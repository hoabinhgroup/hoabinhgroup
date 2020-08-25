<?php if(session_id() == "") session_start(); 
$ref = $_SERVER['HTTP_REFERER'];
if (!$ref) $ref='index.php'; //just in case
$errorCode=0;
$showNavigation=false;
include_once ('php/config.php');
// Only process if the login form has been submitted.

if(isset($_POST['login'])) {

	
	$link = @mysqli_connect($dbhost, $dbuser, $dbpass,$db_name);
	/* check connection */
	if (mysqli_connect_errno()) {
		$errorCode=10;
	}else{

		$username = trim(mysqli_real_escape_string($link,$_POST['inputUsername'])); //addslashes
		$password = trim(mysqli_real_escape_string($link,$_POST['inputPassword'])); 

		if (empty($username)) $errorCode=1;
		else if(empty($password)) $errorCode=2;
		else { 
		
		

			$user_pass=md5($username.'_'.$password);

			$sql = "SELECT * FROM $rating_set WHERE property='user' AND value='$user_pass'"; 
			$result = $link->query($sql) or $errorCode=4;

			
			if($result){
				if(mysqli_num_rows($result) > 0) { 
					while($row = mysqli_fetch_array($result)) { 

						// Start the session and register a variable 

						if(session_id() == "") session_start(); 
						$_SESSION['username'] = $user_pass;
						header("Location: ".$ref); exit();

					}

				} 
				else $errorCode=3;
			}else $errorCode=4;
			
		}
	}
}
	
	
	
include 'php/head.php';
echo '<div class="container">';

if($errorCode>0){
	echo '<div class="alert alert-error"><i class="icon-warning-sign"></i>';
	if($errorCode==1) echo 'Field username can not be empty';
	else if($errorCode==2) echo 'Field password can not be empty';
	else if($errorCode==4) echo ' Error: '.mysqli_error($link).'. Make sure you completed the <a href="install.php">installation</a>.';	
	else if($errorCode==10) echo ' Error: '.mysqli_connect_error().'. Make sure you completed the <a href="install.php">installation</a>.';
	else echo ' No data found in table "'.$rating_set.'". Make sure you completed the <a href="install.php">installation</a>.';	
	echo '</div>';
}	

if($demoMode){
	echo '<div class="alert alert-info">';
	echo 'Use Username:<b>demo</b> Password:<b>demo</b> to login';	
	echo '</div>';
}
?>

<div class="lform" >
		<h2>Login</h2>
	  <form class="" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		  <div class="control-group <?php echo ($errorCode==1 || $errorCode==3)?'error':''; ?>">			
			<label class="control-label" for="inputUsername">Username</label>
			<div class="controls">
			  <input type="text" id="inputUsername" name="inputUsername" placeholder="Username" value="<?php echo !isset($username)?'':$username; ?>">
			</div>
		  </div>
		  <div class="control-group <?php echo ($errorCode>1)?'error':''; ?>">			
			<label class="control-label" for="inputPassword">Password</label>
			<div class="controls">
			  <input type="password" id="inputPassword" name="inputPassword" placeholder="Password" value="<?php echo !isset($password)?'':$password; ?>">
			</div>
		  </div>
		  <div class="control-group">
			<div class="controls">			  
			  <input type="submit" class="btn" name="login" value="Sign in"/>
			</div>
		  </div>
	</form>
 </div>



<?php 
echo '</div>';
include 'php/footer.php';
include 'php/closehtml.php';
?>