<?php
require_once 'connect.php';
require_once 'functions.php';

$conn = OpenCon();
session_start();
$userId = $_SESSION['user'];
$sendId = $_SESSION['sender'];

$sql ="SELECT * from Messages where (sendId = '$sendId' or recieveId = '$sendId') and messageId in (SELECT messageId from Messages where sendId = '$userId' or recieveId = '$userId')";

$arr = makeArr($conn, $sql);
$arrUsed = array();

echo "<div >";
echo "<table style='width:100%'>";
foreach($arr as $data) {
  if($data['sendId'] == $userId ) {
    echo "<tr >";
    echo "<td >";
    echo "<div class='message darker' >";
    echo "<p >".$data['message']."<span class='time-left'>".$data['timeStamp']."</span></p>";
    echo "</div>";
    echo "</td>";
    echo "</tr>";
  } else {
    echo "<tr>";
    echo "<td>";
    echo "<div class='message'>";
    echo "<p>".$data['message']."<span class='time-right'>".$data['timeStamp']."</span></p>";
    echo "</div>";
    echo "</td>";
    echo "</tr>";
  }
}
echo "</table>";
echo "</div>";
?>
