<?php
$servername = "127.0.0.1:3305";
$username = "DbMysql12";
$password = "DbMysql12";
$dbname = "DbMysql12";
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die('Could not connect: ' . mysql_error());
}
// we connect to localhost at port 3306
echo 'Connected successfully' . '<br>';

$sql = "SELECT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "1";
    }
} else {
    echo "0 results";
}
$conn->close();
?>