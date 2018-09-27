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
    if((!(in_array($data['sendId'],$arrUsed)) && $data['sendId'] != $userId )) {
      echo "<form action='chat.php' method='post' >
      <input type='hidden' name='sendId' value='{$data['sendId']}'>
      <input type='submit' value='{$data['email']}'>
      </form>";
      array_push($arrUsed, $data['sendId']);
    }
    if((!(in_array($data['recieveId'],$arrUsed)) && $data['recieveId'] != $userId )) {
       echo "<form action='chat.php' method='post' >
       <input type='hidden' name='sendId' value='{$data['recieveId']}'>
       <input type='submit' value='{$data['email']}'>
       </form>";
       array_push($arrUsed, $data['recieveId']);
     }
  }

  $sql ="SELECT * from Messages where (sendId = '$sendId' or recieveId = '$sendId') and messageId in (SELECT messageId from Messages where sendId = '$userId' or recieveId = '$userId')";

  $arr = makeArr($conn, $sql);
  $arrUsed = array();

  echo "<div>";
  foreach($arr as $data) {
    if($data['sendId'] == $userId ) {
      echo "<div style='float:right; background-color:grey'>".
      $data['message'] ."<br>". $data['timeStamp']."</div";
      echo"<br>";
    } else {
      echo "<div style='float:right;'>".
      $data['message'] ."<br>". $data['timeStamp']."</div";
      echo"<br>";
    }
  }
  echo "</div>";
?>
