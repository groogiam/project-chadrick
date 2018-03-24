<?php
require '../dbHelper.php';

$conn = dbHelper::createInstance();

$query="select * from CurrentState";
$result = $conn->query($query);

$retval =  array();

if ($result->num_rows > 0) {
    $retval = $result->fetch_assoc();
}

$conn->close();

echo json_encode($retval);

?>