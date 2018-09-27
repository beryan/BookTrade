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
  <title>Sell - BookTrade</title>
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

  $bookName = $_POST['bookName'];

  //prevent injection

  $sql = "SELECT bookId FROM Book WHERE bookName = ?";

  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "s", $bookName);
      if((mysqli_stmt_execute($stmt))){
          $result = mysqli_stmt_get_result($stmt);
          while ($row = mysqli_fetch_array($result, MYSQLI_NUM)){
                foreach ($row as $r){
                    $bookId= $r;
                }
          }
      }
      else{
            header("location: error.php");
      }
      mysqli_stmt_close($stmt);
  }

  $userId = $_POST['userId'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $sql = "INSERT into UsedBook ( userId, bookId, price, description ) VALUES (?,?,?,?)";

  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "iiis", $userId, $bookId, $price, $description);
      if(mysqli_stmt_execute($stmt)){
        echo "<div class='container'>Your book is now on sale!</div>";
      }
      else{
        echo "<div class='container'>There was an error!</div>";
      }
      mysqli_stmt_close($stmt);
  }
  ?>

</body>
</html>
