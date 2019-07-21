<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['name']) ) 
{
  die('ACCESS DENIED');
}

if (isset($_POST['cancel'])) 
{
    header("Location: index.php");
    return;
}

if (!isset($_REQUEST["profile_id"])) 
{
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
}

if ((isset($_POST['first_name'])) && (isset($_POST['last_name'])) && (isset($_POST['email'])) && (isset($_POST['headline'])) && (isset($_POST['summary']))) 
{
    if ((strlen($_POST['first_name'])<1) || (strlen($_POST['last_name'])<1) || (strlen($_POST['email'])<1) || (strlen($_POST['headline'])<1) || (strlen($_POST['summary'])<1))
    {
    $_SESSION['error'] = "All fields are required";
    header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
    return;
    }

    if(!strpos($_POST['email'], "@"))
    {
    $_SESSION['error'] = "Email address must contain @";
    header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
    return;
    }

    for($i=1; $i<=9; $i++)                                 //POSITION VALIDATION
    {                                                    
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    if ( strlen($year) == 0 || strlen($desc) == 0 ) 
    {
      $_SESSION['error'] ="All fields are required";
      header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }

    if ( ! is_numeric($year) ) 
    {
      $_SESSION['error'] ="Position year must be numeric";
      header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }
    }

    for($i=1; $i<=9; $i++)                                 //EDUCATION VALIDATION
    {                                                    
    if ( ! isset($_POST['edu_year'.$i]) ) continue;
    if ( ! isset($_POST['edu_school'.$i]) ) continue;

    $year = $_POST['edu_year'.$i];
    $school = $_POST['edu_school'.$i];

    if ( strlen($year) == 0 || strlen($school) == 0 ) 
    {
      $_SESSION['error'] ="All fields are required";
      header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }

    if ( ! is_numeric($year) ) 
    {
      $_SESSION['error'] ="Year must be numeric";
      header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }
    }


            $sql = "UPDATE profile SET first_name = :first_name,
            last_name = :last_name, email = :email, headline = :headline, summary= :summary
            WHERE profile_id = :profile_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':profile_id' => $_POST['profile_id'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary']));


            // Clear out all the old position entries
            $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
            $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

            // Insert the new position entries

            $rank = 1;
            for($i=1; $i<=9; $i++) 
            {
              if ( ! isset($_POST['year'.$i]) ) continue;
              if ( ! isset($_POST['desc'.$i]) ) continue;
            
              $year = $_POST['year'.$i];
              $desc = $_POST['desc'.$i];
              $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description)
                VALUES ( :pid, :rank, :year, :desc)');
            
              $stmt->execute(array(
              ':pid' => $_REQUEST['profile_id'],
              ':rank' => $rank,
              ':year' => $year,
              ':desc' => $desc)
              );
            
              $rank++;
            }


            // Clear out all the old education entries
            $stmt = $pdo->prepare('DELETE FROM education WHERE profile_id=:pid');
            $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

            // Insert the new education entries
            $rank = 1;
            for($i=1; $i<=9; $i++) 
            {
              if ( ! isset($_POST['edu_year'.$i]) ) continue;
              if ( ! isset($_POST['edu_school'.$i]) ) continue;
              $year = $_POST['edu_year'.$i];
              $school = $_POST['edu_school'.$i];

              //lookup for the school if it is there
              $institution_id=false;
              $stmt= $pdo->prepare('SELECT institution_id FROM institution WHERE name=:name');
              $stmt->execute(array(':name'=> $school));
              $row=$stmt->fetch(PDO::FETCH_ASSOC);
              if ($row!==false) 
              {
                  $institution_id=$row['institution_id'];
              }

              //if there was no institution, insert it
              if ($institution_id===false) 
              {
                  $stmt=$pdo->prepare('INSERT INTO institution(name) VALUES (:name)');
                  $stmt->execute(array(':name'=> $school));
                  $institution_id=$pdo->lastInsertId();
              }
             
              $stmt = $pdo->prepare('INSERT INTO education
                (profile_id, institution_id, rank, year)
                VALUES ( :pid, :iid, :rank, :year)');
            
              $stmt->execute(array(
              ':pid' => $_REQUEST['profile_id'],
              ':iid' => $institution_id,
              ':rank' => $rank,
              ':year' => $year)
              );
            
              $rank++;
            }
  

            $_SESSION['success'] = 'Profile updated';
            header( 'Location: index.php' ) ;
            return;

}

//CONTROL STATEMENT
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");      
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

                    <p><b>Summary:</b></br>
                        <input name="summary" value="<?= htmlentities($summary)?>"></input></p>

                    <input type="hidden" name="profile_id" value="<?= $profile_id ?>">

                    <div id="position_fields">
                    

<?php 

//LOAD UP THE POSITION ROWS

$countPos=0;

$stmt=$pdo->prepare('SELECT * FROM position WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof' => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<p><b>Position: </b><input type="submit" name="addPos" id="addPos" value="+">';
echo '<div id="position_fields">';

if (count($rows)>0) 
{
    foreach($rows as $row)
    {
        $countPos++;
        echo '<div id="position'.$countPos.'">';
        echo '<p><b>Year: </b><input type="text" name="year'.$countPos.'" value="'.htmlentities($row['year']).'"/>';
        echo '<input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();return false;"></p>';
        echo '<input name="desc'.$countPos.'" value="'.htmlentities($row['description']).'">';
        echo '</div>';
    }
}

echo "</div></p>";

//LOAD UT THE EDUCATION ROWS

$countEdu=0;

$stmt=$pdo->prepare('SELECT * FROM education JOIN institution ON education.institution_id=institution.institution_id WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof' => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<p><b>Education: </b><input type="submit" name="addEdu" id="addEdu" value="+">';
echo '<div id="edu_fields">';

if (count($rows)>0) 
{
    foreach($rows as $row)
    {
        $countEdu++;
        echo '<div id="edu'.$countEdu.'">';
        echo '<p><b>Year: </b><input type="text" name="edu_year'.$countEdu.'" value="'.htmlentities($row['year']).'"/>';
        echo '<input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove();return false;"></p>';
        echo '<p><b>School: </b><input type="text" class="school" name="edu_school'.$countEdu.'" value="'.htmlentities($row['name']).'">';
        echo '</div>';
    }
}

echo "</div></p>";

?>

                    <p><input type="submit" name="save" value="Save">

                    <input type="submit" name="cancel" value="Cancel"></p>
            </form>

<script>
countPos = 1;
countEdu = 1;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');

    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);

        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        // Grab some HTML with hot spots and insert into the DOM
        var source  = $("#edu-template").html();
        $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));

        // Add the even handler to the new ones
        $('.school').autocomplete({
            source: "school.php"
        });

    });

    $('.school').autocomplete({
        source: "school.php"
    });

});

</script>
<!-- HTML with Substitution hot spots -->
<script id="edu-template" type="text">
  <div id="edu@COUNT@">
    <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
    <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
    </p>
  </div>
</script>

    </div>
</body>
</html>