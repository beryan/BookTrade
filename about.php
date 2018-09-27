<!doctype html>
<html class="book">
<head>
  <title>About - BookTrade</title>
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
      require_once 'connect.php';
      session_start();
      login();
      $conn = OpenCon();
      ?>
    </div>
  </div>

  <div class="topnav" id="myTopnav">
    <a href="home.php"><i class="fas fa-home"></i> Home</a>
    <a href="search.php"><i class="fas fa-search"></i> Search</a>
    <a href="sell.php"><i class="fas fa-book"></i> Sell Books</a>
    <a href="wishlist.php"><i class="fas fa-list"></i> Wishlist</a>
    <a href="chat.php"><i class="fas fa-comments"></i> Messages</a>
    <a href="about.php" class="active"><i class="fas fa-users"></i> About</a>
    <a href="javascript:void(0);" class="icon" onclick="myTopnav()"><i class="fa fa-bars"></i></a>
  </div>

  <div class="about">
    <?php
    $sql = "SELECT Classes.departmentName, count(UsedBook.saleId) from Classes, UsedBook, RequiredBook  where UsedBook.bookId = RequiredBook.bookId and Classes.classId = RequiredBook.classId group by Classes.departmentName";
    $arr = makearr($conn,$sql);
    $handle = mysqli_query($conn, 'SELECT count(distinct Users.userId) from Users, UsedBook where Users.userId = UsedBook.userId');
    $row = mysqli_fetch_row($handle);
    $good = $row[0];
    $handle = mysqli_query($conn, 'SELECT count(userId) from Users');
    $row = mysqli_fetch_row($handle);
    $bad = $row[0] - $good;
    $handle = mysqli_query($conn, 'SELECT count(bookId) from Book');
    $row = mysqli_fetch_row($handle);
    $handle = mysqli_query($conn, 'SELECT count(newId) from NewBooks');
    $row2 = mysqli_fetch_row($handle);
    $badbook = $row[0] - $row2[0];
    $goodbook = $row2[0] - 0;
    ?>
    <p style = "text-decoration: underline;"><strong>WHAT IS BOOKTRADE?</strong></p>
    <p>BookTrade is a textbook trade interface meant for the students of Simon Fraser University. While similar services exist, BookTrade seeks to improve the textbook trade experience by
      using real course data obtained through the SFU Course Outline API. This allows users flexibility in their searches with the ability to use any combination of course information to find their desired book.
      Users can also sell books, create Wishlists, and message sellers.</p>
    <p>Created by Brittany Ryan and Michael Gergely.</p>
    <br>
    <p style = "text-decoration: underline;"><strong>HOW TO USE</strong></p>
    <ol>
      <li>Sign up for an account to gain access to all of BookTrade's features.</li>
      <li>Are you buying or selling a course book?</li>
    </ol>
    <ul>
      <br/>
      <d style = "text-decoration: underline;"><strong>Selling a book</strong></d>
      <li>Visit the "Sell Books" tab.</li>
      <li>From the dropdown menu select the name of the book you're selling then input a price and description.</li>
      <li>Don't see your book? Sometimes a Professsor may choose to use a textbook without actually registering it into the system, help us improve our database by adding it in the lower box.</li>
      <li>Hit the "Add Listing" button. Congratulations! Your book is now on sale! Other users may search for your book and message you if they are interested.</li>
      <li>You may not search for your own listing in the "Search" tab, but you can visit your profile in the top right to view or delete your listings.</li>
      <br/>
      <d style = "text-decoration: underline;"><strong>Buying a Book</strong></d>
      <li>Visit the "Search" tab.</li>
      <li>Input search parameters. The search system is friendly, every entry will simply narrow results when entered. Leave parts blank if you wish for more results to appear and search as much as you want!</li>
      <li>When you've found the book and listing you want, hit the "Add to Wishlist" button to add it to your Wishlist.</li>
      <li>Visit the "Wishlist" tab and click "Message Seller" to get in contact or "Remove from Wishlist" to remove items.</li>
      <li>Visit the "Messages" tab to view all of your ongoing conversations. Messages will appear here in real time!</li>
    </ul>

    <br>
    <p style = "text-decoration: underline;"><strong>INTERESTED IN DATA?</strong> </p>
    <p>The following graphs capture current data within the system through the use of Google's Chart API .</p>
    <div id="piechart" class="chart"></div>
    <div id="piechart2" class="chart"></div>
    <div id="piechart3" class="chart"></div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">

    // Load google charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

     //not working? can't get the elements of the array but can get length?
    var phpdata = <?php echo json_encode($arr); ?>;
    var good = <?php echo json_encode($good); ?>;
    var bad = <?php echo json_encode($bad); ?>;
    var goodbook = <?php echo json_encode($goodbook); ?>;
    var badbook = <?php echo json_encode($badbook); ?>;
    var dataArr = [['Departments', 'Used books']];
    for (i = 0; i < phpdata.length; i++) {
      dataArr.push([phpdata[i]["departmentName"],Number(phpdata[i]["count(UsedBook.saleId)"])]);
    }
    var dataArr2=[['SellingOrNot', 'Amount'],['Selling Books',Number(good)],['Not Selling', bad]];
    var dataArr3=[['UserAdded', 'Amount'],['Added by Users',goodbook],['Originally in Database', badbook]];

    // Draw the chart and set the chart values
    function drawChart() {
      var data = google.visualization.arrayToDataTable(dataArr);
      var data2 = google.visualization.arrayToDataTable(dataArr2);
      var data3 = google.visualization.arrayToDataTable(dataArr3);

      //console.log(phpdata[1]["departmentName"],Number(phpdata[1]["count(UsedBook.saleId)"]));

      var options = {'title':'Books on sale by department'};
      var options2 = {'title':'Percentage of users selling books'};
      var options3 = {'title':'Percentage of user added books'};

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
      chart.draw(data, options);
      var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
      chart.draw(data2, options2);
      var chart = new google.visualization.PieChart(document.getElementById('piechart3'));
      chart.draw(data3, options3);

    }

    </script>

    </p>
    <p><i>Made by SFU students for SFU students.</i></p>
    <p><i>For any inquries please email either beryan@sfu.ca or mgergely@sfu.ca.</i></p>

  </div>

</body>
</html>
