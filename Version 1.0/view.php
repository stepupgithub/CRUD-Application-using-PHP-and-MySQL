<?php // line added to turn on color syntax highlight

session_start();

require_once "pdo.php";                     //making connection with the database

if ( ! isset($_SESSION['name']) ) {
  die('Not logged in');
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

			<?php 
			if (isset($_SESSION['success']))
			{
				echo "<pre>";
				echo '<p style="color: green;">';
				echo $_SESSION['success'];
				unset($_SESSION['success']);
				echo "</p>";
				echo "</pre>";
			}
			?>

			<h2>Automobiles</h2>

			<?php  
				$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				echo "<table>";
				foreach ($rows as $row)
				{
					echo "<pre>";
			
					echo '<tr style="border: 3px solid black; margin: 2px;"><td style="border: 1px solid black;">';
					echo(htmlentities($row['make']));
					echo '</td><td style="border: 1px solid black;">';
					echo(htmlentities($row['year']));
					echo '</td><td style="border: 1px solid black;">';
					echo(htmlentities($row['mileage']));
					echo "</td><tr>";
					echo "\n";
					
					echo "</pre>";
				}
				echo "</table>";

				?>

			<p><a href="add.php">Add New</a> | <a href="logout.php">Logout</a></p>
		</div>
</body>
</html>