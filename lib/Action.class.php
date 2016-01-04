<?php 

class Action{

	public function __construct(){
		$this->db = new mysql();
	}

	
	public function display($data='',$tpl=''){
		if(!$tpl){
			$tpl = str_replace("Action", '', getC()).'/'.getM();
		}
		if($data){
			extract($data);  
		}
		include('./view/'.$tpl.'.php');
	}


}