<?php
include 'php/redirect.php';

//page related styles
$style='
		body {
		padding-top: 60px;
		padding-bottom: 40px;
		}

		';
$showNavigation=true;
$pageIndex=2;
include 'php/head.php';

@include("php/functions.php");

//get default values
$stns=getSettings();
$settings=array();
while($row = mysqli_fetch_array($stns, MYSQLI_ASSOC)) {		
	$settings[$row['property']]=$row['value'];		
}

$dtobj=secondsToObj($settings['voteperiod']);
$dt=$dtobj[0];
$dts=$dtobj[1];

$isip=$settings['ip'];
$iscookie=$settings['cookie'];
$topvalue=$settings['minvotes'];
////////////////////////////////////////////


$errorCode=0;
$successCode=0;

if(isset($_POST['user'])) {
	
	$username = trim(mysqli_real_escape_string($link,$_POST['inputUsername'])); //addslashes
	$password = trim(mysqli_real_escape_string($link,$_POST['inputPassword'])); 
	$password2 = trim(mysqli_real_escape_string($link,$_POST['inputPassword2'])); 

	if (empty($username)) $errorCode=1;
	else if(empty($password)) $errorCode=2;
	else if(empty($password2)) $errorCode=3;
	else if($password!=$password2) $errorCode=4;
	else { 
	
		if($demoMode==true) $errorCode=9;
		else{
			$user_pass=md5($username.'_'.$password);

			//change DB
			$err=setSettings('user',$user_pass);
			if($err!=1){
				$errorCode=5;				
			}
			else{
				$password='';
				$password2='';
				$username='';
				$successCode=1;
			}
		}
	}
}
else if(isset($_POST['voteperiod'])) {	
	if($demoMode==true) $errorCode=19;
	else{
	
		$dt0 = trim(mysqli_real_escape_string($link,$_POST['dt'])); 
		$dts = trim(mysqli_real_escape_string($link,$_POST['dts'])); 
		
		$dt=floatval($dt0);
		if($dt<0) $dt=-$dt;
		
		if($dt>0){
			$dttotal=$dts=='s'?$dt:($dts=='m'?$dt*60:($dts=='h'?$dt*60*60:($dts=='d'?$dt*60*60*24:(-1))));
			if($dttotal==-1){
				$dts='s'; $dttotal=$dt;
			}		
		}else{
			if(ord($dt0)==48){
				$dttotal=0;
				$dts='s'; $dt=0;
			}else{		
				$dttotal=-1;
				$dt='&#8734;';
				$dts='';
			}
		}
		
		
		//change DB
		
		//$dttotal seconds
		$err=setSettings('voteperiod',$dttotal);
		
		if($err!=1){
			$errorCode=15;			
		}else{
			$successCode=2;			
		}
	}

}
else if(isset($_POST['ipcookie'])) {	
	if($demoMode==true) $errorCode=29;
	else{
		$isip = $_POST['isip']; 
		$iscookie = $_POST['iscookie']; 
		
		$isip=(ord($isip)==0)?0:1;
		$iscookie=(ord($iscookie)==0)?0:1;
		
		//change DB
		$err=setSettings('ip',$isip);
		$err=setSettings('cookie',$iscookie);
		
		
		if($err!=1){
			$errorCode=25;		
		}else{
			$successCode=3;
		}
	}
}
else if(isset($_POST['topflop'])) {
	if($demoMode==true) $errorCode=39;
	else{
		$topvalue = trim(mysqli_real_escape_string($link,$_POST['topvalue'])); 
		$topvalue=floatval($topvalue);	
		if($topvalue<0) $topvalue=-$topvalue;
		
		//change DB
		
		$err=setSettings('minvotes',$topvalue);
		
		if($err!=1){
			$errorCode=35;		
		}else{
			$successCode=4;
		}
	}
}else if(isset($_POST['uninstall'])) {
	
	$username = trim(mysqli_real_escape_string($link,$_POST['inputUsernameuninstall'])); //addslashes
	$password = trim(mysqli_real_escape_string($link,$_POST['inputPassworduninstall'])); 

	if (empty($username)) $errorCode=51;
	else if(empty($password)) $errorCode=52;
	else { 
	
		if($demoMode==true) $errorCode=59;
		else{
			$user_pass=md5($username.'_'.$password);

			//change DB
			$errorCode=deleteTables($user_pass);
			if($errorCode==-1){
				$showNavigation=false;
				//redirect
				include('install.php'); 
				exit();
			}
		}
	}
}



?>			
	
	
<div class="container">
	<div>
		
		
		
		<h3>Change admin username/password</h3>
		
		<?php
		if($errorCode>0 && $errorCode<10){
			echo '<div class="alert alert-error">';
			switch ($errorCode) {
				case 1:	echo "Username cannot be empty";break;
				case 2: echo "Password cannot be empty";break;
				case 3:	echo "Please confirm password";break;
				case 4:	echo "Passwords are different, please try again";break;
				case 5:	echo "<h1>Something went wrong</h1>".$err;break;
				case 9:	echo "This operation is disabled in DEMO".$err;break;
			}			
			echo '</div>';
		}
		
		if($successCode==1) echo '<div class="alert alert-success">Username/password successfully changed</div>';
		?>	
		
		
		<form class="" method="POST" action="settings.php">
			<div class="offset50 pull-left control-group <?php if($errorCode==1) echo 'error';?>">
				<label class="control-label" for="inputUsername">Username</label>
				<div class="controls">
					<input type="text" class="span2" id="inputUsername" name="inputUsername" placeholder="Username" value="<?php echo !isset($username)?'':$username; ?>">
				</div>
			</div>
			<div class="offset50 pull-left control-group <?php if($errorCode==2) echo 'error';?>">				
				<label class="control-label" for="inputPassword">Password</label>
				<div class="controls">
					<input type="password" class="span2" id="inputPassword" name="inputPassword" placeholder="Password" value="<?php echo !isset($password)?'':$password; ?>">
				</div>
			</div>
			<div class="offset50 pull-left control-group <?php if($errorCode==3 || $errorCode==4) echo 'error';?>">				
				<label class="control-label" for="inputPassword2">Confirm password</label>
				<div class="controls">
					<input type="password" class="span2" id="inputPassword2" name="inputPassword2" placeholder="Password" value="<?php echo !isset($password2)?'':$password2; ?>">
				</div>
			</div>
			<div class="pull-left"><div>&nbsp;</div>
			<input type="submit" class="btn pull-left btn-large" name="user" value="Submit"/>
			</div>
		</form>
		
		
	</div>
	
	
	<div class="clearfix"></div>
	<hr>
	
	
	
	<div>
		
		<h3>Default vote period (&Delta;T)</h3>
		
		<?php
		if($errorCode==11){
			echo '<div class="alert alert-error">Please provide positive number</div>';
		}else if($errorCode==15){
			echo '<div class="alert alert-error"><h1>Something went wrong</h1>'.$err.'</div>';
		}else if($errorCode==19){
			echo '<div class="alert alert-error">This operation is disabled in DEMO</div>';
		}
		if($successCode==2) echo '<div id="ss2" class="alert alert-success">&Delta;T successfully changed</div>';
		?>	
		
		<form class="form-inline" method="POST" action="settings.php">
			<div class="btn-group input-append offset50">				
				<input id="dts" name="dts" type="hidden" value="<?php echo $dts;?>"/>
				<input id="dt" name="dt" type="text" class="span2" value="<?php echo $dt;?>" placeholder="N"/>
				<button class="btn dropdown-toggle" data-toggle="dropdown"><span id="dtmarker"><?php echo $dts;?></span> <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="javascript:void(0);">seconds</a></li>
					<li><a href="javascript:void(0);">minutes</a></li>
					<li><a href="javascript:void(0);">hours</a></li>
					<li><a href="javascript:void(0);">days</a></li>
					<li class="divider"></li>
					<li><a href="javascript:void(0);">&#8734; (once per user)</a></li>
				</ul>					
			</div><!-- /btn-group -->
			
			<input type="submit" class="btn" name="voteperiod" value="Submit"/>
		</form>
		
		
		<div class="alert alert-info">Period of time before user can rate the same item again. All new items will use this default value to prohibit or allow user to rate the same item multiple times. It can be set <a href="index.php"><b>here</b></a> for each item separately, which provides the ultimate flexibility.</div>
		
	</div>   

	

	<div class="clearfix"></div>
	<hr>
	
	
	
	<div>
		<h3>Preventing multiple voting</h3>
		
		<?php
		if($errorCode==25){
			echo '<div class="alert alert-error"><h1>Something went wrong</h1>'.$err.'</div>';
		}else if($errorCode==29){
			echo '<div class="alert alert-error">This operation is disabled in DEMO</div>';
		}
		if($successCode==3) echo '<div id="ss2" class="alert alert-success">Settings successfully changed</div>';
		?>	
		<form class="form-inline" method="POST" action="settings.php">
		 
		  <label class="checkbox offset50">
			<input type="checkbox" <?php if($iscookie>0) echo 'checked';?> name="iscookie"> check Cookie
		  </label>
		   <label class="checkbox offset50">
			<input type="checkbox" <?php if($isip>0) echo 'checked';?> name="isip"> check IP
		  </label>
		  <input type="submit" class="btn" name="ipcookie" value="Submit"/>
		</form>
		<div class="alert alert-info">Methods applied to check if user already rated an item to prevent unwanted multiple votings.</div>
		
	</div>   
	
	<div class="clearfix"></div>
	<hr>
	
	<div>
		
		<h3>Top/Flop*</h3>
		<?php
		if($errorCode==35){
			echo '<div class="alert alert-error"><h1>Something went wrong</h1>'.$err.'</div>';
		}else if($errorCode==39){
			echo '<div class="alert alert-error">This operation is disabled in DEMO</div>';
		}
		if($successCode==4) echo '<div id="ss2" class="alert alert-success">Settings successfully changed</div>';
		?>	
		<form class="form-inline" method="POST" action="settings.php">
			<label class="control-label offset50" for="topvalue">Minimum votes required to enter TOP list &nbsp;
			<input type="text" class="span2" id="topvalue" name="topvalue" value="<?php echo $topvalue;?>" placeholder="N"/></label>
			<input type="submit" class="btn" name="topflop" value="Submit"/>
		</form>
		
		
		<div class="alert alert-info">
		<b>Top/Flop</b> - global ranking of item in database (similar to IMDb ranking system). More info <a href='http://en.wikipedia.org/wiki/Internet_Movie_Database#Ranking_.28IMDb_Top_250.29' target='_blank'><b>here</b></a>.
		<br>Higher values will result in more accurate global ranking of item.
		</div>

	  
	</div> 
	
	<div class="clearfix"></div>
	<hr>
	
	<div>
		<h3 id="uninstall">Uninstall the system</h3>
		<p>To drop all the rating system related tables - provide your current username and password and press uninstall</p>
		<?php
		if($errorCode>50 && $errorCode<60){
			echo '<div class="alert alert-error">';
			switch ($errorCode) {
				case 51:	echo "Username cannot be empty";break;
				case 52: echo "Password cannot be empty";break;
				case 53:	echo "Please confirm password";break;
				case 58:	echo "Wrong password or username, please try again";break;
				case 55:	echo "<h1>Something went wrong</h1>".$err;break;
				case 59:	echo "This operation is disabled in DEMO".$err;break;
			}			
			echo '</div>';
			
		}
		
		
		
		
		if($successCode==1) echo '<div class="alert alert-success">Username/password successfully changed</div>';
		?>	
		<form class="" method="POST" action="settings.php#uninstall">
			<div class="offset50 pull-left control-group <?php if($errorCode==51) echo 'error';?>">
				<label class="control-label" for="inputUsername">Username</label>
				<div class="controls">
					<input type="text" class="span2" id="inputUsernameuninstall" name="inputUsernameuninstall" placeholder="Username" value="">
				</div>
			</div>
			<div class="offset50 pull-left control-group <?php if($errorCode==52) echo 'error';?>">				
				<label class="control-label" for="inputPassword">Password</label>
				<div class="controls">
					<input type="password" class="span2" id="inputPassworduninstall" name="inputPassworduninstall" placeholder="Password" value="">
				</div>
			</div>
			<div class="pull-left"><div>&nbsp;</div>
			<input type="submit" class="btn pull-left btn-large btn-danger" name="uninstall" value="UNINSTALL"/>
			</div>
		</form>
		
		<div class="clearfix"></div>
		<div class="alert alert-danger">
		ATTENTION! This operation can not be undone! Do it only if you really want to delete all rating system tables from database. All data will be lost!
		</div>
		
	</div>
	
	

</div>   
	
<?php

include 'php/footer.php';
?>

<script src="js/application.settings.js"></script>  
 
<?php
include 'php/closehtml.php';
?>