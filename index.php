<html>

<head>
  <body>
	<!-- <body onload="setInterval('chat_update()', 1000)"> -->
    <title>Chat</title>
    <div id="page_wrap">
        <h2>jQuery/PHP Chat</h2>
        <p id="name_area">NAME AREA</p>
        <div id="chat_wrap"><div id="chat_area"></div></div>
        <form id="send_message_area">
            <p>Your message: </p>
            <textarea id="sendie" maxlength = '100' ></textarea>
        </form>
    </div>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="chat.js"></script>
	<script type="text/javascript">
	    do_nothing();
		chat_getLines();

	    // ask for name
        var name = prompt("Enter your chat name:", "('_>')7");
    	if (!name || name === ' ' || name === "null") { name = "Guest"; }
    	document.getElementById("name_area").textContent = "You are: " + name;

    	// start chat
		document.getElementById("sendie").addEventListener("keyup", function(event){
		  if(event.keyCode == 13){
		    console.log("ENTER pressed");
			var text = $(this).val();
		    $(this).val("");
			chat_send(text, name);
		  }
		});

    </script>
  </body>
</head>


</html>