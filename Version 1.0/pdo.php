<?php            //Here connection with the database is made.

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=project1', 'pritesh', '123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>

