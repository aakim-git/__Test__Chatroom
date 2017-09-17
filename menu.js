var tab = "public";

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
            console.log("load success");
            for (var i = 0 ; i < data.size ; i++) {
                var new_room = document.createElement('p');
                var info = data.rooms[i] + "     " + data.members[i] + " people";
                new_room.appendChild(document.createTextNode(info));
                document.getElementById('rooms').appendChild(new_room);
            }
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
            load_rooms("public");
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