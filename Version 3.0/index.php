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
			<h1>Welcome to Resume Registry</h1>

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


			$stmt = $pdo->query("SELECT * FROM profile");
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				

			if (isset($_SESSION['name'])) 
			{
				echo '<table border="1">';
					echo "<pre>";
					echo "<thead><tr><th>Name</th><th>Headline</th><th>Action</th></tr></thead>";
					foreach ($rows as $row)
					{			
					echo '<tr><td>';
					echo '<a href="view.php?profile_id='.$row['profile_id'].'">';
					echo (htmlentities($row['first_name'])." ".htmlentities($row['last_name']));
					echo '</a>';
					echo '</td><td>';
					echo (htmlentities($row['headline']));
					echo '</td><td>';					
					echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
					echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
					echo "</td><tr>";
					echo "\n";	
					}
					echo "</pre>";
			    echo "</table>";

				    echo "<p><a href='add.php'>Add New Entry</a></p>";
				    echo "<p><a href='logout.php'>Log Out</a></p>";
			}
			else
			{
				echo "<p><a href='login.php'>Please log in</a></p>";
				echo '<table border="1">';
					echo "<pre>";
					echo "<thead><tr><th>Name</th><th>Headline</th></tr></thead>";
					foreach ($rows as $row)
					{			
					echo '<tr><td>';
					echo '<a href="view.php?profile_id='.$row['profile_id'].'">';
					echo (htmlentities($row['first_name'])." ".htmlentities($row['last_name']));
					echo '</a>';
					echo '</td><td>';
					echo (htmlentities($row['headline']));
					echo "</td><tr>";
					echo "\n";	
					}
					echo "</pre>";
			    echo "</table>";
			}
			
			?>
		
		</div>
</body>
</html>