<?php // line added to turn on color syntax highlight

session_start();

require_once "pdo.php";                     //making connection with the database

if ( ! isset($_SESSION['name']) ) 
{
  die('ACCESS DENIED');
}

if (isset($_POST['cancel'])) 
{
	header("Location: index.php");
	return;
}


if ((isset($_POST['make'])) && (isset($_POST['year'])) && (isset($_POST['mileage'])) && (isset($_POST['model']))) 
{
	if ((strlen($_POST['make'])<1) || (strlen($_POST['year'])<1) || (strlen($_POST['mileage'])<1) || (strlen($_POST['model'])<1)) 
	{
    $_SESSION['error'] = "All fields are required";
    header("Location: add.php");
    return;
    }

	if (!is_numeric($_POST['mileage']))
	{
    	$_SESSION['error'] = "Mileage must be an integer";
    	header("Location: add.php");
		return;
    }
	if (!is_numeric($_POST['year']))
	{
		$_SESSION['error'] = "Year must be an integer"; 
		header("Location: add.php");
		return;
    }
		
		$sql = "INSERT INTO autos (make, model, year, mileage)
     	       VALUES (:mk, :mo, :yr, :mi)";
    	$stmt = $pdo->prepare($sql);
    	$stmt->execute(array(
    		':mk' => $_POST['make'],
    		':mo' => $_POST['model'],
    		':yr' => $_POST['year'],
    		':mi' => $_POST['mileage']));
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
				<h1>Tracking Autos for <?= htmlentities($_SESSION['name']); ?></h1>
				
				<form method="POST">

					<p><label for="inp1">Make: </label>
					<input type="text" name="make" id="inp1" size="40"></p>

					<p><label for="inp2">Model: </label>
					<input type="text" name="model" id="inp2" size="40"></p>

					<p><label for="inp3">Year: </label>
					<input type="text" name="year" id="inp3" size="40"></p>

					<p><label for="inp4">Mileage: </label>
					<input type="text" name="mileage" id="inp4" size="40"></p>

					<p><input type="submit" name="add" value="Add">

					<input type="submit" name="cancel" value="Cancel"></p>
				</form>

			</div>
</body>
</html>