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

  $semester = 1187;

  $bookName = $_POST['bookName'];
  $bookName = str_replace('"', "", $bookName);
  $bookName = str_replace("'", "", $bookName);
  $bookName = strip_tags($bookName);
  $bookName = preg_replace("/&#?[a-z0-9]+;/i","",$bookName);
  $departmentName = $_POST['departmentName'];
  $classNumber = $_POST['classNumber'];
  $section = $_POST['section'];
  $instructor = $_POST['instructor'];
  $userId = $_POST['userId'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $sql = "SELECT classId FROM Classes WHERE departmentName = '$departmentName' and classNumber = '$classNumber'";
  if ($result = mysqli_query($conn, $sql)){
    if (mysqli_num_rows($result) == 0) {
          echo "<div class='container'>That combination of department and class number does not exist!</div>";
          exit;
    }
  }
  $class = makeArr($conn, $sql);
  $classId = $class[0]['classId'];

  $sql = "SELECT * FROM RequiredBook where classId = '$classId'";
  if ($result = mysqli_query($conn, $sql)){
    if (!mysqli_num_rows($result) == 0) {
          $arr = makeArr($conn, $sql);
          $bookId = $arr[0]['bookId'];
          $sql = "SELECT bookName FROM Book where bookId = '$bookId'";
          $arr = makeArr($conn, $sql);
          $bookN = $arr[0]['bookName'];
          echo "<div class='container'>We already have that classes' textbook:<br /><br />\"".$bookN."\"</div>";
          exit;
    }
  }


  $sql = "INSERT into Book (bookName) VALUES (?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "s", $bookName);
      if(!mysqli_stmt_execute($stmt)){
        header("error.php");
      }
      $bookId = mysqli_insert_id($conn);
      mysqli_stmt_close($stmt);
  }


  $sql = "INSERT into RequiredBook (bookId, classId) VALUES (?, ?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "ii", $bookId, $classId);
      if(!mysqli_stmt_execute($stmt)){
        header("error.php");
      }
      mysqli_stmt_close($stmt);
  }

  $sql = "INSERT into Instructor (name) VALUES (?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "s", $instructor);
      if(!mysqli_stmt_execute($stmt)){
        header("error.php");
      }
      $instructorId = mysqli_insert_id($conn);
      mysqli_stmt_close($stmt);
  }


  $sql = "INSERT into Offering ( classId, instructorId, semesterId, section ) VALUES (?,?,?,?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "iiis", $classId, $instructorId, $semester, $section);
      if(!mysqli_stmt_execute($stmt)){
        header("error.php");
      }
      $offeringId = mysqli_insert_id($conn);
      mysqli_stmt_close($stmt);
  }


  $sql = "INSERT into UsedBook ( userId, bookId, price, description ) VALUES (?,?,?,?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "iiis", $userId, $bookId, $price, $description);
      if(mysqli_stmt_execute($stmt)){
        $saleId = mysqli_insert_id($conn);
        echo "<div class='container'>Your book is now on sale!</div>";
      }
      else{
        echo "<div class='container'>There was an error!</div>";
      }
      mysqli_stmt_close($stmt);
  }


  $sql = "INSERT into NewBooks ( newBookId, newOfferingId, newUserId, newSaleId ) VALUES (?,?,?,?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "iiii", $bookId, $offeringId, $userId, $saleId);
      if(mysqli_stmt_execute($stmt)){
      }
      else{
      }
      mysqli_stmt_close($stmt);
  }

  ?>

</body>
</html>
