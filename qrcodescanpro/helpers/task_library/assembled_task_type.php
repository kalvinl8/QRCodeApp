<?php
class AP_Assembled_Task_Type extends AP_Base_Task_Type
{
    
    protected $name_space = __CLASS__;
    protected $field_type = "assembled_task_type";
    protected $field_name = "Assembled Task Type";
    protected $admin_fields;
    private static $_instance = null;
    
    public static function get_instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

   


    
    
    
    
}