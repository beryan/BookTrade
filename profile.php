<?php
// Initialize the session
session_start();

if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: error.php");
  exit;
}
?>

<!doctype html>
<html class="bg">
<head>
  <title>Account - BookTrade</title>
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
       alert("Sale was unlisted! Please refresh the page");
   }
 </script>
  <div class="header">
    <h1>BOOKTRADE</h1>
    <div class="login">
      <?php
      require_once "functions.php";
      $username = $_SESSION['email'];
      echo '<a href="logout.php" class="button"><span>Sign out</span></a>';
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
  $userId = getuserId($conn, $sql);

  $sql = "SELECT * from Messages where sendId = '$userId' or recieveId = '$userId'";

  $arr = makeArr($conn, $sql);
  $arrUsed = array();

  $sql ="SELECT Users.email, Book.bookName, UsedBook.saleId, UsedBook.userId, UsedBook.price, UsedBook.description, UsedBook.timeStamp
  FROM UsedBook, Book, Users
  WHERE UsedBook.bookId = Book.bookId and UsedBook.userId = '$userId' and UsedBook.userId = Users.userId";
  $result = mysqli_query($conn, $sql);
  $arr = array();
  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      $arr[] = $row;
    }
  }

  echo "<div class='results'>";
  echo "<div class='section'>Your Account Info</div>";
  echo "<div class='card'>Username: ".$username."</div>";
  echo "</div>";

  echo "<div class='results'>";
  echo "<div class='section'>Your Listings</div>";
  if ($arr == null) {
    echo "<div class='card'>You have no listings! Add a listing with our sell books feature.</div>";
  } else {
    foreach($arr as $data){
      echo "<div class='card'>Book Name: ".$data['bookName']."<br>"
      ."Price: $".$data['price']."<br>"
      ."Description: ".$data['description']."<br>"
      ."Timestamp: ".$data['timeStamp'].
      ".<iframe name='votar' style='display:none;'></iframe>
      <form id ='myForm' action='unlist.php' method='post' target='votar' onsubmit= 'this.submit();remove();'>
      <input type='hidden' name='saleId' value='{$data['saleId']}'>
      <input type='submit' class='submit' value='Unlist'></input>
      </form>".
      "</div>";
    }
  }
  echo "</div>";
  ?>

</body>
</html>
