<?php
// Initialize the session
session_start();

if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: error.php");
  exit;
}
?>

<!doctype html>
<html class = "book">
<head>
  <title>Wishlist - BookTrade</title>
  <meta charset="UTF-8">
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bookTrade.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script type="text/javascript" src="script.js"></script>
</head>
<body>
<script>
  function remove() {
      alert("Entry was removed from wishlist! Please refresh the page");
  }
</script>

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
    <a href="wishlist.php" class="active"><i class="fas fa-list"></i> Wishlist</a>
    <a href="chat.php"><i class="fas fa-comments"></i> Messages</a>
    <a href="about.php"><i class="fas fa-users"></i> About</a>
    <a href="javascript:void(0);" class="icon" onclick="myTopnav()"><i class="fa fa-bars"></i></a>
  </div>

  <?php
  $arr = array();
  require_once 'connect.php';

  $conn = OpenCon();

  $sql = "SELECT userId from Users where email = '$username'";
  $userId = getUserId($conn, $sql);

  $sql ="SELECT Book.bookName, Users.email, UsedBook.userId, UsedBook.price, UsedBook.description, UsedBook.timeStamp, WishList.saleId
  FROM Book, Users, UsedBook, WishList
  where UsedBook.saleId = WishList.saleId and UsedBook.bookId = Book.bookId and UsedBook.userId = Users.userId and WishList.userId = '$userId'";
  if (mysqli_query($conn, $sql)){
    $arr = makeArr($conn, $sql);
    }

   if ($arr ==null) {
        echo "<div class='container'>Your wishlist is empty! Add some entries with our search feature.</div>";
      }

   foreach($arr as $data){

        echo "<div class='results' >";
        echo "<div class='section'>".$data['bookName']."</div>";
        echo "<div class='card'>Seller: ".$data['email']."<br>"
          ."Price: $".$data['price']."<br>"
          ."Description: ".$data['description']."<br>"
          ."Timestamp: ".$data['timeStamp'].
          ".<iframe name='votar' style='display:none;'></iframe>
          <form id ='myForm' action='wishlist-process.php' method='post' target='votar' onsubmit= 'this.submit(); remove();'>
              <input type='hidden' name='saleId' value='{$data['saleId']}'>
              <input type='hidden' name='userId' value='$userId'>
              <input type='submit' class='submit' value='Remove from Wishlist'>
          </form>".
          "<form action='message.php' method='post' >
            <input type='hidden' name='userId' value='{$data['userId']}'>
            <input type='hidden' name='email' value='{$data['email']}'>
            <input type='submit' class='submit' style='margin-right:5px' value='Message Seller'>
          </form>"
          ."</div>"
          ."</div>";
    }
  ?>

</body>
</html>
