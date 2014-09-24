<?php
class Customer {
	protected $cellphone;
	protected $name;
	protected $amount;
	protected $note;


	function __construct($cellphone,$name) {
		$this->cellphone = $cellphone;
		$this->name =$name;
		$this->amout = 0;
		$this->note = "";
	}
}
?>