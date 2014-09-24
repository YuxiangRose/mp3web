<?php
include 'db.php';

	if($_POST){
		switch($_POST["action"]){
			case "search":
				getCustomerInfo($_POST["values"],"查无此人，请重新输入手机号!!");
				break;
			case "insert":
				insertNewCustomer();
				break;
			case "getNote":
				getNote($_POST["cell"]);
				break;
			case "deleteNote":
				deleteNote($_POST["note"],$_POST["cell"]);
				break;
			case "modify":
				modifyCustomer($_POST["oldcell"]);
				break;
			case "deleteCustomer":
				deleteCustomer($_POST["cell"]);
				break;
		}
	}
	function getCustomerInfo($cellnumber,$error_info){
		$arr = array();
		$query = "SELECT * FROM customer WHERE cellnumber = '".$cellnumber."'";
		$result = mysql_query($query);
		if(($row = mysql_fetch_object($result)) == true){
			
			$arr['cell']= $row->cellnumber;
			$arr['name']= $row->name;
			$arr['time']= $row->createtime;
			$arr['amount']= $row->amount;
			$query_note = "SELECT * FROM customer_note WHERE cellnumber = '".$cellnumber."'";
			$result_note = mysql_query($query_note);
			$note = "";
			while(($row = mysql_fetch_object($result_note)) == true){
				$note .= "<p class='info_note'>".$row->note."</p>\r\n";
			}
			$arr['note']= $note;
			echo json_encode($arr);
		}else{
			$arr['cell']= "N/A";
			$arr['name']= "N/A";
			$arr['time']= "N/A";
			$arr['amount']= 0;
			$arr['note']= "<p class='info_note'>".$error_info."</p>\r\n";
			echo json_encode($arr);
		}
	}
	
	function insertNewCustomer(){
		$already = checkCustomer($_POST['cell']);
		if(!$already){
			$arr = array();
			$query = "INSERT INTO customer (cellnumber, name, amount, createtime) VALUES ('".$_POST['cell']."', '".$_POST['name']."', '0', CURRENT_TIMESTAMP);";
			$result = mysql_query($query);
			if($_POST['note']){
				$query = "INSERT INTO customer_note (cellnumber, note) VALUES ('".$_POST['cell']."', '".$_POST['note']."');";
				$result_note = mysql_query($query);
			}		
			getCustomerInfo($_POST['cell'],"");
		}
	}
	
	function checkCustomer($cell){
		$query = "SELECT * FROM customer WHERE cellnumber = '".$cell."'";
		mysql_query($query);
		if(mysql_affected_rows() > 0){
			getCustomerInfo("0","这个号码已经注册，试试查找呗，亲！！！");
			return true;
		}else{
			return false;
		}
	}
	
	function getNote($cell){
		$arr = array();
		$index = 0;
		$query = "SELECT * FROM customer_note WHERE cellnumber = '".$cell."'";
		$result= mysql_query($query);
		while(($row = mysql_fetch_object($result)) == true){
			$arr[$index]['note']= $row->note;
			$arr[$index]['sale']= $row->salenote;
			$index++;
		}
		echo json_encode($arr);
	}
	
	function deleteNote($note,$cell){
		$query = "DELETE FROM customer_note WHERE note = '".$note."' AND cellnumber = '".$cell."'";
		mysql_query($query);
		getCustomerInfo($cell,"累了删不动了");
	}
	
	function modifyCustomer($cell){
		$sql = "UPDATE customer_note SET cellnumber = '".$_POST['cell']."' WHERE cellnumber = '".$cell."'";
		mysql_query($sql);
		if($_POST['note']){
			$query = "INSERT INTO customer_note (cellnumber, note) VALUES ('".$_POST['cell']."', '".$_POST['note']."');";
			$result_note = mysql_query($query);
		}
		$query = "UPDATE customer SET cellnumber = '".$_POST['cell']."', name = '".$_POST['name']."' WHERE cellnumber = '".$cell."'";
		mysql_query($query);
		if(mysql_affected_rows()>0 || $result_note){
			getCustomerInfo($_POST['cell'],"");
		}
	}
	
	function deleteCustomer($cell){
		$query = "DELETE FROM customer WHERE cellnumber = '".$cell."'";
		echo $query;
		mysql_query($query);
		echo mysql_affected_rows();
		if(mysql_affected_rows()>0){
			getCustomerInfo($cell,"");
		}
	}
?>


















