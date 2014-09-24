
<script type="text/javascript" src="js/jquery.slimscroll.min.js"></script>
<script>
$(document).ready(function(){
	var popup,form,modify,form_modify,
	
	popup = $( "#popup-container" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		title: "新会员注册",
		dialogClass: "popup",
		dialogClass: "no-close",
		hide: { effect: "explode", duration: 1000 },
		show: { effect: "drop", duration: 500 },
		buttons: {
			"注册": addCustomer,
			"取消": function() {
				popup.dialog( "close" );
			}
		},
		close: function() {
			form[ 0 ].reset();
		}
	});
	modify = $( "#modify-container" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		title: "修改会员信息",
		dialogClass: "popup",
		dialogClass: "no-close",
		hide: { effect: "explode", duration: 1000 },
		show: { effect: "drop", duration: 500 },
		buttons: {
			"保存": modifyCustomer,
			"取消": function() {
				modify.dialog( "close" );
			}
		},
		close: function() {
			form[ 0 ].reset();
		}
	});
	$("#modifycaller" ).button({ disabled: true });
	$("#deletecaller" ).button({ disabled: true });
	$("#search_btn" ).button();
	function checkLength(){
		if($("#cell").val().length != 11){
			alert( "手机号必须11位。谢谢！！！" );
			vaild = false;
			return vaild;
		}
		return true;
	}

	function checkName(){
		if($("#name").val() == ''){
			alert( "给个名字能死么。谢谢！！！" );
			vaild = false;
			return vaild;
		}
		return true;
	}

	function checkCell(){
		if($("#cell").val() == ''){
			alert( "没手机号我注册个毛线啊。谢谢！！！" );
			vaild = false;
			return vaild;
		}
		return true;
	}


	function checkLengthM(){
		if($("#cell-modify").val().length != 11){
			alert( "手机号必须11位。谢谢！！！" );
			vaild = false;
			return vaild;
		}
		return true;
	}

	function checkNameM(){
		if($("#name-modify").val() == ''){
			alert( "给个名字能死么。谢谢！！！" );
			vaild = false;
			return vaild;
		}
		return true;
	}

	function checkCellM(){
		if($("#cell-modify").val() == ''){
			alert( "没手机号我注册个毛线啊。谢谢！！！" );
			vaild = false;
			return vaild;
		}
		return true;
	}

	function modifyCustomer(){
		var vaild = true;
		
		vaild = vaild && checkCellM();
		vaild = vaild && checkNameM();
		vaild = vaild && checkLengthM();
		var oldcell = $("#cell-info").text();
		var cell = $("#cell-modify").val();
		var name = $("#name-modify").val();
		var note = $("#note-modify").val();
		if(vaild){
			$.ajax({
				url: "customer.php",
				type: "post",
				dataType: "json",	
				data:{action:'modify',cell:cell,name:name,note:note,oldcell:oldcell},
				success: function(data){
					outData(data);
				}
			});
			modify.dialog( "close" );
		}	
	}

	
	function addCustomer(){
		var vaild = true;
		
		vaild = vaild && checkCell();
		vaild = vaild && checkName();
		vaild = vaild && checkLength();
		var cell = $("#cell").val();
		var name = $("#name").val();
		var note = $("#note").val();
		if(vaild){
			$.ajax({
				url: "customer.php",
				type: "post",
				dataType: "json",	
				data:{action:'insert',cell:cell,name:name,note:note},
				success: function(data){
					outData(data);
				}
			});
			popup.dialog( "close" );
		}	
	}

	
	$("#customer-search").submit(function(event){
		event.preventDefault();
		var values = $("#customer_cell").val();
		$.ajax({
			url: "customer.php",
			type: "post",
			dataType: "json",
			data:{action:'search',values:values},
			success: function(data){
				outData(data);
				$("#customer_cell").val("");
			}
		});	
		
	});

	

	function outData(data){
		if(data['cell'] != "N/A"){
			document.getElementById("cell-info").innerHTML = data['cell'];
	        document.getElementById("name-info").innerHTML = data['name'];
	        document.getElementById("time-info").innerHTML = data['time'];
	        document.getElementById("amount-info").innerHTML = data['amount'];
	        document.getElementById("note-info").innerHTML = data['note'];
	        $("#modifycaller" ).button({ disabled: false });
	        $("#deletecaller" ).button({ disabled: false });
		}else{
			document.getElementById("cell-info").innerHTML = data['cell'];
	        document.getElementById("name-info").innerHTML = data['name'];
	        document.getElementById("time-info").innerHTML = data['time'];
	        document.getElementById("amount-info").innerHTML = data['amount'];
	        document.getElementById("note-info").innerHTML = data['note'];
	        $("#modifycaller" ).button({ disabled: true });
	        $("#deletecaller" ).button({ disabled: true });
		}
	}

	
	form = popup.find( "form" );
	form_modify = modify.find( "form" );
	$("#popcaller" ).button().on( "click", function() {
		popup.dialog( "open" );
	});
	
	$("#modifycaller" ).on( "click", function() {
		modify.dialog( "open" );
		var cell = $("#cell-info").html();
		var name = $("#name-info").html();
		var note = $("#note-info").html();
		$("#cell-modify").val(cell);
		$("#name-modify").val(name);
		$.ajax({
			url: "customer.php",
			type: "post",
			dataType: "json",
			data:{action:'getNote',cell:cell},
			success: function(data){
				$("#old-note").empty();
				for(var i =0;i < data.length;i++)
				{
				  if(data[i]['sale']== 0){
					  $("#old-note").append("<div class='note-row'><p>"+data[i]['note']+"</p><label class='delete-label'>X</label></div>");
				  }else{
					  $("#old-note").append("<div class='note-row'><p>"+data[i]['note']+"</p></div>");
				  }
				}
			}
		});	
	});
	
	$("#deletecaller" ).on( "click", function() {
		if (confirm("确定缘尽于此？？")) {
			var cell = $("#cell-info").text();
			$.ajax({
				url: "customer.php",
				type: "post",
				dataType: "json",
				data:{action:'deleteCustomer',cell:cell},
				success: function(data){	
				}
			});
			
			document.getElementById("cell-info").innerHTML = "N/A";
	        document.getElementById("name-info").innerHTML = "N/A";
	        document.getElementById("time-info").innerHTML = "N/A";
	        document.getElementById("amount-info").innerHTML ="N/A";
	        document.getElementById("note-info").innerHTML = "N/A";
	        $("#modifycaller" ).button({ disabled: true });
	        $("#deletecaller" ).button({ disabled: true });
	    }
	    return false;
	});

	$( "#modify-container" ).on( "click", '.delete-label', function(){
		var note = $(this).parent().find("p").text();
		var cell = $("#cell-info").html(); 
		$.ajax({
			url: "customer.php",
			type: "post",
			dataType: "json",
			data:{action:'deleteNote',note:note,cell:cell},
			success: function(data){
				outData(data);
				alert("删了，删了，删了");
			}
		});
		$(this).parent().remove();
	});
	
	$('#cell').keyup(function () {
	    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
	       this.value = this.value.replace(/[^0-9\.]/g, '');
	    }
	});
	
	$('#cell-modify').keyup(function () {
	    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
	       this.value = this.value.replace(/[^0-9\.]/g, '');
	    }
	});
	$('.info-holder').slimScroll({
        height: '200px',
        size: '2px',
        wheelStep :'1px',
        width : '430px'
    });
});

</script>
<div class="customer-container">
	<form id="customer-search" action="" method="post">
	  <input id="customer_cell" type="search" name="customer_search"></input>
	  <input id="search_btn" type="submit" value="查找"></input>
	</form>
	<button id="popcaller">注册</button>
	<button id="modifycaller">修改</button>
	<button id="deletecaller">删除</button>
	
	
	<div class="info-holder">
		<div class="info-cell">
			<label>手机号:</label>
			<label id="cell-info">N/A</label>
		</div>
		<div class="info-cell">
			<label>姓名:</label>
			<label id="name-info">N/A</label>
		</div>
		<div class="info-cell">
			<label>创建时间</label>
			<label id="time-info">N/A</label>
		</div>
		<div class="info-cell">
			<label>消费金额</label>
			<label id="amount-info">0</label>
		</div>
		<div class="info-cell note-cell">	
			<label>备注</label>
			<label id="note-info">N/A</label>
		</div>
	</div>
	
	
	
</div>


<div id="popup-container" >
	<form>
		<div class="form-cell-popup">
			<label for="cell">手机号</label>
			<input type="text" name="cell" id="cell" value="" class="text ui-widget-content ui-corner-all">
		</div>
		<div class="form-cell-popup">
			<label for="name">姓名</label>
			<input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all">
		</div>
		<div class="form-cell-popup">
			<label for="password">备注</label>
			<textarea type="text" name="note" id="note" value="" class="text ui-widget-content ui-corner-all"></textarea>
		</div>
	</form>
</div>


<div id="modify-container" >
	<form>
		<div class="form-cell-popup">
			<label for="name">手机号</label>
			<input type="text" name="cell" id="cell-modify" value="" class="text ui-widget-content ui-corner-all">
		</div>
		<div class="form-cell-popup">
			<label for="email">姓名</label>
			<input type="text" name="name" id="name-modify" value="" class="text ui-widget-content ui-corner-all">
		</div>
		<div class="form-cell-popup">
			<label for="password">备注</label>
			<textarea type="text" name="note" id="note-modify" value="" class="text ui-widget-content ui-corner-all"></textarea>
		</div>
		<div class="form-cell-popup" id="old-note">
		</div>
	</form>
</div>












