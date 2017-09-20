var file = "";
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
                    // CHECK IF I=I+2 WORKS IN NETWORKED ENVIRONMENT
                    for (var i = 0; i < cur_lines - temp - 1; i=i+2) {
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

function chat_load5() {
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            'function': 'chat_load5',
            'file': file
        },
        dataType: "json",
        success: function (data) {
            var i = data.num_lines - 10;
            if (i < 1) { i = 0; }
            for ( i ; i < data.num_lines; i=i+2) {
                console.log(data.text[i]);
                var new_message = document.createElement('p');
                new_message.appendChild(document.createTextNode(data.text[i]));
                document.getElementById('chat_area').appendChild(new_message); 

            }
        },
        error: function (err) {
            console.log(err.responseText);
        }
    });
}