<?php
class AP_Multi_Element_With_Parameters_Task_Type extends AP_Base_Task_Type{

    protected $name_space = __CLASS__;
    protected $field_type = "multi_ele_with_paramerts_task";
    protected  $field_name = "Multi Element With Parameters";
    protected  $admin_fields;
    private static $_instance = null;

        public static function get_instance() {
    if ( self::$_instance == null ) {
        self::$_instance = new self();
    }

    return self::$_instance;
}


   





    

    

    function admin_fields($data=null){

      $min_elements =    isset($data["min_elements"]) ? $data["min_elements"] : ""; 
      $parameters =    isset($data["parameters"]) ? $data["parameters"] : ""; 
       
        $this->admin_fields = array(

                                array(
                                     "title"=>"Min Elements",
                                     "type"=>"number",
                                     "name"=>"task_lib[__addNoHere__][meta_data][min_elements]",
                                     
                                     "name_space"=>$this->name_space,
                                     "value"=>$min_elements
                                   ),
                                array(
                                     "title"=>"Parameters",
                                     "type"=>"input_repater",
                                     "name"=>"task_lib[__addNoHere__][meta_data][parameters][]",
                                     "place_holder"=>"",
                                     "name_space"=>$this->name_space,
                                     "saved_data"=>$parameters
                                   )


        );
  
       

         // echo "<pre>";var_dump($this->render_fields($data)); 
       // $this->admin_fields = array();
        return $this->render_fields($this->admin_fields);

    }



    

    
}