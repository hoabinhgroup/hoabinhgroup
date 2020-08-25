<?php

// 1. change DB settings below
// 2. upload all files to your server
// 3. run install.php (by opening the page www.YouDomain.com/path/to/install.php)

//************DB settings*******************************************
$dbhost = 'localhost';		// for example 'www.YouDomain.com'
$dbuser = 'admin_tuan';			// for example 'DB_username'
$dbpass = 'V49OYnEv';				// for example 'DB_password'


//************DB and tables names **********************************
// following DB will be created during installation (if not already exist)
$db_name = 'admin_hbg2017';				//DB name


// following tables inside DB will be recreated during installation
$table_prefix='rating_';			//prefix for the tables to create

$rating_set = $table_prefix.'settings'; 	//ratings settings table
$rating_sum = $table_prefix.'summary';		//ratings summary table
$rating_det = $table_prefix.'details'; 		//ratings details table
$banlist 	= $table_prefix.'banlist'; 		//banlist table

$demoMode=false;
?>