<?php
	//connect mysql
	$hostname="localhost";
	$user="root";
	$passwd="20092137";
	$db="coma-project";

	$conn=msyql_connect($hostname,$user,$passwd) or die(mysql_error());
	mysql_select_db($db) or die(mysql_error());



?>