<?php

   header("Content-Type:text/html;charset=utf-8"); 
   
   $link = @mysql_connect("localhost", "root", "king2000") or die("Can't connect mysql!!" . mysql_error());

   $db_selected = mysql_select_db('NCHU_Network_Management',$link);

   mysql_set_charset('utf8', $link);

   mysql_query("SET NAMES 'utf8'");

?>
