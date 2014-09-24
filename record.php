<?php
	include 'db.php';
	
	if($_POST){
		switch($_POST["action"]){
			case "insert":
				insertNewRecord();
				break;
			case "checkCell":
				getCustomerInfo($_POST['cell']);
				break;
			case "getRecords":
				getRecords($_POST['from'],$_POST['to']);
				break;
		}
	}
	
	function checkCustomer($cell){
		$query = "SELECT * FROM customer WHERE cellnumber = '".$cell."'";
		mysql_query($query);
		if(mysql_affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function getCustomerInfo($cellnumber){
		$arr = array();
		$query = "SELECT * FROM customer WHERE cellnumber = '".$cellnumber."'";
		$result = mysql_query($query);
		if(($row = mysql_fetch_object($result)) == true){	
			$arr['cell']= $row->cellnumber;
			$arr['name']= $row->name;
			echo json_encode($arr);
		}else{
			$arr['cell']= "";
			$arr['name']= "";
			echo json_encode($arr);
		}
	}
	
	function insertNewRecord(){
		if($_POST['type'] == "in" ){
			$type = "收入";
			$amount = $_POST['amount'];
			switch($_POST['member']){
				case 'new':
					$query = "INSERT INTO customer (cellnumber, name, amount, createtime) VALUES ('".$_POST['cell']."', '".$_POST['name']."', '".$_POST['amount']."', CURRENT_TIMESTAMP);";
					$result = mysql_query($query);
					$note = $_POST['note']." ".$_POST['amount']." 元";
					$sql = "INSERT INTO customer_note (cellnumber, note,salenote) VALUES ('".$_POST['cell']."', '".$note."','1');";
					$result_note = mysql_query($sql);
					break;
				case 'old':
					$query = "SELECT * FROM customer WHERE cellnumber = '".$_POST['cell']."'";
					$result = mysql_query($query);
					$row = mysql_fetch_object($result);
					$amountSum = $row->amount + $_POST['amount'];
					$query = "UPDATE customer SET amount = '".$amountSum."' WHERE cellnumber = '".$_POST['cell']."'";
					mysql_query($query);
					$note = $_POST['note']." ".$_POST['amount']." 元";
					$sql = "INSERT INTO customer_note (cellnumber, note,salenote) VALUES ('".$_POST['cell']."', '".$note."','1');";
					$result_note = mysql_query($sql);
					break;
				case 'ignore':
					break;
			}			
		}else{
			$type = "支出";
			$amount = $_POST['amount']*-1;
		}
		$query = "INSERT INTO record (type,amount,note,date) VALUES ('".$type."', '".$amount."', '".$_POST['note']."', CURRENT_TIMESTAMP);";
		$result = mysql_query($query);
		$id = mysql_insert_id();
		
		$sql = "SELECT * FROM record WHERE rid = '".$id."'";
		$record = mysql_query("$sql");
		$insertRow = mysql_fetch_object($record);
		
		$arr = array();
		$arr['type']= $insertRow->type;
		$arr['amount'] = $insertRow->amount;
		$arr['note']= $insertRow->note;
		$arr['date']= $insertRow->date;
		
		echo json_encode($arr);
	}
	
	function getRecords($from,$to){
		$arr = array();
		$index = 0;
		$from = date('Y-m-d H:i:s', $from);
		$to = strtotime('+1 day', $to);
		$to = date('Y-m-d H:i:s', $to);
		$query = "SELECT * FROM record WHERE date BETWEEN '".$from."' AND '".$to."'";
		$results = mysql_query($query);
		while($row = mysql_fetch_object($results)){
			$arr[$index]['date']= $row->date;
			$arr[$index]['amount']=$row->amount;
			$arr[$index]['note'] = $row->note;
			$arr[$index]['type']= $row->type;
			$index++;
		}

		echo json_encode($arr); 
	}
?>






















