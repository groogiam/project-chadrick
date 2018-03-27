<?php
require '../dbHelper.php';

$lastBurnId = (int)$_GET['LastBurnId'];
$reqLimit = 6;

$conn = dbHelper::createInstance();

$query = "select * from Burn order by Id Limit $reqLimit";
if (! is_null($lastBurnId)) {
    //this should be safe as int cast should take care of any sql injection issues.
    $query = "select * from Burn where Id > $lastBurnId order by Id Limit $reqLimit"; 
}

$result = $conn->query($query);
$retval = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();

echo json_encode($retval);

?>