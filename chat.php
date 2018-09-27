<?php
// Initialize the session
session_start();

if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: error.php");
  exit;
}
?>

<!doctype html>
<html>
<head>
  <title>Messages - BookTrade</title>
  <meta charset="UTF-8">
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bookTrade.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
  <script type="text/javascript" src="script.js"></script>
  <script>
    function ajaxCall() {
      $.ajax({
        url: "contacts.php",
        success: (function (result) {
          $("#contacts").html(result);
        })
      })
      $.ajax({
        url: "messages.php",
        success: (function (result) {
          $("#messages").html(result);
        })
      })
    };
    ajaxCall();
    setInterval(ajaxCall, (2 * 1000));
  </script>
</head>
<body class="page">
  <div class="header">
    <h1>BOOKTRADE</h1>
    <div class="login">
      <?php
      require_once "functions.php";
      $username = login();
      ?>
    </div>
  </div>


  <div class="topnav" id="myTopnav">
    <a href="home.php"><i class="fas fa-home"></i> Home</a>
    <a href="search.php"><i class="fas fa-search"></i> Search</a>
    <a href="sell.php"><i class="fas fa-book"></i> Sell Books</a>
    <a href="wishlist.php"><i class="fas fa-list"></i> Wishlist</a>
    <a href="chat.php" class="active"><i class="fas fa-comments"></i> Messages</a>
    <a href="about.php"><i class="fas fa-users"></i> About</a>
    <a href="javascript:void(0);" class="icon" onclick="myTopnav()"><i class="fa fa-bars"></i></a>
  </div>

  <?php
  require_once 'connect.php';
  $conn = OpenCon();

  $sql = "SELECT userId from Users where email = '$username'";
  $userId = getuserId($conn, $sql);

  if (empty($_POST['sendId'])) {
    $sql = "SELECT recieveId
    FROM Messages, Users
    WHERE sendId = '$userId'  and recieveId != '$userId'";

    $arrsent = makeArr($conn, $sql);

    $sql = "SELECT sendId
    FROM Messages, Users
    WHERE sendId != '$userId'  and recieveId = '$userId'";

    $arrrecieved = makeArr($conn, $sql);

    if($arrsent == NULL && $arrrecieved == NULL) {
      echo "<div class='container'>You have no message History! Get started by messaging a seller with our wishlist feature.</div>";
      exit;
    }

    if ($arrsent != NULL) {
      $arr = array_values(array_slice($arrsent, -1))[0];
      $sendId = $arr['recieveId'];
    } else {
      $arr = array_values(array_slice($arrrecieved, -1))[0];
      $sendId = $arr['sendId'];
    }
  } else {
    $sendId = $_POST['sendId'];
  }

  $_SESSION['user'] = $userId;
  $_SESSION['sender'] = $sendId;
  ?>

  <div class="row" style="overflow:auto">
    <div class="contacts" id="contacts"></div>
    <div class="messages" id="messages"></div>
  </div>

  <div class=footer>
    <iframe name="votar" style="display:none;"></iframe>
    <form id ="myForm" action="message-process.php" method="post" target="votar" onsubmit="this.submit(); this.reset(); return false;">
      <?php
      echo "<input type='hidden' name='userId' value='$sendId'>";
      ?>
      <table>
        <tr>
          <td style='width:100%;padding-right:2px;'><input name="message" type="text" maxlength="255" placeholder="Enter your message..."></td>
          <td><input type='submit' value='Send Message'></td>
        </tr>
      </table>
    </form>
  </div>

</body>
</html>
