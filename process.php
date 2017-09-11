<?php

    $function = $_POST['function'];
    // log is the information that will be sent to web page. 
    $log = array();
    
    switch($function) {
    
	     // return number of lines in chat.txt
    	 case('chat_getLines'):
           $fp = file('chat.txt');
           $log['lines'] = count($fp); 
           break;	
    	
       // return lines of text that are new
    	 case('chat_update'):
           $fp = file('chat.txt');
           $count = count($fp); 
           $temp = explode("\n", file_get_contents('chat.txt'));
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
         $fp = fopen('chat.txt', 'a');
		     if(($message) != "\n"){
		       fwrite($fp, $nickname . ": " . $message . "\r\n");
		     }
         fclose($fp);
       	 break;
         
       case('do_nothing'):
         break;
 
    }
    
    echo json_encode($log);

?>

             
             