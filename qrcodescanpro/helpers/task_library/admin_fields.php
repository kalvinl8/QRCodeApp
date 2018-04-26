<?php

class Admin_Fields {


	static function inputField($data){
		
        echo '<input type="text" placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].']" value="'.$data["value"].'" class="ap_task_title_field" id="">';
	}


  static function textarea($data){
    
    
        echo '<textarea style="height:100px"  placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].']"  class="ap_task_title_field" id="">'.$data["value"].'</textarea>';
  }



	static function inputFieldNumber($data){ 
		
        echo '<input type="number" placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].']" min="0" value="'.$data["value"].'" class="ap_task_title_field" id="">';
	}



	static function input_repater($data){
		$val1 = (isset($data["value"][0]))?$data["value"][0]:"";
		$repHtml = "";
	     if(isset($data["value"][0])){
	     	unset($data["value"][0]);

           foreach ($data["value"] as $key => $val) {
           	  $repHtml .= '<div class="ap_repated"><input type="text" placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].'][]" value="'.$val.'" class="" id=""><span class="dashicons dashicons-no"></span></div>'; 
           }
	     }  


          $add_btn_lable = (isset($data["btn_name"]))?$data["btn_name"]:"Add Choice";
	     

		 echo '<input class="ap_task_input_repater" data-d="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].'][]" type="text" placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].'][]" value="'.$val1.'"  id=""><div class="ap_input_repater_area">'.$repHtml.'</div><a class="button button-primary ap_input_repater_add_choice" id="">'.$add_btn_lable.'</a>';
	}




  static function input_repater_tasks($data){

    //echo "<pre>";var_dump($data["value"]["choices"]);
    $val1 = (isset($data["value"]["choices"][0]))?$data["value"]["choices"][0]:"";
    $val2 = (isset($data["value"]["fields"][0]))?$data["value"]["fields"][0]:"";
    $repHtml = "";
       if(isset($data["value"]["choices"][0])){
        unset($data["value"]["choices"][0]);
        unset($data["value"]["fields"][0]);
          $select_Count = 2;
           foreach ($data["value"]["choices"] as $key => $val) {
              $repHtml .= '<div class="ap_repated_tasks"><input type="text" placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].'][]" value="'.$val.'" class="" id="">'.self::tasks_select($data,$select_Count++,$data["value"]["fields"][$key]).'<span class="dashicons dashicons-no remove_re_tasks"></span></div>'; 
           }
       }  


          $add_btn_lable = (isset($data["btn_name"]))?$data["btn_name"]:"Add Choice";
       

     echo '
<style>.ap_task_input_repater_tasks > select,.ap_repated_tasks > select{
    float: left;
    margin-bottom: 10px;
    width: 90%;
}

.ap_repated_tasks > input {
    width: 100% !important;
}
</style><div class="ap_task_input_repater_tasks"><input class="" data-d="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].'][]" type="text" placeholder="'.$data["place_holder"].'"  name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].'][]" value="'.$val1.'"  id="">'.self::tasks_select($data,1,$val2).'</div><div class="ap_input_repater_area">'.$repHtml.'</div><a class="button button-primary ap_input_repater_add_choice_tasks" id="">'.$add_btn_lable.'</a>';
  }


	function help($data){



		$args = array(
    'posts_per_page'   => -1,
    'offset'           => 0,
   
   
    'post_type'        => 'ap_lessons',
    'tag' => 'task_help',
    'post_status'      => 'publish',
    'orderby'          => 'date',
    'order'            => 'ASC',
    
);
$posts_array = get_posts( $args );


             $html .= '<select name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].']">';
             $html .= "<option value='0'>--Select Help--</option>";

             foreach ($posts_array as $key => $value) {
             	$se = ($data["value"] == $value->ID) ? "selected='true'":"";
             	$html .= "<option $se value='".$value->ID."'>".$value->post_title."</option>";
             }


              $html .= "</select>";

              echo $html;
	}



  function risk_level($data){



          $sel = $data["value"];
          $sell = ($sel == "low")?"selected='true'":"";
          $selm = ($sel == "medium")?"selected='true'":"";
          $selh = ($sel == "high")?"selected='true'":"";
          $selc = ($sel == "critical")?"selected='true'":"";
        

             $html .= '<select name="task_lib['.$data["no"].']['.$data["name_space"].']['.$data["id"].']">';
             $html .= "<option  value='0'>--Select Level--</option>";
             
             
              $html .= "<option $selc value='critical'>Critical</option>";
             $html .= "<option $selh value='high'>High</option>";
             $html .= "<option $selm value='medium'>Medium</option>";
             $html .= "<option $sell value='low'>Low</option>";
            

            

              $html .= "</select>";

              echo $html;
  }

  function tasks_select($data,$index,$selected =null){
   
   
 $posts_array = AP_Task_Libraries::getByRiskLevel("conditional",1);
 

  //echo "<pre>";var_dump((array)$data["value"]["fields"][$index]); 
        $html .= '<select multiple="multiple" name="task_lib['.$data["no"].']['.$data["name_space"].'][fields]['.$index.'][]">';
             
             
          
              foreach ($posts_array as $key => $value) {
                $pid = $value["id"];


                foreach ($value["tasks"][0] as $ke2 => $value2) {
                  $k = key($value2);

                  
                $s = (in_array($pid."-".$ke2,$data["value"]["fields"][$index]))?'selected':'';

                $html .= "<option  ".$s."  value='".$pid."-".$ke2."'>".$value2[$k]["task_title"]."</option>";
                  
                }
                
              }
               
               
           
             
             
             $html .= "</select>";
            return  $html;

  }


}