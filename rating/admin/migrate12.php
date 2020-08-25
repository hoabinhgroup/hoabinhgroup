<?php
include 'php/config.php';

$versionToInstall=2;
$migrationPossible=true;

$error=false;
$link = @mysqli_connect($dbhost, $dbuser, $dbpass);

$dbexists=false;
if (!mysqli_connect_errno()) {
	$dbexists = @mysqli_select_db($link,"$db_name");
}



if(!$dbexists){
	header("Location: install.php"); exit();
}else{
	
	//check if already installed the last version
	$isinstalled=true;
	$sql = "SELECT value FROM $rating_set WHERE property ='version'";
	$result = $link->query($sql) or $isinstalled=false;
	
	
	
	if(!$isinstalled){ 
		//redirect to install page
		header("Location: install.php"); exit();
	}
	
	$versionexists=false;
	if($result && mysqli_num_rows($result)>0){
		$row = $result->fetch_assoc();
		$currentVersion=(float) $row['value'];
		$versionexists=true;	
	}else{
		$currentVersion=1;		
	}
	
	if($currentVersion>$versionToInstall){
		header("Location: install.php"); exit();
	}
	
}

//ready to start migration
include 'php/redirect.php';

include 'php/head.php';
echo '<div class="container">';



if($currentVersion==$versionToInstall){
	$error=true;
	?>
	
	<div class="alert alert-success">
		<h1>Congratulations!</h1>
		<p>The latest version is already installed.</p>
		<p>What to do now?</p>
		
		<p><a class="btn btn-large btn-primary" href="index.php">Enter admin area</a></p>
	 </div>
	
	<?php
}elseif($_POST['Install']){
	
	
	
	///START INSTALLATION
	
	if(mysqli_connect_errno()){
		$error=true;
	}		
	
	
	//if config is confirmed
	else{	
		//echo '<div class="alert alert-success"><i class="icon-ok"></i> database config is confirmed</div>';
		
		mysqli_select_db($link,$db_name);
		
		//ALTER TABLES HERE
		
		$query = 'ALTER TABLE '.$rating_sum.' ADD `property` varchar(50) NOT NULL';
		$result=$link->query($query) or $error=true;
		
		$method=1;
		$query = 'ALTER TABLE '.$banlist.' ALTER COLUMN `ip` varchar(50)';   	//SQL Server / MS Access
		$result=$link->query($query) or $method=2;
		
		if($method==2){
			$query = 'ALTER TABLE '.$banlist.' MODIFY COLUMN `ip` varchar(50)';	//My SQL / Oracle (prior version 10G)
			$result=$link->query($query) or $method=3;
		}
		
		if($method==3){
			$query = 'ALTER TABLE '.$banlist.' MODIFY `ip` varchar(50)';			//Oracle 10G and later
			$result=$link->query($query) or $error=true;
		}
		
		
		//alter other tables
		if($method==1){
			//SQL Server / MS Access:
			$query = 'ALTER TABLE '.$rating_det.' ALTER COLUMN `ip` varchar(50)';   	
			$result=$link->query($query) or $error=true;
			
			$query = 'ALTER TABLE '.$rating_det.' ALTER COLUMN `rate` decimal(8,5)';
			$result=$link->query($query) or $error=true;
			
			$query = 'ALTER TABLE '.$rating_sum.' ALTER COLUMN `mean` decimal(8,5)';
			$result=$link->query($query) or $error=true;
		}elseif($method==2){
			////My SQL / Oracle (prior version 10G):
			$query = 'ALTER TABLE '.$rating_det.' MODIFY COLUMN `ip` varchar(50)';   	
			$result=$link->query($query) or $error=true;
			
			$query = 'ALTER TABLE '.$rating_det.' MODIFY COLUMN `rate` decimal(8,5)';
			$result=$link->query($query) or $error=true;
			
			$query = 'ALTER TABLE '.$rating_sum.' MODIFY COLUMN `mean` decimal(8,5)';
			$result=$link->query($query) or $error=true;
		}elseif($method==3){
			////My SQL / Oracle (prior version 10G):
			$query = 'ALTER TABLE '.$rating_det.' MODIFY `ip` varchar(50)';   	
			$result=$link->query($query) or $error=true;
			
			$query = 'ALTER TABLE '.$rating_det.' MODIFY `rate` decimal(8,5)';
			$result=$link->query($query) or $error=true;
			
			$query = 'ALTER TABLE '.$rating_sum.' MODIFY `mean` decimal(8,5)';
			$result=$link->query($query) or $error=true;
		}
		
		//add version number
		if($versionexists) $query = "UPDATE ".$rating_set." SET value='2.0' WHERE property='version'";		
		else $query = "INSERT INTO ".$rating_set." (property, value) VALUES ('version','2.0')";	
		$result=$link->query($query) or $error=true;
		
		
	}
	
	
	
	
	
	
	if($error){
		echo '<div class="alert alert-error"><h1>Something went wrong</h1><p>'.mysqli_error($link).'</p>';
		echo 'What to do now?<ul><li>Make sure that you have permission to alter database tables</li><li>Or upgrade manually using "admin/php/MIGRATE.sql" file</li></ul></div>';
	}else{
	
	?>
	
	<div class="alert alert-success">
		<h1>Congratulations!</h1>
		<p>System installed and ready to use</p>What to do now? 		
		<p><a class="btn btn-large btn-primary" href="index.php">Enter admin area</a></p>
	 </div>
	
	<?php
	}
	
}

 
if(!isset($_POST['Install']) && !$error){

	
	
	///DISPLAY INSTALLATION PAGE
	
	
	
?>


	
		<h2 class="info">You are running version 1.x of rating system</h2> 
		<h3 class="info">Please follow the insructions to <b>migrate</b> to version 2</h3>
		
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
		<div class="alert alert-error">status: ERROR
		<p><?php echo mysqli_connect_error(); ?></p>
		<p>Please edit config.php and refresh this page</p>
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
			
		<h3>2. DB and tables names (to change - edit php/config.php)</h3>
		
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
						$migrationPossible=false;
					}else{
						$result = @$link->query("SHOW TABLES LIKE '".$tables[$i]."'");
						$table[$i] = @mysqli_num_rows($result) > 0;	
						if(!$table[$i]) $migrationPossible=false;
					}
				}
			}
		
			
			for($i=0;$i<count($tables);$i++){
				echo '<tr '.(($dbexists && $table[$i])?'class="info"':'class="error"').'><td>table</td><td>'.$tables[$i].'</td>';
				echo '<td>'.(($dbexists && $table[$i])?'exists and ready to migrate':'! does not exist ! Proceed <a href="install.php">installation</a> instead of migration').'</td></tr>';
			}
			?>
		
		</table>
		
		
		
		
		<?php
		if($migrationPossible){
		?>
		<form id="form-install" name="form-install" method="post" action="migrate12.php">		  		  
			  <input type="submit" class="btn btn-large btn-success btn-primary" name="Install" id="Install" value="MIGRATE"/>		
		</form>
		<?php
		}else{
			echo '<div class="alert alert-error">Some of the tables do not exist OR were renamed in new config.php. Proceed <a href="install.php">installation</a> instead of migration.</div>';
			
		}
	
	


	}

}



echo '</div>';
include 'php/footer.php';
echo '<script src="js/application.install.js"></script>';
include 'php/closehtml.php';
?>