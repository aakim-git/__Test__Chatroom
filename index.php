<html>
<body>
  <head>
  	<link rel = "stylesheet" type = "text/css" href = "style.css" />
    <title>Chat</title>

    <div id="page_wrap">
      <h2>THE BEST CHAT EVER \o/</h2>
	  <p id="room_name"></p>
      <p id="name_area"></p>

	  <div id = "chat">
        <div id="chat_area"></div>
        <form id="send_message_area">
            <textarea id="sendie" maxlength = '100' ></textarea>
        </form>
	  </div>
	  
	  <div id = "menu"> 
	    <div id = buttons>
		  <button id = "public"> Public </button>
		  <button id = "private"> Private </button>
		  <button id = "create_room"> Create Room </button>
		</div>
		<div id = "rooms"></div>
	  </div>
   </div>

   	<script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type = "text/javascript" src = "chat.js"></script>
   	<script type = "text/javascript" src = "menu.js"> </script>
	<script type = "text/javascript">
		enter_room("global", false);
		chat_getLines();
		document.getElementsByTagName("Body")[0].addEventListener("load", setInterval(function(){ chat_update(); }, 1000));
		document.getElementById("public").addEventListener("click", function(){ load_rooms("public"); });
		document.getElementById("private").addEventListener("click", function(){ load_rooms("private"); });
		document.getElementById("create_room").addEventListener("click", function(){ load_create(); });
		window.onbeforeunload = function(e){ exit_page(); return null; };


	    // ask for name
        var name = prompt("Enter your chat name:", "('_>')7");
    	if (!name || name === ' ' || name === "null") { name = "Guest"; }
    	document.getElementById("name_area").textContent = "You are: " + name;

    	// start chat
		document.getElementById("sendie").addEventListener("keyup", function(event){
		  if(event.keyCode == 13){
			var text = $(this).val();
		    $(this).val("");
			chat_send(text, name);
		  }
		});

    </script>

  </head>
</body>
</html>