<?php

class Task_Library_Model{



	function save_task_list(){
         global $db;
         $db->task_library[] = [
         
    'title' => 'Hello world 2',
    "risk_level"=>"Level 1",
    "tl_order"=>1,
    "user_id"=>1
];
	}
}