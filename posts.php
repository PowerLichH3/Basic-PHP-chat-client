<!DOCTYPE html>
<html>
<head>
<title>Assignment 7</title>
<link rel="stylesheet" href="style.css" type="text/css">
<body>
<section class="post-section">
<fieldset>
<form action="index.php">
	<input type="submit" value="Back"/>
</form>
</fieldset>
<?php
$dsn = getenv('DB');
$user = getenv('DBUSER');
$pass = getenv('DBPASS');


$conn = new PDO($dsn, $user, $pass);

$sql = $conn->prepare("SELECT * FROM posttable");

if($sql->execute()){
    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
        echo "<div class=\"post\"><b>" . $row["post_name"]." </b> <br>" . $row["post_time"] . "<br><br> <a>". $row["post_comment"] ." </a></div>\n";
        
    }
}


?>
</section>
</body>
</html>