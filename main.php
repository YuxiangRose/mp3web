<?php 
session_start();
	if(!isset($_SESSION['user'])){
		header("Location: index.php");
	}else{
		include("header.php");
	}
	
?>
<div>
	<div class="music-block">
	<?php 
		include("music-block.php");
	?>
	</div>
	
	<div class= "customer-block">
	<?php 
		include("customer-block.php");
	?>
	</div>
	
	<div class="record-block">
	<?php 
		include("record-block.php");
	?>
	</div>
	
	
	
</div>