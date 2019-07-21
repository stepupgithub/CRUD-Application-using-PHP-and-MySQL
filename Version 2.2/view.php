<?php
session_start();

require_once("pdo.php");

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");       //CONTROL STATEMENT
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
else
{	echo '<pre><div class="container">';
	echo "<h1>Profile Information</h1>";
	echo "First Name: ";
	echo(htmlentities($row['first_name']));
	echo "\n";
	echo "Last Name: ";
	echo(htmlentities($row['last_name']));
	echo "\n";
	echo "Email: ";
	echo(htmlentities($row['email']));
	echo "\n";
	echo "Headline: ";
	echo(htmlentities($row['headline']));
	echo "\n";
	echo "Summary: ";
	echo(htmlentities($row['summary']));
	echo "\n\n";
	echo "<a href='index.php'>Done</a>";
	echo "</div></pre>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>PRITESH KUMAR SAHANI</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
		
</body>
</html>

