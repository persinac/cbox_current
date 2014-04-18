<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="dist/css/user_wod_page.css" rel="stylesheet">
<link href="dist/css/bootstrap.min.css" rel="stylesheet"> 
<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
<title>Untitled Document</title>
</head>

<body>

	<!-- set up the modal to start hidden and fade in and out -->
<div id="myModal" class="modal" style="display:none; ">
    <!-- dialog contents -->
    <div class="modal-body">Hello world!</div>
    <!-- dialog buttons -->
    <div class="modal-footer"><a href="#" class="btn" data-dismiss="modal">Close</a></div>
</div>
	
	
	<div class="btn-group" id="level_selector">
	  <button type="button" class="btn btn-default" id="lvl_rx">RX</button>
	  <button type="button" class="btn btn-default" id="lvl_inter">Intermediate</button>
	  <button type="button" class="btn btn-default" id="lvl_nov">Novice</button>
	</div>
	
	<div id="button_holder">
		<p>
			<button class="btn btn-primary" name="wod_but" id="wod_but" type="button">Add WOD</button>
			</p><p>
			<a data-toggle="modal" href="#myModal" class="btn btn-default" name="wod_custom" id="wod_custom">Add Scaled WOD</a>
			</p><p>
			<button class="btn btn-primary" name="strength" id="strength" type="button">Add Strength</button>
			</p><p>
			<button class="btn btn-default" name="post_wod" id="post_wod" type="button">Add Post WOD</button>
			</p><p>
			<button class="btn btn-primary" name="rest" id="rest" type="button">Rest Day</button>
		</p>
	</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script id="source" language="javascript" type="text/javascript">
$(function(){
	$("button#wod_but").click(function() {
		alert("Clicked Add WOD");
		$('#myModal').modal('show');
	});
});

var today = new Date();
function getCurrentDate()
{
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	
	if(dd<10) {
		dd='0'+dd;
	} 
	
	if(mm<10) {
		mm='0'+mm;
	} 
	
	today = yyyy+'-'+mm+'-'+dd;
	//alert("today");
}

</script>

</body>
</html>