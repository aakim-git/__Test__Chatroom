var file = "chat.txt";
var cur_lines;

//sets cur_lines to the current number of lines. 
function chat_getLines() {
  $.ajax({
    type: "POST",
    url: "process.php",
    data: {
      'function': 'chat_getLines',
      'file': file
    },
    async: false,
    dataType: "json",

    success: function (data) {
        cur_lines = data.lines;
    },
    error: function (err) {
        console.log("chat lines error");
        console.log(err.responseText);
    }
  });
}

// checks for changes in chat.txt
// if change, append the new lines into message board. 
function chat_update() {
    var temp = cur_lines;
    chat_getLines();
    if (temp != cur_lines) {
        $.ajax({
            type: "POST",
            url: "process.php",
            data: {
                'function': 'chat_update',
                'previous': temp,
                'file': file
            },
            dataType: "json",
            success: function (data) {
                if (data.text) {
                    for (var i = 0; i < cur_lines - temp - 1; i++) {
                        var new_message = document.createElement('p');
                        new_message.appendChild(document.createTextNode(data.text[i]));
                        document.getElementById('chat_area').appendChild(new_message);
                    }
                }
            },
            error: function (err) {
                console.log("chat update error");
                console.log(err.responseText);
            }
        });
    }
  
}

// append message into chat.txt      
// append message into message board. 
function chat_send(message, nickname){
    // maybe after a certain amount of time, you can start deleting old messages. 
     $.ajax({
		   type: "POST",
		   url: "process.php",
		   data: {  
		        'function': 'chat_send',
				'message': message,
				'nickname': nickname,
				'file': file
				 },
		   success: function () {
		       chat_update();
		   },
           error: function(err){
             console.log(err.responseText);
           }
	});
}