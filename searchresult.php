<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css">
<h1>Search results</h1>
</head>
<body>
<section class ="post-section">
<fieldset>
<?php
$dsn = getenv('DB');
$user = getenv('DBUSER');
$pass = getenv('DBPASS');

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (isset($_POST["searchButton"])) {
    $searchfor = $_POST["search-field"];
    $sql = $conn->prepare("SELECT * FROM posttable WHERE post_comment LIKE '%$searchfor' OR post_name LIKE '%$searchfor'");
    
    if($sql->execute())
        if($sql->rowCount()>0){
        while($row = $sql->fetch(PDO::FETCH_ASSOC)){
            echo "<div class=\"post\"><b>" . $row["post_name"]." </b> <br>" . $row["post_time"] . "<br><br> <a>". $row["post_comment"] ." </a></div>\n";
        }
    }
    else {
        echo "No posts found with the search terms";
    }

    }

    
    


$conn = NULL;
?>
</fieldset>
<fieldset>
<form action="index.php">
    <input type="submit" value="Back" />
</form>
    
</fieldset>
</section>
</body>
</html>