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

  $saleId = $_POST['saleId'];

  $sql = "DELETE from UsedBook where saleId ='$saleId'";

  if ($conn->query($sql) === TRUE) {
    echo "<div class='container'>Record Removed</div>";
  } else {
    $sql = "DELETE from WishList where saleId ='$saleId'";

    if ($conn->query($sql) === TRUE) {
      $sql = "DELETE from UsedBook where saleId ='$saleId'";
      if ($conn->query($sql) === TRUE) {
        echo "<div class='container'>Record Removed</div>";
      }
      else {
        echo "<div class='container'>There was an error!</div>";
      }
    }
    else {
      echo "<div class='container'>There was an error!</div>";
    }
  }
  ?>

</body>
</html>
