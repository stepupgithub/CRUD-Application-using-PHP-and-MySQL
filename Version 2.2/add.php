<?php // line added to turn on color syntax highlight

session_start();

require_once "pdo.php";                     //making connection with the database

if ( ! isset($_SESSION['name']) ) 
{
  die('Not logged in');
}

if (isset($_POST['cancel'])) 
{
	header("Location: index.php");
	return;
}


if ((isset($_POST['first_name'])) && (isset($_POST['last_name'])) && (isset($_POST['email'])) && (isset($_POST['headline'])) && (isset($_POST['summary']))) 
{
	if ((strlen($_POST['first_name'])<1) || (strlen($_POST['last_name'])<1) || (strlen($_POST['email'])<1) || (strlen($_POST['headline'])<1) || (strlen($_POST['summary'])<1))
	{
    $_SESSION['error'] = "All fields are required";
    header("Location: add.php?profile_id=" . $_POST["profile_id"]);
    return;
    }

    if(!strpos($_POST['email'], "@"))
    {
    $_SESSION['error'] = "Email address must contain @";
    header("Location: add.php?profile_id=" . $_POST["profile_id"]);
    return;
    }
		
		$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)";
    	$stmt = $pdo->prepare($sql);

        $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
        );

    	$_SESSION['success'] = "Record added";
    	header("Location: index.php");
		return;	
}

if ( isset($_SESSION['error']) ) 
{
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
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
				<h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?></h1>
				
				<form method="POST">

					<p><label for="inp1">First Name: </label>
					<input type="text" name="first_name" id="inp1" size="40"></p>

					<p><label for="inp2">Last Name: </label>
					<input type="text" name="last_name" id="inp2" size="40"></p>

					<p><label for="inp3">Email: </label>
					<input type="text" name="email" id="inp3" size="30"></p>

					<p><label for="inp4">Headline: </label>
					<input type="text" name="headline" id="inp4" size="50"></p>

					<p>Summary:</br>
						<textarea name="summary" rows="8" cols="80"></textarea></p>

					<p><input type="submit" name="add" value="Add">

					<input type="submit" name="cancel" value="Cancel"></p>
				</form>

			</div>
</body>
</html>