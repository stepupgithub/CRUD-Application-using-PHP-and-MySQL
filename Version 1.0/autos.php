<?php

require_once "pdo.php";                     //making connection with the database

if (!isset($_GET['name']))                  //you cannot enter into autos.php without entering the username into the previous page
{
	die("Name parameter missing");
}



if (isset($_POST['logout'])) 
{
	header("Location: index.php");
	return;
}

$say =" ";
$x=0;

if (!strlen($_POST['make']<1)) 
$say= "Make is required";
elseif ((isset($_POST['make'])) && (isset($_POST['year'])) && (isset($_POST['mileage'])))  //when the "add new" button is pressed the values get automatically checked and the following is executed
{
	if ((!is_numeric($_POST['year'])) || (!is_numeric($_POST['mileage'])))
	$say= "Mileage and year must be numeric"; 
	else
	{
		$sql = "INSERT INTO autos (make, year, mileage)
     	       VALUES (:mk, :yr, :mi)";
    	$stmt = $pdo->prepare($sql);
    	$stmt->execute(array(
    		':mk' => $_POST['make'],
    		':yr' => $_POST['year'],
    		':mi' => $_POST['mileage']));
    	$x=1;
    	$say="Record inserted";
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
				<h1>Tracking Autos for <?= htmlentities($_GET['name']); ?></h1>
				
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
				if ($x==1)
				{
				echo "<pre>";
				echo '<p style="color: green;">';
				echo $say;
				echo "</p>";
				echo "</pre>";
			    }
			    else
			    {
			    echo "<pre>";
				echo '<p style="color: red;">';
				echo $say;
				echo "</p>";
				echo "</pre>";
			    }
				?>
				
				<h2>Automobiles: </h2>

				<?php  
				$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				echo "<table>";
				foreach ($rows as $row)
				{
					echo "<pre>";
			
					echo '<tr style="border: 3px solid black; margin: 2px;"><td style="border: 1px solid black;">';
					echo $row['make'];
					echo '</td><td style="border: 1px solid black;">';
					echo $row['year'];
					echo '</td><td style="border: 1px solid black;">';
					echo $row['mileage'];
					echo "</td><tr>";
					echo "\n";
					
					echo "</pre>";
				}
				echo "</table>";

				?>
			</div>
</body>
</html>