
<?php
/* 
    //Connect to the database
    $host = "127.0.0.1";
    $user = "nicoede";                           //Your Cloud 9 username
    $pass = "";                                  //Remember, there is NO password by default!
    $db = "cms";                                  //Your database name you want to connect to
    $port = 3306;                                //The port #. It is always 3306
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysqli_error($connection));
*/
?>


<?php
$url = parse_url(getenv("mysql://b8e6b3c540d809:7afb81de@us-cdbr-iron-east-05.cleardb.net/heroku_481ed05723dc72a?reconnect=true"));

$server = $url["us-cdbr-iron-east-05.cleardb.net"];
$username = $url["b8e6b3c540d809"];
$password = $url["7afb81de"];
$db = substr($url["heroku_481ed05723dc72a"], 1);

$connection = new mysqli($server, $username, $password, $db);
?>