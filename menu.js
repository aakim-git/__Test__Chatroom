var tab = "public";

function check_private(room_name) {
    var correct;
    if(tab === "private"){
        var attempt = prompt("Enter password for this room:", "");
        $.ajax({
            type: "POST",
            url: "process.php",
            data: {
                'function': "check_private",
                'room_name': room_name,
                'attempt': attempt
            },
            async: false,
            dataType: "json",
            success: function (data) {
                if (data.pass == "true") { correct = true; }
                else { alert("wrong password :/"); correct = false; }
            },
            error: function (err) {
                console.log("check_private error");
                console.log(err.responseText);
            }
        });
        return correct;
    }

}

function enter_room(room_name, created) {
    if (room_name == file.split(".")[0] || created == false && check_private(room_name) == false) {
        return;
    }
    
       
    document.getElementById("chat_area").innerHTML = "";
    document.getElementById("room_name").textContent = room_name + " room";
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            'function': "enter_room",
            'old_room': file.split(".")[0],
            'new_room': room_name,
            'which': tab + "_rooms"
        },
        async: false,
        success: function () {
            file = room_name + ".txt"; // file from chat.js
            load_rooms(tab);
            chat_getLines();
            chat_load5();
        },
        error: function (err) {
            console.log("enter room error");
            console.log(err.responseText);
        }
    });

}

// calls php to access sql database. Then, appends the list to index.php. 
function load_rooms(which) {
    document.getElementById("rooms").innerHTML = "";
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            'function': 'load_rooms',
            'which': which
        },
        dataType: "json",
        success: function (data) {
            for (var i = 0 ; i < data.size ; i++) {
                (function (i) {
                    var temp = data.rooms[i];
                    var new_room = document.createElement('p');
                    new_room.addEventListener("click", function () { enter_room(temp, false); });
                    var info = data.rooms[i] + "   " + data.members[i] + " people";
                    new_room.appendChild(document.createTextNode(info));
                    document.getElementById('rooms').appendChild(new_room);
                })(i);
            }
            tab = which;
        },
        error: function (err) {
            console.log("load rooms error");
            console.log(err.responseText);
        }
    });
}

function create_room(name, password) {
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            'function': "create_room",
            'room_name': name,
            'password': password
        },

        success: function (data) {
            if (password == "") { tab = "public"; }
            else { tab = "private"; }
            enter_room(name, true);
        },
        error: function (err) {
            console.log("create rooms error");
            console.log(err.responseText);
        }
    });
}

function load_create() {
    document.getElementById("rooms").innerHTML = "";

    var form = document.createElement("input");
    form.setAttribute("id", "room_name");
    document.getElementById('rooms').appendChild(form);

    var form2 = document.createElement("input");
    form2.setAttribute("id", "password");
    document.getElementById('rooms').appendChild(form2);

    var button = document.createElement("button");
    button.textContent = "Create";
    button.setAttribute("id", "create");
    button.addEventListener("click", function () { create_room(form.value, form2.value); });
    document.getElementById('rooms').appendChild(button);
}

function exit_page() {
    console.log("exiting page");
    $.ajax({
        type: "POST",
        url: "process.php",
        data: {
            'function': "exit_page",
            'room_name': file.split(".")[0],
        },

        success: function () {
            console.log(file.split(".")[0]);
            console.log("updated");
        },
    });
}