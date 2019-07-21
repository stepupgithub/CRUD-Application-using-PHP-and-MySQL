<?php // line added to turn on color syntax highlight

session_start();

require_once "pdo.php";                     //making connection with the database

if ( ! isset($_SESSION['name']) ) 
{
  die('ACCESS DENIED');
}

if (isset($_POST['cancel'])) 
{
	header("Location: index.php");
	return;
}


if ((isset($_POST['first_name'])) && (isset($_POST['last_name'])) && (isset($_POST['email'])) && (isset($_POST['headline'])) && (isset($_POST['summary']))) 
{
	if ((strlen($_POST['first_name'])<1) || (strlen($_POST['last_name'])<1) || (strlen($_POST['email'])<1) || (strlen($_POST['headline'])<1) || (strlen($_POST['summary'])<1))                                      
	{                                                                          //PROFILE VALIDATION
    $_SESSION['error'] = "All fields are required";
    header("Location: add.php?profile_id=" . $_REQUEST["profile_id"]);
    return;
    }

    if(!strpos($_POST['email'], "@"))
    {
    $_SESSION['error'] = "Email address must contain @";
    header("Location: add.php?profile_id=" . $_REQUEST["profile_id"]);
    return;
    }

    for($i=1; $i<=9; $i++) 
    {                                                    //POSITION VALIDATION
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    if ( strlen($year) == 0 || strlen($desc) == 0 ) 
    {
      $_SESSION['error'] ="All fields are required";
      header("Location: add.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }

    if ( ! is_numeric($year) ) 
    {
      $_SESSION['error'] ="Position year must be numeric";
      header("Location: add.php?profile_id=" . $_REQUEST["profile_id"]);
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
      header("Location: add.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }

    if ( ! is_numeric($year) ) 
    {
      $_SESSION['error'] ="Year must be numeric";
      header("Location: add.php?profile_id=" . $_REQUEST["profile_id"]);
      return;
    }
    }

		
		$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)";
    	$stmt = $pdo->prepare($sql);
    																//inserting into profile database
        $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
        );

        


      $profile_id = $pdo->lastInsertId();

      $rank = 1;													//inserting into position database
  		for($i=1; $i<=9; $i++) 
      {
  		if ( ! isset($_POST['year'.$i]) ) continue;
  		if ( ! isset($_POST['desc'.$i]) ) continue;
		
  		$year = $_POST['year'.$i];
  		$desc = $_POST['desc'.$i];
  		$stmt = $pdo->prepare('INSERT INTO Position
  		  (profile_id, rank, year, description)
  		  VALUES ( :pid, :rank, :year, :des)');
		
  		$stmt->execute(array(
  		':pid' => $profile_id,
  		':rank' => $rank,
  		':year' => $year,
  		':des' => $desc)
  		);
		
  		$rank++;
		  }

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
        ':pid' => $profile_id,
        ':iid' => $institution_id,
        ':rank' => $rank,
        ':year' => $year)
        );
      
        $rank++;
      }

    	$_SESSION['success'] = "Profile added";
    	header("Location: index.php");
		return;	
}

if ( isset($_SESSION['error']) ) 
{
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
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
				<h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?></h1>
				
				<form method="POST">

					<p><label for="inp1">First Name: </label>
					<input type="text" name="first_name" id="inp1" size="40"></p>

					<p><label for="inp2">Last Name: </label>
					<input type="text" name="last_name" id="inp2" size="40"></p>

					<p><label for="inp3">Email: </label>
					<input type="text" name="email" id="inp3" size="30"></p>

					<p><label for="inp4">Headline: </label>
					<input type="text" name="headline" id="inp4" size="50"></p>

					<p>Summary:</br>
						<textarea name="summary" rows="8" cols="80"></textarea></p>

          <p>
          Education: <input type="submit" id="addEdu" value="+">
          <div id="edu_fields"></div>
          </p>

					<p>
					Position: <input type="submit" name="addPos" id="addPos" value="+">
					<div id="position_fields"></div>
					</p>

					<p><input type="submit" name="add" value="Add">

					<input type="submit" name="cancel" value="Cancel"></p>
				</form>

				<script>
countPos = 0;
countEdu = 0;

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
            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
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

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });

});

</script>


			</div>
</body>
</html>