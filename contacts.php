<?php
require_once 'connect.php';
require_once 'functions.php';

$conn = OpenCon();
session_start();
$userId = $_SESSION['user'];
$sendId = $_SESSION['sender'];

$sql = "SELECT *
FROM Messages, Users
WHERE (sendId = '$userId' and userId = recieveId) or (recieveId = '$userId' and userId = sendId)";

$arr = makeArr($conn, $sql);
$arrUsed = array();

foreach($arr as $data) {
  if((!(in_array($data['sendId'], $arrUsed)) && $data['sendId'] != $userId )) {
    if($data['sendId'] == $sendId) {
      echo "<form action='chat.php' method='post' >
      <input type='hidden' name='sendId' value='{$data['sendId']}'>
      <input type='submit' style='padding-left:5px;pointer-events:none;background-color:#A6192E;color:white;border-bottom:1px solid grey;border-radius:0;margin:0;' class='contact' value='{$data['email']}'>
      </form>";
      array_push($arrUsed, $data['sendId']);
    } else {
    echo "<form action='chat.php' method='post' >
    <input type='hidden' name='sendId' value='{$data['sendId']}'>
    <input type='submit' style='padding-left:5px;background-color:lightgrey;color:black;border-bottom:1px solid grey;border-radius:0;margin:0;' class='contact' value='{$data['email']}'>
    </form>";
    array_push($arrUsed, $data['sendId']);
    }
  }
  if((!(in_array($data['recieveId'],$arrUsed)) && $data['recieveId'] != $userId )) {
    if($data['recieveId'] == $sendId) {
      echo "<form action='chat.php' method='post' >
      <input type='hidden' name='sendId' value='{$data['recieveId']}'>
      <input type='submit' style='padding-left:5px;pointer-events:none;background-color:#A6192E;color:white;border-bottom:1px solid grey;border-radius:0;margin:0;' class='contact' value='{$data['email']}'>
      </form>";
      array_push($arrUsed, $data['recieveId']);
    } else {
     echo "<form action='chat.php' method='post' >
     <input type='hidden' name='sendId' value='{$data['recieveId']}'>
     <input type='submit' style='padding-left:5px;background-color:lightgrey;color:black;border-bottom:1px solid grey;border-radius:0;margin:0;' class='contact' value='{$data['email']}'>
     </form>";
     array_push($arrUsed, $data['recieveId']);
   }
  }
}
?>
