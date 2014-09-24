<?php
	include 'db.php';
	if($_POST){
		switch ($_POST['name']){
			case 'all':
				$query = "SELECT * FROM songs ORDER BY time DESC";
				getlist($query);
				break;
			default:
				$query =  "SELECT * FROM songs WHERE tag = '".$_POST['name']."' ORDER BY name DESC";
				getlist($query);
		}
	}else{
		$query = "SELECT * FROM songs ORDER BY time DESC";
		getlist($query);
	}
	
	
	
	
	
	function getlist($query){
		$myfile = 'js/myplaylist.js';
		fopen($myfile,'w');
		$current = "var myPlaylist = [\r\n";
		$songs = mysql_query($query);
		while(($row = mysql_fetch_object($songs)) != false){
			$current .= "{\r\n";
			$current .= "mp3:";
			$current .= "'".$row->address."',\r\n";
			$current .= "title:";
			$current .= "'".$row->name."',\r\n";
			$current .= "artist:";
			$current .= "'".$row->singer."',\r\n";
			$current .= "duration:";
			$current .= "'".$row->duration."',\r\n";
			$current .= "cover:";
			$current .= "'".$row->cover."',\r\n";
			$current .= "},\r\n" ;
		}
		$current .= "];";
		file_put_contents($myfile, $current);
	}
?>