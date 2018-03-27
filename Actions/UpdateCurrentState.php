<?php
require '../dbHelper.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(500);
    die("HTTP POST Required");
}

$conn = dbHelper::createInstance();

$query="select * from CurrentState";
$result = $conn->query($query);

$retval =  array();

if ($result->num_rows > 0) {
    $retval = $result->fetch_assoc();
}

$currentName = $retval["CurrentName"];
$demotionDate = new DateTime($retval["ClockStart24h"]);
$resetDate = new DateTime($retval["ClockStart72h"]);

$currentDate = new DateTime(date("Y-m-d H:i:s"));
$dateDiff = date_diff($currentDate, $demotionDate);

if (((int)$resetDate->format('w')) >= 1) {
    switch ($currentName) {
      case "Torvald Takahashi":
          $currentName = "\"Jason\"";
          break;
      case "\"Jason\"":
      case "Seamus":
          $currentName = "Chad";
          break;
      case "Chad":
      case "Seabiscuit":
          $currentName = "Chadrick";
          break;
      default:
          $currentName = "Ryan";
  }
}

$query = "DELETE FROM CurrentState";
$result = $conn->query($query);

if ($result) {
  $stmt = $conn->prepare("INSERT INTO CurrentState (CurrentName, ClockStart24h) VALUES (?, NOW())");
  $stmt->bind_param("s", $currentName);

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
} else {
  $conn->close();
  echo false;
}

echo true;

/* Jason TODO:

//Load CurrentState and last burn from tables
Evavluate rules and update the CurrentState table.

*/
?>