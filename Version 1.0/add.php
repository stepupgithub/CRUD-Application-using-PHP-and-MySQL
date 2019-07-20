<?php // line added to turn on color syntax highlight

session_start();

require_once "pdo.php";                     //making connection with the database

if ( ! isset($_SESSION['name']) ) 
{
  die('Not logged in');
}

if (isset($_POST['logout'])) 
{
	header("Location: index.php");
	return;
}


if ((isset($_POST['make'])) && (isset($_POST['year'])) && (isset($_POST['mileage'])))  //when the "add new" button is pressed the values get automatically checked and the following is executed
{
	if (strlen($_POST['make']==false)) 
	{
    	$_SESSION['error'] = "Make is required";
    	header("Location: add.php");
		return;
    }
	elseif ((!is_numeric($_POST['year'])) || (!is_numeric($_POST['mileage'])))
	{
		$_SESSION['error'] = "Mileage and year must be numeric"; 
		header("Location: add.php");
		return;
    }
	else
	{
		$sql = "INSERT INTO autos (make, year, mileage)
     	       VALUES (:mk, :yr, :mi)";
    	$stmt = $pdo->prepare($sql);
    	$stmt->execute(array(
    		':mk' => $_POST['make'],
    		':yr' => $_POST['year'],
    		':mi' => $_POST['mileage']));
    	$_SESSION['success'] = "Record inserted";
    	header("Location: view.php");
		return;
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
				<h1>Tracking Autos for <?= htmlentities($_SESSION['name']); ?></h1>
				
				<form method="POST">

					<p><label for="inp1">Make: </label>
					<input type="text" name="make" id="inp1" size="40"></p>

					<p><label for="inp2">Year: </label>
					<input type="text" name="year" id="inp2" size="40"></p>

					<p><label for="inp3">Mileage: </label>
					<input type="text" name="mileage" id="inp3" size="40"></p>

					<p><input type="submit" name="add" value="Add">

					<input type="submit" name="logout" value="Logout"></p>
				</form>

				<?php
				if (isset($_SESSION['error']))
				{
				echo "<pre>";
				echo '<p style="color: red;">';
				echo $_SESSION['error'];
				unset($_SESSION['error']);
				echo "</p>";
				echo "</pre>";
			    }
				?>

			</div>
</body>
</html>