<?php
define("SITE_URL", 'http://localhost/electric/');
define("DB", "electric");
define("DBUSER", "root");
define("DBPWD", "");  
define("SALT", "1235786fgg");
define('ROOTPATH', __DIR__);
define('ADMIN_EMAIL','algonquinelectrical@gmail.com');  

$conn = mysql_connect("localhost",DBUSER,DBPWD) or die("could not connect to server");
mysql_select_db(DB,$conn) or die("could not connect to database");



?>