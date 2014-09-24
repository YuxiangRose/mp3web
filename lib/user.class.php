<?php

class User {
	protected $id;
	protected $name;
	protected $nickname;
	
	
	function __construct($id,$name,$nickname) {
		$this->id = $id;
		$this->name =$name;
		$this->nickname = $nickname;
	}
	
	
}

?>