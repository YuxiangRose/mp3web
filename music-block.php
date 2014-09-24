

<link rel="stylesheet" type="text/css" href="../css/style.css">
<script type="text/javascript" src="/js/jquery.jplayer.js"></script>
<script type="text/javascript" src="/js/ttw-music-player-min.js"></script>
<script type="text/javascript" src="js/myplaylist.js"></script>
<script type="text/javascript" src="js/jquery.slimscroll.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.myplayer').ttwMusicPlayer(myPlaylist, {
			autoplay:false,
			//description:description,
			jPlayer:{
				swfPath:'../js/jquery-jplayer' //You need to override the default swf path any time the directory structure changes
			}
		
		});

		$('.tracklist').slimScroll({
	        height: '250px',
	        size: '2px',
	        wheelStep :'1px'
	    });
	    
		$(".tags").click(function(e){
	        e.preventDefault();
	        var value = $(this).attr("name");
	        $.ajax({
	        	type: "POST",
				url: "getlist.php",
				data: "name="+value,
				datatype:'text',
				success: function(data){
					$('.music-block').empty();
				    $('.music-block').load('music-block.php');
				}
			});
	    });
		
	});
	
</script>
<div class="player-all">
<div class='myplayer'></div>
<?php 
include 'db.php';
$tag_query = "SELECT DISTINCT tag FROM songs";
$tag_result = mysql_query($tag_query);
while(($row = mysql_fetch_object($tag_result)) != false){
	echo '<button class="tags" name="'.$row->tag.'">';
	echo $row->tag;
	echo '</button>';
}
echo '<button class="tags" name="all">全部</button>'


?>
</div>
