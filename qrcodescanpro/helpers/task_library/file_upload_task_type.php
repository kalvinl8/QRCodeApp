<?php
class AP_File_Upload_Task_Type extends AP_Base_Task_Type{

    protected $name_space = __CLASS__;
    protected $field_type = "file_upload_task";
    protected  $field_name = "File Upload";
    protected  $admin_fields;
    private static $_instance = null;

        public static function get_instance() {
    if ( self::$_instance == null ) {
        self::$_instance = new self();
    }

    return self::$_instance;
}


   





    

  

    function admin_fields($data=null){


       $allowed_file_types =    isset($data["allowed_file_types"]) ? $data["allowed_file_types"] : ""; 
       
        $this->admin_fields = array(

                                array(
                                     "title"=>"Allowed File Types (e.g jpg|png)",
                                     "type"=>"text",
                                     "name"=>"task_lib[__addNoHere__][meta_data][allowed_file_types]",
                                     
                                     "name_space"=>$this->name_space,
                                     "value"=>$allowed_file_types
                                   ),


        );
  
        

         // echo "<pre>";var_dump($this->render_fields($data)); 
       // $this->admin_fields = array();
        return $this->render_fields($this->admin_fields);

    }


   

    

    
}