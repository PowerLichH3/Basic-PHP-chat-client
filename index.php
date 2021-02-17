<?php
include 'dbconfig.php';
session_start();
global $name, $comment;
$nameInvalid = FALSE;
$commentInvalid = FALSE;
$dsn = getenv('DB');
$user = getenv('DBUSER');
$pass = getenv('DBPASS');


try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE posttable (
    post_id INT PRIMARY KEY AUTO_INCREMENT,
    post_name VARCHAR(20),
    post_time TIMESTAMP,
    post_comment VARCHAR(200)
    )";
    
    try{
        $conn->exec($sql);
        echo "Post table created succesfully";
        
    }catch(PDOException $e) {
        echo "Table already exists";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}




date_default_timezone_set('Europe/Helsinki');




if (isset($_POST['submit'])) {

    if (empty($_POST['name'])) {
        $nameInvalid = TRUE;
    } else {
        $name = $_POST['name'];
    }

    if (empty($_POST["comment"])) {
        $commentInvalid = TRUE;
    } else {
        $comment = $_POST["comment"];
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Assignment 7</title>
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
	<section class="post-section">
<?php
$sql = $conn->prepare("SELECT * FROM posttable");

$result = $sql->fetch(PDO::FETCH_ASSOC);
echo $result;

if($sql->execute()){
    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
        echo "<div class=\"post\"><b>" . $row["post_name"]." </b> <br>" . $row["post_time"] . "<br><br> <a>". $row["post_comment"] ." </a></div>\n";
        
    }
}

global $name, $comment, $nameInvalid, $commentInvalid;

if (isset($_POST['submit'])) {  
    if ($nameInvalid == FALSE && $commentInvalid == FALSE) {
        $date = date("Y/n/j H.i");
        $sql = $conn->prepare( "INSERT INTO posttable (post_name, post_time, post_comment) VALUES ('$name', '$date', '$comment')");
        if ($sql->execute()) {
            echo "<div class=\"post\"><b>" . $name." </b> <br>" . $date . "<br><br> <a>". $comment ." </a></div>\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
    }
}
$conn = NULL;
?>
</section>
	<section class="comment-section">
		<form action="index.php" method="POST">
			<fieldset>
				<label for="name">Name:</label><br> 
				<input required type="text" id="name" name="name"
					value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ""; ?>" /><br>
				<label for="comment-area">Comment:</label><br>
				<textarea required name="comment" id="comment-area"
					placeholder="Write your comment here"><?php echo isset($_POST["comment"]) ? $_POST["comment"] : ""; ?></textarea>

				<input type="submit" value="Submit" name="submit"> 
				<input type="reset">
			</fieldset>
		</form>
		<form action="searchresult.php" method="POST">
			<fieldset>
				

				<label for="search-field">Search posts</label> 
				<input required
					type="text" id="search-field" name="search-field"
					placeholder="input search terms"></input>
				<input type="submit" value="Search" name="searchButton"></input>
				
			</fieldset>
		</form>
		<form action="posts.php">
			<fieldset>
				<input type="submit" value="Posts"/>
			</fieldset>
		</form>
	</section>
</body>
</html>

