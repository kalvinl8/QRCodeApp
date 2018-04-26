<?php
class AP_Filled_With_File_Task_Type extends AP_Base_Task_Type{

    protected $name_space = __CLASS__;
    protected $field_type = "filled_with_file_task";
    protected  $field_name = "Filled With File upload Task";
    protected  $admin_fields;
    private static $_instance = null;

        public static function get_instance() {
    if ( self::$_instance == null ) {
        self::$_instance = new self();
    }

    return self::$_instance;
}


   






    

    function admin_fields($data=null){
  
        $task_default_text =    isset($data["task_default_text"]) ? $data["task_default_text"] : ""; 
       
        $this->admin_fields = array(

                                array(
                                     "title"=>"Default Text",
                                     "type"=>"textarea",
                                     "name"=>"task_lib[__addNoHere__][meta_data][task_default_text]",
                                     "place_holder"=>"Enter Task Defaut Text Here",
                                     "name_space"=>$this->name_space,
                                     "value"=>$task_default_text
                                   ),


        );
  

         // echo "<pre>";var_dump($this->render_fields($data)); 
       // $this->admin_fields = array();
        return $this->render_fields($this->admin_fields);

    }

   
    

    
}