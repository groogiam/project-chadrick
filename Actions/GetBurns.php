<?php
require '../dbHelper.php';

$lastBurnId = (int)$_GET['LastBurnId'];
$reqLimit = 6;

$conn = dbHelper::createInstance();

$minQuery = "select min(Id) from Burn";
$minId = $conn->query($minQuery)->fetch_array()[0];

$query = "select * from Burn order by Id desc Limit $reqLimit";
if ($lastBurnId !== 0) {
    //this should be safe as int cast should take care of any sql injection issues.
    $query = "select * from Burn where Id < $lastBurnId order by Id desc Limit $reqLimit"; 
}

//echo $minId;
//echo $query;

if($lastBurnId != 0 and $lastBurnId <= $minId) {
    echo "[]";
    return;
}

$result = $conn->query($query);
$retval = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();

echo json_encode($retval);

?>