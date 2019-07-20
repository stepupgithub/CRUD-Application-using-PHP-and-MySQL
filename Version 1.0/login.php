<?php 
session_start();

require_once "pdo.php";                     //making connection with the database

if (isset($_POST['cancel'])) 
{
	header("Location: index.php");
	return;                                //if the cancel button is pressed, returns to the index page
}

$salt="XyZzy12*_";
$hash="1a52e17fa899cf40fb04cfc42e6352f1";  //MD5 hash after adding the salt


if ( isset($_POST['email']) && isset($_POST['pass']) ) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) 
    {
        $_SESSION['error'] = "Both email and password are required";
        header("Location: login.php");
        return;
    } 
    else 
    {

        if (strpos($_POST['email'], "@")===false) 
        {
            $_SESSION['error'] = "email must have an at-sign (@)";
            header("Location: login.php");
            return;
        }
        else
        {
            if ($hash == hash('md5', $salt.$_POST['pass'])) 
            {
                // Redirect the browser to view.php
                error_log("Login success ".$_POST['email']);
                $_SESSION['name'] = htmlentities($_POST['email']);   
                header("Location: view.php");  
                return;
            } 
            else 
            {
                error_log("Login fail ".$_POST['email']." $hash");
                $_SESSION['failure'] = "Incorrect password";
                header("Location: login.php");
                return;
            }
        }
    }
}

?>





<!DOCTYPE html>
<html>
<head>
	<title>PRITESH KUMAR SAHANI</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
		<div class="container">
		<h2>Please Log In</h2>
		<p>
			<form method="POST">

				<label for="inp1">User Name:</label>
				<input type="text" name="email" id="inp1"><br>

				<label for="inp2">Password:</label>
				<input type="text" name="pass" id="inp2"><br>

				<input type="submit" value="Log In">
				<input type="submit" value="Cancel" name="cancel">

			</form>
		</p>

		<p>HINT: See the Page Source</p>
		<?php // line added to turn on color syntax highlight

		if ( isset($_SESSION['error']) ) 
		{
 		 echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
 		 unset($_SESSION['error']);
 		}
 		?>

		</div>
</body>
</html>