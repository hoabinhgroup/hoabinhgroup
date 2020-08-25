<?php
include 'php/config.php';

$versionToInstall=2;

$error=false;
$link = @mysqli_connect($dbhost, $dbuser, $dbpass);

$dbexists=false;
if (!mysqli_connect_errno()) {
	$dbexists = @mysqli_select_db($link,"$db_name");
}



if($dbexists){
	
	//check if already installed
	$isinstalled=true;
	$sql = "SELECT value FROM $rating_set WHERE property ='version'";
	$result = $link->query($sql) or $isinstalled=false;
	
	
	
	if($isinstalled){ 
		//redirect to login page if necessary
		include 'php/redirect.php';
	}
	
	
	if($result && mysqli_num_rows($result)>0){
		$row = $result->fetch_assoc();
		$currentVersion=(float) $row['value'];		
	}elseif(!$isinstalled){
		$currentVersion=$versionToInstall;
	}else{
		$currentVersion=1;		
	}
	
	if($currentVersion<$versionToInstall){ header("Location: migrate".$currentVersion.$versionToInstall.".php"); exit();}
	

}
 
 
if(session_id() == "") session_start();
include 'php/head.php';
echo '<div class="container">';

if($_POST['Install']){ 
	
	$checkInput='';
	
		
	$pass = trim($_POST['inputPassword']);
	$pass2 = trim($_POST['inputPasswordConfirm']);
	$user = trim($_POST['inputUsername']);
	
	if($user == '') {
    	$checkInput = '<div class="alert alert-error"><i class="icon-user"></i> Attention! You must enter a username.</div>';
    }
	if($pass == '') {
    	$checkInput .= '<div class="alert alert-error"><i class="icon-pencil"></i> Attention! You must enter the password.</div>';
    }else if($pass2 == '') {
    	$checkInput .= '<div class="alert alert-error"><i class="icon-warning-sign"></i> Attention! You must confirm password.</div>';
    }else if($pass != $pass2) {
    	$checkInput .=  '<div class="alert alert-error"><i class="icon-warning-sign"></i> Attention! Your passwords did not match.</div>';
	}
	
	if($checkInput==''){
	
		
		//TO DO check if DB exists
	
		
		///START INSTALLATION
		
		if(mysqli_connect_errno()){
			$error=true;
		}		
		
		
		//if config is confirmed
		else{	
			//echo '<div class="alert alert-success"><i class="icon-ok"></i> database config is confirmed</div>';
			
			
			////////CREATE DATABASE//////////////
			if(!$dbexists){
				$query  = "CREATE DATABASE IF NOT EXISTS $db_name";
				$result = $link->query($query) or $error=true;
			}
			
			
			if(!$error){
				
				mysqli_select_db($link,"$db_name");
				
				//echo '<div class="alert alert-success"><i class="icon-ok"></i> database <b>'.$db_name.'</b> is ready</div>';
						
				//create tables				
				
					//echo '<div class="alert alert-success"><i class="icon-ok"></i> database <b>'.$db_name.'</b> is selected</div>';
				
					////////DELETE EXISTING TABLES/////////////////				
					$query =  'DROP TABLE IF EXISTS '.$rating_sum;
					$result=$link->query($query) or $error=true;
					if(!$result){					
						//echo '<div class="alert alert-error"><h1>Error</h1><p>Can not empty/delete existing table '.$rating_sum.'</p>';
						$error=true;
					}
					
					$query =  'DROP TABLE IF EXISTS '.$rating_det;
					$result=$link->query($query) or $error=true;
					if(!$result){					
						//echo '<div class="alert alert-error"><h1>Error</h1><p>Can not empty/delete existing table '.$rating_det.'</p>';
						$error=true;
					}
					
					$query =  'DROP TABLE IF EXISTS '.$banlist;
					$result=$link->query($query) or $error=true;
					if(!$result){					
						//echo '<div class="alert alert-error"><h1>Error</h1><p>Can not empty/delete existing table '.$banlist.'</p>';
						$error=true;
					}
					
					$query =  'DROP TABLE IF EXISTS '.$rating_set;
					$result=$link->query($query) or $error=true;
					if(!$result){					
						//echo '<div class="alert alert-error"><h1>Error</h1><p>Can not empty/delete existing table '.$rating_set.'</p>';
						$error=true;
					}
	
					
					$query =  'CREATE TABLE IF NOT EXISTS `'.$rating_sum.'`('
							. ' `n` INT(11) NOT NULL AUTO_INCREMENT,'						
							. ' `id` VARCHAR(50) DEFAULT NULL,'
							. ' `property` varchar(50) DEFAULT \'\','
							. ' `mean` decimal(8,5) NOT NULL DEFAULT \'0.00000\','
							. ' `votes` INT(11) NOT NULL default \'0\','
							. ' `title` VARCHAR(250),'
							. ' `voteperiod` INT(40) NOT NULL default \'2678400\','
							. ' `link` varchar(450),'
							. ' `validtill` BIGINT(50) NOT NULL default \'-1\','
							. ' `editable` tinyint(1) NOT NULL DEFAULT \'1\','
							. ' `time` BIGINT(50) NOT NULL,'
							. ' PRIMARY KEY  (`n`)'
							. ' );';
					$result=$link->query($query) or $error=true;
					//if($result) echo '<div class="alert alert-success"><i class="icon-ok"></i> table <b>'.$rating_sum.'</b> is ready</div>';
			

					$query =  'CREATE TABLE IF NOT EXISTS `'.$rating_det.'`('
							. ' `n` INT NOT NULL AUTO_INCREMENT, '
							. ' `id` INT(11) NOT NULL,'						
							. ' `rate` decimal(8,5) NOT NULL,'
							. ' `ip` varchar(50) NOT NULL,'
							. ' `time` BIGINT(50) NOT NULL,'
							. ' PRIMARY KEY(`n`)'
							. ' );';
					$result=$link->query($query) or $error=true;
					//if($result) echo '<div class="alert alert-success"><i class="icon-ok"></i> table <b>'.$rating_det.'</b> is ready</div>';

					
					$query =  'CREATE TABLE IF NOT EXISTS `'.$banlist.'`('
							. ' `n` INT NOT NULL AUTO_INCREMENT, '
							. ' `ip` varchar(50) NOT NULL,'
							. ' `time` BIGINT(50) NOT NULL,'
							. ' PRIMARY KEY(`n`)'
							. ' );';
					$result=$link->query($query) or $error=true;
					//if($result) echo '<div class="alert alert-success"><i class="icon-ok"></i> table <b>'.$banlist.'</b> is ready</div>';
					

					$query =  'CREATE TABLE IF NOT EXISTS `'.$rating_set.'`('
							. ' `n` INT NOT NULL AUTO_INCREMENT, '
							. ' `property` varchar(50) NOT NULL,'											
							. ' `value` varchar(50) NOT NULL,'											
							. ' PRIMARY KEY(`n`)'
							. ' );';
					$result=$link->query($query) or $error=true;
					//if($result) echo '<div class="alert alert-success"><i class="icon-ok"></i> table <b>'.$banlist.'</b> is ready</div>';
			
			
					
					$pass = trim(mysqli_real_escape_string($link,$_POST['inputPassword']));
					$pass2 = trim(mysqli_real_escape_string($link,$_POST['inputPasswordConfirm']));
					$user = trim(mysqli_real_escape_string($link,$_POST['inputUsername']));
			
					//insert initial data
					$user_pass=md5($user.'_'.$pass);
					$query = "INSERT INTO $rating_set (property, value) VALUES ('user','$user_pass'),('voteperiod','2678400'),('minvotes','10'),('ip','1'),('cookie','1'),('version','2.0')"; //insert initial settings
					$result=$link->query($query) or $error=true;
					
					$time=time()-100;
					
					$query = "INSERT INTO $rating_det (`n`, `id`, `rate`, `ip`, `time`) VALUES (1, 1, 90.00000, '127.0.0.1', $time),(2, 3, 25.83000, '127.0.0.1', ".($time+50)."),(3, 4, 90.00000, '999.0.0.9', ".($time+100).");";
					$result=$link->query($query) or $error=true;
				
					$query = "INSERT INTO $rating_sum (`n`, `id`, `property`, `mean`, `votes`, `title`, `voteperiod`, `link`, `validtill`, `editable`, `time`) VALUES (1, 'itemid', 'cild 1', 90.00000, 1, 'cild 1', 2678400, 'http%3A%2F%2Fcodecanyon.net%2Fitem%2Fskinnable-rating-system-admin-area%2F3853477%3Fref%3DSandi', -1, 1, $time),(2, 'itemid', '', 57.91500, 2, 'item with children example', 2678400, 'http%3A%2F%2Fcodecanyon.net%2Fitem%2Fskinnable-rating-system-admin-area%2F3853477%3Fref%3DSandi', -1, 1, ".($time+50)."),(3, 'itemid', 'cild 2', 25.83000, 1, 'cild 2', 2678400, 'http%3A%2F%2Fcodecanyon.net%2Fitem%2Fskinnable-rating-system-admin-area%2F3853477%3Fref%3DSandi', -1, 1, ".($time+50)."),(4, 'itemid2', '', 90.00000, 1, 'item title example', 2678400 ,'http%3A%2F%2Fcodecanyon.net%2Fitem%2Fskinnable-rating-system-admin-area%2F3853477%3Fref%3DSandi', -1, 1, ".($time+100).");";
					$result=$link->query($query) or $error=true;
					
					
					$query = "INSERT INTO $banlist (`n`, `ip`, `time`) VALUES (1, '999.0.0.9', ".($time+200).");";
					$result=$link->query($query) or $error=true;
				
					if(!$error){						 
						$_SESSION['username'] = $user_pass;
					}
			}
			
		}
		
	
	}
	
	
	
	if($error){
		echo '<div class="alert alert-error"><h1>Something went wrong</h1><p>'.mysqli_error($link).'</p>';
		echo 'What to do now?<ul><li>Make sure that you have permission to create databases/tables</li><li>Go back to <a href="install.php">preveous page</a> and double check your config</li><li>or install manually using "admin/php/DB.sql" file</li></ul></div>';
	}else if($checkInput==''){
	
	?>
	
	<div class="alert alert-success">
		<h1>Congratulations!</h1>
		<p>System installed and ready to use</p>
		<p>What to do now?</p>
		
		<p><a class="btn btn-large btn-primary" href="index.php">Enter admin area</a></p>
	 </div>
	
	<?php
	}
	
}

 
if((!isset($_POST['Install']) || $checkInput!='') && !$error){

	
	
	///DISPLAY INSTALLATION PAGE
	
	
	
?>


	
		<h2 class="info">Please follow the insructions to install the rating system</h2>
		
		<h3>1. DB settings (to change - edit php/config.php)</h3>
		<table class="table table-condensed table-hover">
			<tr>				
				<td style="width:150px">Database host:</td>
				<td><?php echo $dbhost?></td>			
			</tr>
			<tr>				
				<td>Database user:</td>
				<td><?php echo $dbuser?></td>		
			</tr>
			<tr>				
				<td>Database pass:</td>
				<td><?php echo $dbpass?></td>		
			</tr>
		</table>

<?php
	
	
	if(mysqli_connect_errno()){
		$error=true;
		
?>				
		<div class="alert alert-error"><p>status: ERROR</p>
		<p><?php echo mysqli_connect_error(); ?></p>
		<p>Please edit "admin/php/config.php" file in any text editor and refresh this page</p>
		</div>
		<p><a class="btn btn-large disabled" href="#">Install</a></p>
<?php	
		
	}
	
	//if config is confirmed
	else{	
		echo '<div class="alert alert-success">status: <i class="icon-ok"></i> connection to server confirmed.</div>';
	}
	
	
	if(!$error){
		

		
?>
			
		<h3>2. DB and tables names (to change - edit "admin/php/config.php" file)</h3>
		
		<table class="table table-condensed table-hover">
			<tr>	
				<td>DB name</td>
				<td><?php echo $db_name;?></td>
				<td><?php echo $dbexists?'exists and ready to use':'will be created'; ?></td>
			</tr>
			
		
			<?php
			//check if tables exist
			$tables=array($rating_sum, $rating_det, $banlist, $rating_set);	
			$table=array();	
				
			if($dbexists){
				for($i=0;$i<count($tables);$i++){
					if(!$tables[$i]){
						$table[$i]=false;
						$tables[$i] = 'UNKNOWN (check config!!!)';
					}else{
						$result = @$link->query("SHOW TABLES LIKE '".$tables[$i]."'");
						$table[$i] = @mysqli_num_rows($result) > 0;						
					}
				}
			}
		
			
			for($i=0;$i<count($tables);$i++){
				echo '<tr '.(($dbexists && $table[$i])?'class="info"':'').'><td>table</td><td>'.$tables[$i].'</td>';
				echo '<td>'.(($dbexists && $table[$i])?'already exists. It will be empted':'will be created').'</td></tr>';
			}
			?>
		
		</table>
		
		
		
		<h3>3. Create user to login to administration area</h3>
		
		<?php
			if($checkInput!='') echo $checkInput;
		
		?>
		
		<form class="form-horizontal" id="form-install" name="form-install" method="post" action="install.php">
		  <div class="control-group">
			 <label class="control-label" for="inputUsername">Username</label>
			<div class="controls">
			  <input type="text" id="inputUsername" name="inputUsername" placeholder="Username" value="<?php echo ($user=='')?'':$user; ?>">
			</div>
		  </div>
		  <div class="control-group">
			 <label class="control-label" for="inputPassword">Password</label>
			<div class="controls">
			  <input type="password" id="inputPassword" name="inputPassword" placeholder="Password" value="<?php echo ($pass=='')?'':$pass; ?>">
			</div>
		  </div> 
		  <div class="control-group">
			 <label class="control-label" for="inputPasswordConfirm">Confirm password</label>
			<div class="controls">
			  <input type="password" id="inputPasswordConfirm" name="inputPasswordConfirm" placeholder="Confirm Password" value="<?php echo ($pass2=='')?'':$pass2; ?>">
			</div>
		  </div>
		  <div class="control-group">
			<div class="controls">			  
			  <input type="submit" class="btn btn-large btn-success btn-primary" name="Install" id="Install" value="INSTALL"/>
			</div>
		  </div>
		</form>
		
		
	
	
<?php

	}

}



echo '</div>';
include 'php/footer.php';
echo '<script src="js/application.install.js"></script>';
include 'php/closehtml.php';
?>