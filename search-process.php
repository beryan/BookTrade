<!doctype html>
<html class="book">
<head>
  <title>Search - BookTrade</title>
  <meta charset="UTF-8">
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bookTrade.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script type="text/javascript" src="script.js"></script>
  <style>
    table, thead, tbody{
      width: 100%;
    }
    @media screen and (max-width: 900px) {
      table, thead, tbody, th, td, tr {
    		display: block;
        width: 100%;
    	}
    }
  </style>
</head>
<body>

<script>

function remove() {
    alert("Entry was removed from wishlist! Please refresh the page");
}

function add(){
  alert("Entry was added to wishlist! Please refresh the page");
}

</script>

  <div class="header">
    <h1>BOOKTRADE</h1>
    <div class="login">
      <?php
      require_once "functions.php";
      session_start();
      $username = login();
      ?>
    </div>
  </div>

  <div class="topnav" id="myTopnav">
    <a href="home.php"><i class="fas fa-home"></i> Home</a>
    <a href="search.php" class="active"><i class="fas fa-search"></i> Search</a>
    <a href="sell.php"><i class="fas fa-book"></i> Sell Books</a>
    <a href="wishlist.php"><i class="fas fa-list"></i> Wishlist</a>
    <a href="chat.php"><i class="fas fa-comments"></i> Messages</a>
    <a href="about.php"><i class="fas fa-users"></i> About</a>
    <a href="javascript:void(0);" class="icon" onclick="myTopnav()"><i class="fa fa-bars"></i></a>
  </div>

  <div class="searchBar">
    <form action="search-process.php" method="post">
      <table>
        <tr>
          <td><input name="departmentName" type="text" placeholder="Department"></td>
          <td><input name="classNumber" type="text" placeholder="Class Number"></td>
          <td><input name="section" type="text" placeholder="Section"></td>
          <td><input name="bookName" type="text" placeholder="Book Name"></td>
          <td><input type="submit" style='width:100%' value="Search"></td>
        </tr>
    </table>
    </form>
  </div>

  <?php
  require_once 'connect.php';
  $conn = OpenCon();
  $departmentName = $_POST['departmentName'];
  $classNumber = $_POST['classNumber'];
  $section = $_POST['section'];
  $bookName= $_POST['bookName'];
  $semester = 1187;


if ($_SESSION != null){

  $sql = "SELECT userId from Users where email = '$username'";
  $userId = getuserId($conn, $sql);

  $sql ="SELECT Book.bookName, Users.email, UsedBook.userId, UsedBook.price, UsedBook.description, UsedBook.timeStamp, WishList.saleId
  FROM Book, Users, UsedBook, WishList
  where UsedBook.saleId = WishList.saleId and UsedBook.bookId = Book.bookId and UsedBook.userId = Users.userId and WishList.userId = '$userId'";
  $wishList = makeArr($conn, $sql);
  $wishList = getSaleId($wishList);

  $sql = "SELECT DISTINCT Offering.offeringId, UsedBook.saleId, UsedBook.bookId, UsedBook.price,
  UsedBook.timeStamp, Book.bookName, Classes.classNumber, Instructor.name,
  Classes.departmentName, Offering.section, Users.email, UsedBook.description
  FROM UsedBook, Book, Classes, Offering, Instructor, RequiredBook, Users
  WHERE UsedBook.bookId = Book.bookId
  and Classes.classId = RequiredBook.classId and Book.bookId = RequiredBook.bookId
  and Classes.classId = Offering.classId and Offering.instructorId = Instructor.instructorId
  and Offering.semesterId = $semester and UsedBook.userId = Users.userId and UsedBook.userId != $userId
  and Classes.departmentName like ? and Classes.classNumber like ?
  and Book.bookName like ? and Offering.section like ?";


$arr = array();

$param1 = "$departmentName%";
$param2 = "$classNumber%";
$param3 = "$bookName%";
$param4 = "$section%";

if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "ssss", $param1, $param2, $param3, $param4);
    if((mysqli_stmt_execute($stmt))){
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
      		while($row = mysqli_fetch_assoc($result)){
      			$arr[] = $row;
      		}
        }
    }
  }
  if ($arr == null){
    echo '<div class="instructions">';
    echo "<p> Sorry there were no results :( </p>";
    echo "<p> Try modifying your query! </p>"."</div>";
    exit;
  }

  rsort($arr);
  $arrUsed = array();
  $arrUsedSale = array();
}
else{
  $sql = "SELECT DISTINCT Offering.offeringId, UsedBook.saleId, UsedBook.bookId, UsedBook.price,
  UsedBook.timeStamp, Book.bookName, Classes.classNumber, Instructor.name,
  Classes.departmentName, Offering.section, Users.email, UsedBook.description
  FROM UsedBook, Book, Classes, Offering, Instructor, RequiredBook, Users
  WHERE UsedBook.bookId = Book.bookId
  and Classes.classId = RequiredBook.classId and Book.bookId = RequiredBook.bookId
  and Classes.classId = Offering.classId and Offering.instructorId = Instructor.instructorId
  and Offering.semesterId = $semester and UsedBook.userId = Users.userId
  and Classes.departmentName like ? and Classes.classNumber like ?
  and Book.bookName like ? and Offering.section like ?";


$arr = array();

$param1 = "$departmentName%";
$param2 = "$classNumber%";
$param3 = "$bookName%";
$param4 = "$section%";

if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "ssss", $param1, $param2, $param3, $param4);
    if((mysqli_stmt_execute($stmt))){
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
      		while($row = mysqli_fetch_assoc($result)){
      			$arr[] = $row;
      		}
        }
    }
  }

  if ($arr == null){
    if ($arr == null){
      echo '<div class="instructions">';
      echo "<p> Sorry there were no results :( </p>";
      echo "<p> Try modifying your query! </p>"."</div>";
      exit;
    }
  }

  rsort($arr);
  $arrUsed = array();
  $arrUsedSale = array();

}
if($_SESSION ==null){
  foreach($arr as $data) {

    if(!(in_array($data['offeringId'],$arrUsed))) {
      echo "</div>";
      echo "<div class='results'>";
      echo "<div class='section'>"
        .$data['departmentName']." ".$data['classNumber']." ".$data['section']."</br>"
        ."Book Name: ".$data['bookName']."<br>"
        ."Instructor: ".$data['name']."<br>"
        ."</div>";
      echo "<div class='card'>"
        ."Seller: ". $data['email']."<br>"
        ."Price: $".$data['price']."<br>"
        ."Description: ".$data['description']."<br>"
        ."Timestamp: ".$data['timeStamp'].
        "<form action='info-process.php' method='post' >
          <input type='hidden' name='saleId' value='{$data['saleId']}'>
          <input type='submit' class='submit' value='Add to Wishlist'>
        </form>"
        ."</div>";
      array_push($arrUsed, $data['offeringId']);
      unset($arrUsedSale); // break references
      $arrUsedSale = array();
      array_push($arrUsedSale, $data['saleId']);
    }

    if ((in_array($data['offeringId'],$arrUsed))&&(!(in_array($data['saleId'],$arrUsedSale)))) {
      echo "<div class='card'>"
        ."Seller: ". $data['email']."<br>"
        ."Price: $".$data['price']."<br>"
        ."Description: ".$data['description']."<br>"
        ."Timestamp: ".$data['timeStamp'].
       "<form action='info-process.php' method='post' >
         <input type='hidden' name='saleId' value='{$data['saleId']}'>
         <input type='submit' class='submit' value='Add to Wishlist'>
       </form>"
       ."</div>";
      array_push($arrUsedSale, $data['saleId']);
    }
  }
}

else{

  foreach($arr as $data) {

    if(!(in_array($data['offeringId'],$arrUsed))) {
      echo "</div>";
      echo "<div class='results'>";
      echo "<div class='section'>"
        .$data['departmentName']." ".$data['classNumber']." ".$data['section']."</br>"
        ."Book Name: ".$data['bookName']."<br>"
        ."Instructor: ".$data['name']."<br>"
        ."</div>";
      echo "<div class='card'>"
        ."Seller: ". $data['email']."<br>"
        ."Price: $".$data['price']."<br>"
        ."Description: ".$data['description']."<br>"
        ."Timestamp: ".$data['timeStamp'];

        if(in_array($data['saleId'],$wishList)){
        echo   ".<iframe name='votar' style='display:none;'></iframe>
        <form id ='myForm' action='wishlist-process.php' method='post' target='votar' onsubmit= 'this.submit();remove();'>
            <input type='hidden' name='saleId' value='{$data['saleId']}'>
            <input type='hidden' name='userId' value='$userId'>
            <input type='submit' class='submit' value='Remove from Wishlist'>
          </form>";
        }

        else{
        echo ".<iframe name='votar' style='display:none;'></iframe>
        <form id ='myForm' action='info-process.php' method='post' target='votar' onsubmit= 'this.submit();add();'>
          <input type='hidden' name='saleId' value='{$data['saleId']}'>
          <input type='submit' class='submit' value='Add to Wishlist'>
        </form>";
       }
       echo "</div>";
      array_push($arrUsed, $data['offeringId']);
      unset($arrUsedSale); // break references
      $arrUsedSale = array();
      array_push($arrUsedSale, $data['saleId']);
    }

    if ((in_array($data['offeringId'],$arrUsed))&&(!(in_array($data['saleId'],$arrUsedSale)))) {
      echo "<div class='card'>"
        ."Seller: ". $data['email']."<br>"
        ."Price: $".$data['price']."<br>"
        ."Description: ".$data['description']."<br>"
        ."Timestamp: ".$data['timeStamp'];

       if(in_array($data['saleId'],$wishList)){
         echo  ".<iframe name='votar' style='display:none;'></iframe>
         <form id ='myForm' action='wishlist-process.php' method='post' target='votar' onsubmit= 'this.submit();remove();'>
           <input type='hidden' name='saleId' value='{$data['saleId']}'>
           <input type='hidden' name='userId' value='$userId'>
           <input type='submit' class='submit' value='Remove from Wishlist'>
         </form>";
       }

       else{
       echo ".<iframe name='votar' style='display:none;'></iframe>
       <form id ='myForm' action='info-process.php' method='post' target='votar' onsubmit= 'this.submit();add();'>
         <input type='hidden' name='saleId' value='{$data['saleId']}'>
         <input type='submit' class='submit' value='Add to Wishlist'>
       </form>";
     }
      echo "</div>";
      array_push($arrUsedSale, $data['saleId']);
    }
  }
}
  ?>

</body>
</html>
