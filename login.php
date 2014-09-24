<?php
//include 'header.php';
include 'db.php';
include '/lib/user.class.php';

if($_POST){
	session_start();
	$query = "SELECT * FROM user WHERE name = '".$_POST['username']."'";
	$result = mysql_query($query);
	if(($row = mysql_fetch_object($result)) == true){
		if(md5($_POST['password'])== $row->password){
			$user = new User($row->id, $row->name, $row->nickname);
			$_SESSION['user'] = $user;
			if($row->name == "lanhua"){
				echo "login_lan";
			}else{
				echo "login";
			}
		}else{
			echo "password_error";
		}
	}else{
		echo "no_user";
	}
}

?>