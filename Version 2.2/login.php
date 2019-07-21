<?php 
session_start();

require_once "pdo.php";                     //making connection with the database

if (isset($_POST['cancel'])) 
{
    header("Location: index.php");
    return;
}

$salt="XyZzy12*_";
$check = hash('md5', $salt.$_POST['pass']);

$stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
$row = $stmt->fetch(PDO::FETCH_ASSOC);


if ( isset($_POST['email']) && isset($_POST['pass'])) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) 
    {
        $_SESSION['error'] = "User name and password are required";
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
            if ($row)
            {
                // Redirect the browser to index.php
                error_log("Login success ".$_POST['email']);
                $_SESSION['name'] = htmlentities($_POST['email']);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['success'] = "Login Success";
                header("Location: index.php");  
                return;
            } 
            else 
            {
                error_log("Login fail ".$_POST['email']." $hash");
                $_SESSION['error'] = "Incorrect password";
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
    <script type="text/javascript" src="js.js"></script>
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

				<input type="submit" onclick="return doValidate();" value="Log In">
				<input type="submit" name="cancel" value="Cancel">

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