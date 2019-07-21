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
	echo "\n";
	echo "</div></pre>";
}

$stmt=$pdo->prepare('SELECT * FROM position WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof' => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<div class='container'><pre><ul>";
echo "<h2>Position:</h2>";
foreach($rows as $row)
{
	echo "<li>";
	echo(htmlentities($row['year']).': '.htmlentities($row['description']));	
	echo "</li>";
}
echo "</ul>\n";
echo "<a href='index.php'>Done</a>";
echo "</pre></div>";

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

