<?php
require '../dbHelper.php';

date_default_timezone_set('GMT');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(500);
    die("HTTP POST Required");  
}

$post_data = file_get_contents('php://input'); // Get Raw Posted Data
$data = json_decode($post_data, true); // Decode it

$conn = dbHelper::createInstance();

$query="select * from CurrentState";
$result = $conn->query($query);

$retval =  array();

if ($result->num_rows > 0) {
    $retval = $result->fetch_assoc();
}

$currentName = $retval["CurrentName"];

if (empty($data)) {  
  $demotionDate = new DateTime($retval["ClockStart24h"]);
  $resetDate = new DateTime($retval["ClockStart72h"]);

  $currentDate = new DateTime(date("Y-m-d H:i:s"));
  $dateDiff = date_diff($demotionDate, $currentDate);
  $resetDateDiff = date_diff($resetDate, $currentDate);

  if (((int)$dateDiff->format('%d')) >= 1) {
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

    $stmt = $conn->prepare("UPDATE CurrentState SET CurrentName = ?, ClockStart24h = NOW()");
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
    }
  } else if (((int)$resetDateDiff->format('%d')) >= 3 && $currentName != "Torvald Takahashi") {
    $currentName = "Ryan";
    $stmt = $conn->prepare("UPDATE CurrentState SET CurrentName = ?, ClockStart24h = NOW(), ClockStart72h = NOW()");
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
    }
  }
} else {
  if ($data["BurnType"] == "Normal") {
    switch ($currentName) {
      case "Ryan":
        $currentName = "Chadrick";
        break;
      case "Chadrick":
        $currentName = "Seabiscuit";
        break;
      case "Seabiscuit":
        $currentName = "Chad";
        break;
      case "Chad":
        $currentName = "Seamus";
        break;
      case "Seamus":
        $currentName = "\"Jason\"";
        break;
      case "\"Jason\"":
        $currentName = "Torvald Takahashi";
        break;        
      default:
        break;
    }
  } else {
    switch ($currentName) {
      case "Ryan":
        $currentName = "Seabiscuit";
        break;
      case "Chadrick":
      case "Seabiscuit":
        $currentName = "Seamus";
        break;
      case "Chad":        
      case "Seamus":
      case "\"Jason\"":
        $currentName = "Torvald Takahashi";
        break;        
      default:
        break;
  }    
}
  $stmt = $conn->prepare("UPDATE CurrentState SET CurrentName = ?, ClockStart24h = NOW()");
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
  }
}

$conn->close();

echo true;

/* Jason TODO:

//Load CurrentState and last burn from tables
Evavluate rules and update the CurrentState table.

*/
?>