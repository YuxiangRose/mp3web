<?php
	include 'header.php';
	include 'db.php';
	include '/lib/user.class.php';
?>
<script>
$(document).ready(function(){
	$("#login-form").submit(function(event) {
		event.preventDefault();
		var values = $(this).serialize();
		 $.ajax({
			url: "login.php",
			type: "post",
			data: values,
			success: function(data){
				switch(data){
					case "login_lan":
						alert("你终于又来了，我都想你了！！！");
						window.location.href = "main.php";
						break;
					case "login":
						alert("开业大吉，生意兴！！！！");
						window.location.href = "main.php";
						break;
					case "password_error":
						alert("密码输入错了 你个笨蛋!!!!!");
						break;
					case "no_user":
						alert("你是谁啊，我不认识你！！！！ 用户名错了吧，2货");
						break;
				}
			}
		});	
	});
});
</script>
<div class= "login">
	<div class="container">
		<form id="login-form" action="" method="post">
			<div class="formcell">是谁？<input type="text" id = "username" class="inputbox" name="username" value=""><br></div>
			<div class="formcell">口令？<input type="password" id = "password" class="inputbox" name="password" value=""><br></div>
			<div class="formcell"><input type="submit" value="让我进去" class="btn" /></div>
		</form>
	</div>
</div>


<!-- <div id="player"> -->
 <?php 
//  	include 'music-block.php';
 ?>
<!-- </div> -->
<?php 	
	include 'footer.php';
?>