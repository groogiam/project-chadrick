<?php
require '../dbHelper.php';

$post_data = file_get_contents('php://input'); // Get Raw Posted Data
$data = json_decode($post_data, true); // Decode it

$conn = dbHelper::createInstance();

$stmt = $conn->prepare("insert into Burn values(DEFAULT, ?, ?, ?, now(), ?);");
$stmt->bind_param("ssss", $data["Burnee"], $data["BurnType"], $data["Description"], $_SERVER['REMOTE_ADDR']);

try {
    $stmt->execute();
}catch(Exception $e){
    echo $e;
    http_response_code(500);
    echo false;
    return;
}finally{
    $stmt->close();
    $conn->close();
}



echo true;

?>