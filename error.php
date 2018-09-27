<?php
session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['username'])) { }
else {
  header("location: home.php");
  exit;
}
?>

<!doctype html>
<html  class="book">
<head>
  <title>Error - BookTrade</title>
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
      <a href="login.php" class="button"><span>Sign in</span></a>
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

  <div class="container">
    <p>Error: You must be signed in to use this page!</p>
    <p>Please register <a href="register.php">here</a></p>
  </div>

</body>
</html>
