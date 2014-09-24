

 <script>
	
	$(document).ready(function(){
		// datepicker section
		$(function() {
			$( "#from" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
				dateFormat: "yy年mm月dd日",
				onSelect: populateList,
				onClose: function( selectedDate ) {
					$( "#to" ).datepicker( "option", "minDate", selectedDate );
				}
			});
			$( "#to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
				dateFormat: "yy年mm月dd日",
				onSelect: populateList,
				onClose: function( selectedDate ) {
					$( "#from" ).datepicker( "option", "maxDate", selectedDate );
				}
			});
		});
		function populateList()
		{
			if($( "#from" ).datepicker( "getDate" ) && $( "#to" ).datepicker( "getDate" )){
				var from = $( "#from" ).datepicker( "getDate" ).getTime() / 1000;
				var to = $( "#to" ).datepicker( "getDate" ).getTime() / 1000;
				$.ajax({
					url: "record.php",
					type: "post",
					dataType: "json",	
					data:{action:'getRecords',from:from,to:to},
					success: function(data){
						$("#record-table > tbody").html("")
						for(var i =0;i < data.length;i++)
						{
							if(data[i]['amount']>0){
								var tableRow = "<tr class='in-row'><td class='date-col'>"+data[i]['date']+"</td><td class='type-col'>"+data[i]['type']+"</td><td class='note-col'>"+data[i]['note']+"</td><td class='amount-col sale-amount'>"+data[i]['amount']+"</td></tr>";
							}else{
								var tableRow = "<tr class='out-row'><td class='date-col'>"+data[i]['date']+"</td><td class='type-col'>"+data[i]['type']+"</td><td class='note-col'>"+data[i]['note']+"</td><td class='amount-col sale-amount'>"+data[i]['amount']+"</td></tr>";
							}
							$('#record-table > tbody:first').append(tableRow);
						}
						var total = getSum();
						if(total>0){
							$('.sum-cell').css( "color", "green" );
						}else{
							$('.sum-cell').css( "color", "red" );
						}
						$('.sum-cell').html(total);
					}
				});
			}
		};
		//popup form validation check
		function checkType(){
			if($("input[name='type']:checked").val()){
				return true;
			}
			else{
				alert("你是花钱还是收钱，选一个呗！！");
				return false;
			}
		}
		
		function checkMember(){
			if($("input[name='type']:checked").val() == "in"){
				if($("input[name='member']:checked").val()){
					return true;	
				}else{
					alert("这人是会员么？选一下能死么？");
					return false;
				}
			}
			else{
				return true;
			}
		}

		function checkAmount(){
			
			if( $.isNumeric($(".record-amout").val())){
				return true;
			}else{
				alert("多少钱，给个数呗");
				return false;
			}
		}
		function checkNote(){
			if($("#record-note").val() != ""){
				return true;
			}else{
				alert("这钱花哪了？记一下呗！!");
				return false;
			}
		}

		$('#record-cell').keyup(function () {
		    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
		       this.value = this.value.replace(/[^0-9\.]/g, '');
		    }
		});

		function checkLength(){
			if($("#record-cell").val().length != 11 && $("input[name='type']:checked").val() == "in" && $("input[name='member']:checked").val() != "ignore"){
				alert( "手机号必须11位。谢谢！！！" );
				vaild = false;
				return vaild;
			}
			return true;
		}

		$( "#record-cell" ).focus(function() {
			document.getElementById("old").disabled = false;
			document.getElementById("new").disabled = false;
		});
		
		$( "#record-cell" ).focusout(function(){
			
			var cell = $( "#record-cell" ).val();
			$.ajax({
				url: "record.php",
				type: "post",
				dataType: "json",	
				data:{action:'checkCell',cell:cell},
				success: function(data){
					if(data['cell'] != ""){
						$("#old").prop("checked", true);
						$("#record-name").val(data['name']);
						document.getElementById("new").disabled = true;
					}else{
						$("#new").prop("checked", true);
						$("#record-name").val('');
						document.getElementById("old").disabled = true;
					}
				}
			});
		})

		//popup section
		var recordpopup,form,
		recordpopup = $( "#record-popup" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			title: "添加记录",
			dialogClass: "popup-record",
			dialogClass: "no-close",
			hide: { effect: "explode", duration: 1000 },
			show: { effect: "drop", duration: 500 },
			buttons: {
				"保存": addRecords,
				"取消": function() {
					recordpopup.dialog( "close" );
					$(".hide-for-in").hide();
					$(".hide-for-out").hide();
					document.getElementById("old").disabled = false;
					document.getElementById("ignore").disabled = false;
					document.getElementById("new").disabled = false;
				}
			},
			close: function() {
				form[ 0 ].reset();
			}
		});
		form = recordpopup.find( "form" );


		function addRecords(){
			
			var vaild = true;

			vaild = vaild && checkType();
			vaild = vaild && checkMember();
			vaild = vaild && checkAmount();
			vaild = vaild && checkNote();
			vaild = vaild && checkLength();

			var member = $("input[name='member']:checked").val();
			var type = $("input[name='type']:checked").val();
			var cell = $("#record-cell").val();
			var name = $("#record-name").val();
			var note = $("#record-note").val();
			var amount = $(".record-amout").val();

			//$( "#record-cell" ).focusout();
			
			if(vaild){
				$.ajax({
					url: "record.php",
					type: "post",
					dataType: "json",	
					data:{action:'insert',cell:cell,name:name,note:note,amount:amount,type:type,member:member},
					success: function(data){
						if(data['amount']>0){
							var tableRow = "<tr class='in-row'><td class='date-col'>"+data['date']+"</td><td class='type-col'>"+data['type']+"</td><td class='note-col'>"+data['note']+"</td><td class='sale-amount amount-col'>"+data['amount']+"</td></tr>";
						}else{
							var tableRow = "<tr class='out-row'><td class='date-col'>"+data['date']+"</td><td class='type-col'>"+data['type']+"</td><td class='note-col'>"+data['note']+"</td><td class='sale-amount amount-col'>"+data['amount']+"</td></tr>";
						}
						$('#record-table > tbody:first').append(tableRow);
						var total = getSum();
						
						$('.sum-cell').html(total);
					}
				});
				recordpopup.dialog( "close" );		
			}	
		}

		function getSum(){
			var total = 0;
			$( ".sale-amount" ).each(function(){
				total = total + parseFloat($( this ).text());
			});
			total = total.toFixed(2)
			return total;
		}

		
		$("#recordcaller" ).button().on( "click", function() {
			recordpopup.dialog( "open" );
		});


		$('input:radio[name="type"]').change(function(){
	        if ($(this).val() == 'in'){
		        $(".hide-for-in").show();
		        $(".hide-for-out").show();
	        }
	        else {
	        	$(".hide-for-in").hide();
		        $(".hide-for-out").show();
	        }
	    });

		$('input:radio[name="member"]').change(function(){
	        if ($(this).val() == 'new'){
		        $(".cell-info").show();
		        $(".name-info").show();
	        }
	        if ($(this).val() == 'old'){
		        $(".cell-info").show();
		        $(".name-info").show();
	        }
	        if ($(this).val() == 'ignore'){
		        $(".cell-info").hide();
		        $(".name-info").hide();
	        }
	    });
	});
</script>
<style>
.form-cell-popup label{
	width: 60px;
}
</style>

<div id="record-container">
	<form class="data-form">
		<label for="from">从</label>
		<input type="text" id="from" name="from" class="date-input text ui-widget-content ui-corner-all">
		<label for="to">到</label>
		<input type="text" id="to" name="to" class="date-input text ui-widget-content ui-corner-all">
	</form>
	<button id="recordcaller" class="popcaller">记录</button>
	<div id="record-display">
		 <table id="record-table">
		 	<thead>  
			  <tr>
			    <th class="date-col">日期</th>
			    <th class="type-col">类型</th>
			    <th class="note-col">详细</th>
			    <th class="amount-col">金额</th>
			  </tr>
			</thead>
			<tbody class="record-body">
			</tbody>
			<tfoot>
				<tr class="sum-row">
			    	<td colspan="4" class="sum-cell">0</td>
			  	</tr>
			</tfoot>
		</table> 
	</div>
</div>


<div id="record-popup" >
	<form>
		<div class="form-cell-popup">
			<label for="name">类型</label>
			<input class="type-radio" type="radio" name="type" value="in" class="text ui-widget-content ui-corner-all"><label for="type">售出</label>
			<input class="type-radio" type="radio" name="type" value="out" class="text ui-widget-content ui-corner-all"><label for="type">支付</label>
		</div>
		<div class="hide-for-in">
			<div class="form-cell-popup">
				<label for="name">会员</label>
				<input class="member-radio" type="radio" id="new" name="member" value="new" class="text ui-widget-content ui-corner-all"><label>新会员</label>
				<input class="member-radio" type="radio" id="old" name="member" value="old" class="text ui-widget-content ui-corner-all"><label>老会员</label>
				<input class="member-radio" type="radio" id="ignore" name="member" value="ignore" class="text ui-widget-content ui-corner-all"><label>忽略</label>
			</div>
			<div class="form-cell-popup cell-info">
				<label for="cell">手机</label>
				<input type="text" id="record-cell" name="cell" value="" class="text ui-widget-content ui-corner-all">
			</div>
			<div class="form-cell-popup name-info">
				<label for="name">姓名</label>
				<input type="text" id="record-name" name="name" value="" class="text ui-widget-content ui-corner-all">
			</div>
		</div>
		<div class="hide-for-out">
			<div class="form-cell-popup">
				<label >金额</label>
				<input type="text" step="any" name="amount" value="" class="record-amout text ui-widget-content ui-corner-all">
			</div>
			<div class="form-cell-popup">
				<label >备注</label>
				<textarea  name="note" id="record-note" class="text ui-widget-content ui-corner-all"></textarea>
			</div>
		</div>
		
	</form>
</div>


















