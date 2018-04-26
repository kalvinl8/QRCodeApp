<?php
class AP_Logical_Task_Type extends AP_Base_Task_Type{

    protected $name_space = __CLASS__;
    protected $field_type = "logical_task";
    protected  $field_name = "logical";
    protected  $admin_fields;
    private static $_instance = null;

        public static function get_instance() {
    if ( self::$_instance == null ) {
        self::$_instance = new self();
    }

    return self::$_instance;
}


   





   

    function admin_fields($data=null){

      $choices =    isset($data["choices"]) ? $data["choices"] : "";
    //var_dump($choices);die; 
        $this->admin_fields = array(array(
                                     "title"=>"Choices",
                                     "type"=>"input_repater",
                                     "name"=>"task_lib[__addNoHere__][meta_data][choices][]",
                                     "place_holder"=>"Enter Choice here",
                                     "name_space"=>$this->name_space,
                                     "saved_data"=>$choices
                                   ));

   

         // echo "<pre>";var_dump($this->render_fields($data)); 
       // $this->admin_fields = array();
        return $this->render_fields($this->admin_fields);

    }

   

    

    
}