<?php

    $function = (isset($_POST['function']) ? $_POST['function'] : null);
    // log is the information that will be sent to web page. 
    $log = array();
    
    
    switch($function) {
    
	     // return number of lines in chat.txt
    	 case('chat_getLines'):
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
             
    }
    
    echo json_encode($log);
    
?>

             
             