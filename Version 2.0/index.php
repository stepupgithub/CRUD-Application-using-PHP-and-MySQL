<?php 
session_start();

require_once("pdo.php");

 ?>



<!DOCTYPE html>
<html>
<head>
	<title>PRITESH KUMAR SAHANI</title>
	<?php require_once("bootstrap.php"); ?>
</head>
<body>
		<div class="container">
			<h1>Welcome to the Automobiles Database</h1>

			<?php 

			if ( isset($_SESSION['success']) ) 
			{
 				echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  				unset($_SESSION['success']);
			}

			if ( isset($_SESSION['error']) ) 
			{
 				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  				unset($_SESSION['error']);
			}


			if (isset($_SESSION['name'])) 
			{
				$stmt = $pdo->query("SELECT * FROM autos");
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				echo '<table border="1">';

				if (!$rows) 
				{
					echo "No rows found";
					echo "<p><a href='add.php'>Add New Entry</a></p>";
				    echo "<p><a href='logout.php'>Log Out</a></p>";
				}
				else
				{
					echo "<pre>";
					echo "<thead><tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th></tr></thead>";
					echo "</pre>";
					foreach ($rows as $row)
					{
					echo "<pre>";
			
					echo '<tr><td>';
					echo (htmlentities($row['make']));
					echo '</td><td>';
					echo (htmlentities($row['model']));
					echo '</td><td>';
					echo (htmlentities($row['year']));
					echo '</td><td>';
					echo (htmlentities($row['mileage']));
					echo '</td><td>';
					echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
					echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
					echo "</td><tr>";
					echo "\n";
					
					echo "</pre>";
					}
				  echo "</table>";

				  echo "<p><a href='add.php'>Add New Entry</a></p>";
				  echo "<p><a href='logout.php'>Log Out</a></p>";
				}
			}
			else
			{
				echo "<p><a href='login.php'>Please log in</a></p>";
			}


			?>
		
		</div>
</body>
</html>