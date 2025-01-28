<?php

if($_SERVER['SERVER_NAME'] == "localhost")
{

    //local server
define("ROOT","http://localhost/DMDS/public");

define("DBDRIVER", "mysql");
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "" );
define("DBNAME", "music_website_db");
}else{

    //online server
define("ROOT","http://www.mywebsite.com");
}

