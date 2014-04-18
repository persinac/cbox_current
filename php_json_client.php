<!---------------------------------------------------------------------------
Example client script for JQUERY:AJAX -> PHP:MYSQL example
---------------------------------------------------------------------------->

<html>
  <head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <link href="dist/css/php_json.css" rel="stylesheet" type="text/css">
  </head>
  <body>

  <!-------------------------------------------------------------------------
  1) Create some html content that can be accessed by jquery
  -------------------------------------------------------------------------->
  <h2> Client example </h2>
  <h3>Output: </h3>
  <div id="cft_test" >this element will be accessed by jquery and this text replaced</div>
  <div id="navbar_sub"> 
      <ul id="navbar_sub_ul"> 
        <li id="cft" ><a href="#" >CROSSFIT</a></li> 
        <li id="oly" ><a href="#" >OLYMPIC</a></li> 
        <li id="pwr" ><a href="#" >POWERLIFTING</a></li> 
        <li id="wod" ><a href="#" >WODs</a></li> 
        <li id="mis" ><a href="#" >MISC</a></li> 
      </ul> 
	</div>
<div class="data_container"> <table class="maxes">
		<thead>
            <tr>
                <th>Movement ID</th>
                <th>Weight</th>
            </tr>
 		</thead> 
        <tbody class="data">
        </tbody>
      </table>
 </div>
        
  <script id="source" language="javascript" type="text/javascript">

	$(document).ready(function() {
		//$("#navbar_sub_ul li").click(function(event) {
		$("#navbar_sub_ul li").click(function() {
			var id = jQuery(this).attr("id");
			//alert(id);
			getData(id);
    	});	
	});

  function getData(movement_id) 
  {
    //-----------------------------------------------------------------------
    // 2) Send a http request with AJAX http://api.jquery.com/jQuery.ajax/
    //-----------------------------------------------------------------------
    $.ajax({ 
	  type:"POST",                                     
      url: "php_json_test.php",                  //the script to call to get data          
      data: { dataString: movement_id },                 //you can insert url argumnets here to pass to api.php
      dataType: "json",                //data format      
      success: function(response)          //on recieve of reply
      {
		  //alert(movement_id);
		loadData(response);
      } 
    });
  } 
  
  function loadData(data) //
  {
	  alert("Data: " + data);
	  var html = data;
        var m = "m_"; 
		var w = "w_";             //get id
        var vname;           //get name
		for(var i = 0; i < data.length; i++) {
                    html += "<tr id="+ m+""+ i +"><td>"+
						data[i].mvmnt_id+"</td><td>"+data[i].weight+"</td></tr>";
        }
		alert("HTML: " +html);
        //--------------------------------------------------------------------
        // 3) Update html content
        //--------------------------------------------------------------------
        $('.data').html(html); //Set output element html
		$('#cft_test').html(html);
        //recommend reading up on jquery selectors they are awesome 
        // http://api.jquery.com/category/selectors/
  }

  </script>
  </body>
</html>