<?php
require_once "pdo.php";
session_start();

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
    header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
    return;
    }

    if(!strpos($_POST['email'], "@"))
    {
    $_SESSION['error'] = "Email address must contain @";
    header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
    return;
    }

            $sql = "UPDATE profile SET first_name = :first_name,
            last_name = :last_name, email = :email, headline = :headline, summary= :summary
            WHERE profile_id = :profile_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary'],
            ':profile_id' => $_POST['profile_id']));


            $_SESSION['success'] = 'Profile updated';
            header( 'Location: index.php' ) ;
            return;

}


$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");      //CONTROL STATEMENT
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
$profile_id = $row['profile_id'];   //we can also use $profile_id = $_GET['profile_id']; 
?>


<!DOCTYPE html>
<html>
<head>
    <title>PRITESH KUMAR SAHANI</title>
    <?php require_once("bootstrap.php"); ?>
</head>
<body>
    <div class="container">
            <h1>Editing Profile for <?= htmlentities($_SESSION['name']); ?></h1>
            <form method="POST">

                    <p><label for="inp1">First Name: </label>
                    <input type="text" name="first_name" id="inp1" size="40" value="<?= htmlentities($first_name); ?>"></p>

                    <p><label for="inp2">Last Name: </label>
                    <input type="text" name="last_name" id="inp2" size="40" value="<?= htmlentities($last_name); ?>"></p>

                    <p><label for="inp3">Email: </label>
                    <input type="text" name="email" id="inp3" size="30" value="<?= htmlentities($email)?>"></p>

                    <p><label for="inp4">Headline: </label>
                    <input type="text" name="headline" id="inp4" size="50" value="<?= htmlentities($headline)?>"></p>

                    <p>Summary:</br>
                        <input name="summary" value="<?= htmlentities($summary)?>"></input></p>

                    <input type="hidden" name="profile_id" value="<?= $profile_id ?>">

                    <p><input type="submit" name="save" value="Save">

                    <input type="submit" name="cancel" value="Cancel"></p>
            </form>
    </div>
</body>
</html>