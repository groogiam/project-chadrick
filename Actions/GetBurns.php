<?php
require '../dbHelper.php';

$conn = dbHelper::createInstance();

$query="select * from Burn";
$result = $conn->query($query);
$retval = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();

echo json_encode($retval);

?>