<?php

    $function = (isset($_POST['function']) ? $_POST['function'] : null);
    // log is the information that will be sent to web page. 
    $log = array();
    
    
    switch($function) {
	     // return number of lines in chat.txt
    	 case('chat_getLines'):
           if( !file_exists($_POST['file'] )){
             $temp = fopen($_POST['file'], 'w');
             fclose($temp);
           }
           $fp = file($_POST['file']);
           $log['lines'] = count($fp); 
           break;	
    	
       // return lines of text that are new
    	 case('chat_update'):
           $fp = file($_POST['file']);
           $count = count($fp); 
           $temp = explode("\n", file_get_contents($_POST['file']));
           $text = array();
           $j = 0;
           for($i = $_POST['previous'] ; $i < $count ; $i++){
             $text[$j] = $temp[$i];
             $j++;
           }
        	 $log['text'] = $text; 
           break;
    	 
       // insert new message to chat.txt 
    	 case('chat_send'):
		     $nickname = $_POST['nickname'];
		     $message = $_POST['message'];
         $fp = fopen($_POST['file'], 'a');
		     if(($message) != "\n"){
		       fwrite($fp, $nickname . ": " . $message . "\r\n");
		     }
         fclose($fp);
       	 break;
         
       case('chat_load5'):
           $fp = file($_POST['file']);
           $count = count($fp);
           $temp = explode("\n", file_get_contents($_POST['file']));
           $text = array();
           $j = 0;
           //$i = $count-5;
           //if($i < 0){ $i = 0; }
           for($i=0 ; $i < $count ; $i++){
             $text[$j] = $temp[$i];
             $j++;
           }
        	 $log['text'] = $text; 
           $log['num_lines'] = $j;
           break;
         
       case('load_rooms'):
         $conn = mysqli_connect("localhost", "root", "", "thingroom");
         if($_POST['which'] == "public"){ $sql = "SELECT name, members FROM public_rooms"; }
         else{ $sql = "SELECT name, members FROM private_rooms"; }
         $members = array();
         $rooms = array();
         $size = 0;
         
         $result = mysqli_query($conn, $sql);
         if(mysqli_num_rows($result) > 0){
           for($i=0 ; $row = mysqli_fetch_assoc($result) ; $i++){
             $members[$i] = $row["members"];
             $rooms[$i] = $row["name"];
             $size++;
           }
         }
         
         $log['members'] = $members; 
         $log['rooms'] = $rooms;
         $log['size'] = $size;
         $conn -> close();
         break;
    
    case('create_room'):
      $rm_name = $_POST['room_name'];
      $psword = $_POST['password'];
      $conn = mysqli_connect("localhost", "root", "", "thingroom");
      if($_POST['password'] == ""){ $sql = "INSERT INTO public_rooms (name, members) VALUES ('$rm_name', 5)"; }
      else{ $sql = "INSERT INTO private_rooms (name, members, password) VALUES ( '$rm_name', 5, '$psword')"; }
      $conn -> query($sql);
      $conn -> close();
      break;	
             
    
    
   case('enter_room'):
     $conn = mysqli_connect("localhost", "root", "", "thingroom");
     $which = $_POST['which'];
     $old_room = $_POST['old_room'];
     $new_room = $_POST['new_room'];
     
     $sql = "UPDATE $which SET members = members + 1 WHERE name = '$new_room'"; 
     $conn -> query($sql);
     
     if($old_room!= ""){
       $sql = "SELECT name FROM $which WHERE name = '$old_room'";
       $result = mysqli_query($conn, $sql);
       if(mysqli_num_rows($result) > 0){ $sql = "UPDATE $which SET members = members - 1 WHERE name = '$old_room'"; }
       else{ 
         if($which == "public_rooms"){ $which = "private_rooms"; }
         else{ $which = "public_rooms"; }
         $sql = "UPDATE $which SET members = members - 1 WHERE name = '$old_room'";
       } 
     $conn -> query($sql);
     } 

     
     $conn -> close();
     break;
     
   case('check_private'):
     $room = $_POST['room_name'];
     $conn = mysqli_connect("localhost", "root", "", "thingroom");
     
     $sql = "SELECT name, password FROM private_rooms WHERE name = '$room'";
     $result = mysqli_query($conn, $sql);
     if(mysqli_num_rows($result) > 0){
       $row = mysqli_fetch_assoc($result);
       if($_POST['attempt'] == $row["password"]){ $log['pass'] = "true"; }
       else{ $log['pass'] = "false"; }
     }  // end if
     
     $conn -> close();
     break;

    case('exit_page'):
      $room = $_POST['room_name'];
      $conn = mysqli_connect("localhost", "root", "", "thingroom");
      
      $sql = "SELECT name FROM public_rooms WHERE name = '$room'";
      $result = mysqli_query($conn, $sql);
      
      if(mysqli_num_rows($result) >= 0){ $sql = "UPDATE public_rooms SET members = members - 1 WHERE name = '$room'"; }
      else{$sql = "UPDATE public_rooms SET members = members - 1 WHERE name = '$room'"; }
      $conn -> query($sql);  
      
      $conn -> close();
      break;
      
    }
    echo json_encode($log);
    
?>

             
             