<?php
class AP_Base_Task_Type
{
    protected $name_space = __CLASS__;
    protected  $field_type = "base";
    protected  $initiated;
    protected $admin_fields;
    protected  $field_name = "Base";
    private $_instance = null;
    
    
    
    /*
     * singltone 
     */
    public static function get_instance()
    {
        if ($this->_instance == null) {
            $this->_instance = new self();
        }
        
        return $this->_instance;
    }
    
    /*
     * init hooks etc
     */
    function __construct()
    {


        $this->init();
        $this->add_to_dropdown();
       
    }

   
    
    function init()
    {
        
        if (!$this->initiated) {
         //   $this->init_hooks();
        //  self::add_to_dropdown();
            
            
            $this->initiated = true;
            
        }
        
        
        
    }
    
    
    
    
    // function init_hooks()
    // {
    //     add_filter("AP_task_type_dropdown", array(
    //         $this,
    //         "add_to_dropdown"
    //     ));
    //     add_action('admin_enqueue_scripts', array(
    //         $this,
    //         'load_script'
    //     ));

    //      add_action('wp_ajax_ap_'.$this->name_space.'_admin_out', array(
    //         $this,
    //         'admin_out'
    //     ));
         
    // }

    function admin_fields($data=null){
         
    }
    
     function admin_out($data=null)
    {
      
        $id  =  isset($_GET["data"]) ? (int)$_GET["data"] : false; 
        $no  =  isset($_GET["no"]) ? (int)$_GET["no"] : 0; 
        $data_to_task = null;
        global  $db;
        if($id){

           
           $lib_task_data = $db->library_task[$id];
           $data_to_task = isset(array_values($lib_task_data->library_task_meta->meta_value)[0]) ?  unserialize(array_values($lib_task_data->library_task_meta->meta_value)[0]) : null;
          // var_dump(array_values($lib_task_data->library_task_meta->meta_value)[0]);die;
        }   

        $task_help = $db->task_help->select()
    ->orderBy('id DESC')
    ->run();;
        $task_help_array = [];
        foreach ($task_help as $key => $value) {
           $task_help_array[$value->id] = $value->title;
        }
         $this->admin_fields = array(
                               
                                 array(
                                     "title"=>"Task Title",
                                     "type"=>"textarea",
                                     "name"=>"task_lib[$no][task_title]",
                                     "place_holder"=>"Enter Task Title Here",
                                     "name_space"=>$this->name_space,
                                     "value"=> isset($lib_task_data->title) ? $lib_task_data->title : ""
                                   ),
                                 array(
                                     "title"=>"Risk level",
                                     "type"=>"select",
                                     "name"=>"task_lib[$no][task_risk_level]",
                                     "place_holder"=>"Enter Task Title Here",
                                     "name_space"=>$this->name_space,
                                     "options"=>array(
                                                     "Critical"=>"Critical",
                                                     "High"=>"High",
                                                     "Medium"=>"Medium",
                                                     "Low"=>"Low",
                                                    
                                                      ),
                   
                                     "selected"=> isset($lib_task_data->risk_level) ? $lib_task_data->risk_level: ""
                                   ),


                                 array(
                                     "title"=>"Tranning",
                                     "type"=>"select",
                                     "name"=>"task_lib[$no][task_tranning]",
                                     "place_holder"=>"Enter Task Title Here",
                                     "name_space"=>$this->name_space,
                                     "options"=>$task_help_array,
                   
                                     "selected"=> isset($lib_task_data->task_help_id) ? $lib_task_data->task_help_id: ""
                                   ),

                                 );
         
         ob_start();
          
      echo    $this->render_fields($this->admin_fields); 
      echo "<input type='hidden' name='task_lib[".$no."][task_name_space]' value='".$this->name_space."'>";
       echo $this->admin_fields($data_to_task);

      
      if($data == null){
        ob_end_flush();
        die;
      }else{
       $f=  ob_get_contents(); 
       ob_end_clean();
       return $f;
      }


            
        
        
    }
    
   
   
    
    
    function add_to_dropdown()
    {

      global $AP_task_type_dropdown;
       $AP_task_type_dropdown[$this->name_space] =  $this->field_name;
        
    }
    
    

    function render_fields($fields){
    //   echo "<pre>"; var_dump($fields);
        $fieldHTML  = "";
        foreach ($fields as $key=>$field) {
         $des = (isset($field["data"]["des"]))?" <span class='ap_title_des'>( ".$field["data"]["des"]." )<span>":"";
            ob_start();
         ?>
         <div class="action_plan-field action_plan-field-text ">
                                    <div class="action_plan-label">
                                        <label for=""><?php echo $field["title"].$des; ?></label>
                                    </div>
                                    <div class="action_plan-input">
                                        <div class="action_plan-input-wrap">
                                            <?php

                                            //--------------------------------------
                                            switch ($field["type"]) {
                                                case 'text':
                                                case 'number':
                                          echo  Admin_Form_Fields_Helper::input($field);
                                                    break;
                                                case "input_repater":
                                                
                                                 echo  Admin_Form_Fields_Helper::input_repater($field);
                                                break;

                                                case "input_repater_tasks":
                                                
                                                 Admin_Fields::input_repater_tasks($field["data"]);
                                                break;
                                                case "input_number":
                                                
                                                 Admin_Fields::inputFieldNumber($field["data"]);
                                                break;

                                                case "help":
                                                
                                                 Admin_Fields::help($field["data"]);
                                                break;

                                                case "select":
                                                
                                                 echo  Admin_Form_Fields_Helper::select($field);
                                                break;

                                                case "textarea":
                                                
                                                echo  Admin_Form_Fields_Helper::textarea($field);
                                                break;

                                                
                                                
                                                
                                            }

                                            //======================================
                                             ?>
                                        </div>
                                    </div>
                                </div>
         <?php

       $fieldHTML  .=   ob_get_contents();
         ob_end_clean();
            
        }

        return  $fieldHTML;
    }



   



  //===============================  Save Backend Data =======================

    final public function save_back_end_tasks(){
        if(isset($_POST["task_lib"]) && isset($_POST["task_lib"][0]["task_title"])){   // if form is submitted.
      
          global $db;
          $id  = false;
          if(isset($_GET["edit"]) && (int)$_GET["edit"] != 0){
              //  unset($db->task_library[(int)$_GET["edit"]]);
                $id = (int)$_GET["edit"];
          }
        
              
               $task_list_id = 0;

               $task_lib_data =   array(
                               "title"=>strip_tags($_POST["task_list_title"]),
                               "task_list_g_id2"=>$_POST["task_list_id"],
                               "risk_level"=>$_POST["task_list_risk_level"],

                               "user_id"=>1
                               
                               );
               

               if($id){
                 $task_list_id = $id;
               $db->task_library[$id] =  $task_lib_data;

                 $db->library_task
                          ->delete()
                          ->where('task_library_id = :id', [':id' => $id]) //shortcut of where('id = :id', [':id' => 23])
                          ->run();



                 
               }else{

                
              $db->task_library[] =  $task_lib_data;

               // get last task list id
                 $task_list_id_raw = $db->task_library
                            ->select("id")
                            ->orderBy('id DESC')
                             ->limit(1)
                              ->run();
                              foreach ($task_list_id_raw as $key1 => $value) {
                                  $task_list_id = $value->id;  break;
                              }

               }
              

              

              
              foreach ($_POST["task_lib"] as $key => $task) {
                  //echo "<pre>";var_dump($task["task_tranning"]);die;
                 
                if(!isset($task["task_title"]) || !isset($task["task_name_space"]) || $task["task_title"] == "") continue;
                 $task_id =0;
                 // insert data
           $db->library_task[] = array(
                               "title"=>$task["task_title"],
                               "type"=>$task["task_name_space"],
                               "risk_level"=> $task["task_risk_level"],
                               "task_library_id"=>$task_list_id,
                               "lt_order"=>$key,
                               "task_help_id"=>$task["task_tranning"],
                               );
           // get last task id
                 $task_id_raw = $db->library_task
                            ->select("id")
                            ->orderBy('id DESC')
                             ->limit(1)
                              ->run();
                              foreach ($task_id_raw as $key1 => $value) {
                                  $task_id = $value->id;  break;
                              }
            // inset task meta
                  if(isset($task["meta_data"])){  

                    $db->library_task_meta[] = array(
                               "meta_key"=>"gernal_data",
                               "meta_value"=>serialize($task["meta_data"]),
                               
                               "library_task_id"=>$task_id,
                               );

                  }

               }
               
               set_flash_message("task_lib_saved","Saved","success"); 
               //
               header("location:".BASE_URL."task_lib_listing");
            
         }
    }


    
    
    
} 