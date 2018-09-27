<?php
// Initialize the session
session_start();

if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: error.php");
  exit;
}
?>

<!doctype html>
<html class="book">
<head>
  <title>Message Seller - BookTrade</title>
  <meta charset="UTF-8">
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bookTrade.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script type="text/javascript" src="script.js"></script>
</head>
<body>

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
    <a href="chat.php"><i class="fas fa-comments"></i> Messages</a>
    <a href="about.php"><i class="fas fa-users"></i> About</a>
    <a href="javascript:void(0);" class="icon" onclick="myTopnav()"><i class="fa fa-bars"></i></a>
  </div>

  <?php
  require_once 'connect.php';

  $conn = OpenCon();
  $sql = "SELECT userId from Users where email = '$username'";

  $sendId = getuserId($conn, $sql);
  $recieveId = $_POST['userId'];
  $message = $_POST['message'];

  if ($recieveId == $sendId){
    echo "<div class='container'>You cannot message yourself!</div>";
    exit;
  }

  if($message == null){
    echo "<div class='container'>Your message was empty and was not sent!</div>";
    exit;
  }

  $sql = "INSERT into Messages ( sendId, recieveId, message ) VALUES (?,?,?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "iis", $sendId, $recieveId, $message);
      if(mysqli_stmt_execute($stmt)){
        echo "<div class='container'>Your message was sent! Please visit the messages tab to view your messages.</div>";
      }
      else{
        echo "<div class='container'>There was an error!</div>";
      }
      mysqli_stmt_close($stmt);
  }
  ?>

  </body>
 </html>
