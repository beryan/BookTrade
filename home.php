<!doctype html>
<html class="bg">
<head>
  <title>BookTrade</title>
  <meta charset="UTF-8">
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="bookTrade.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script type="text/javascript" src="script.js"></script>
  <style>
    .mySlides {display: none; margin: 0;}
    img {vertical-align: middle; object-fit: cover;}

    .slideshow-container {
      max-width: 1000px;
      position: relative;
      margin: auto;
      margin-top: 10px;
    }

    .prev, .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      padding: 16px;
      margin-top: -22px;
      color: white;
      font-weight: bold;
      font-size: 18px;
      transition: 0.6s ease;
      border-radius: 0 3px 3px 0;
    }

    .next {
      right: 0;
      border-radius: 3px 0 0 3px;
    }

    .prev:hover, .next:hover {
      background-color: rgba(0,0,0,0.8);
    }

    .text {
      color: white;
      font-size: 30px;
      padding: 15px;
      position: absolute;
      bottom: 8px;
      width: 100%;
      text-align: center;
      font-family: georgia;
      text-shadow: 2px 2px 4px rgba(0,0,0,1);
    }

    .numbertext {
      color: #f2f2f2;
      font-size: 12px;
      padding: 8px 12px;
      position: absolute;
      top: 0;
    }

    .dot {
      cursor: pointer;
      height: 15px;
      width: 15px;
      margin: 0 2px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.6s ease;
    }

    .active, .dot:hover {
      background-color: #111;
    }

    .fade {
      -webkit-animation-name: fade;
      -webkit-animation-duration: 1.5s;
      animation-name: fade;
      animation-duration: 1.5s;
    }

    @-webkit-keyframes fade {
      from {opacity: .4}
      to {opacity: 1}
    }

    @keyframes fade {
      from {opacity: .4}
      to {opacity: 1}
    }

    @media only screen and (max-width: 300px) {
      .prev, .next, .text {font-size: 11px}
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>BOOKTRADE</h1>
    <div class="login">
      <?php
      require_once "functions.php";
      session_start();
      login();
      ?>
    </div>
  </div>

  <div class="topnav" id="myTopnav">
    <a href="home.php" class="active"><i class="fas fa-home"></i> Home</a>
    <a href="search.php"><i class="fas fa-search"></i> Search</a>
    <a href="sell.php"><i class="fas fa-book"></i> Sell Books</a>
    <a href="wishlist.php"><i class="fas fa-list"></i> Wishlist</a>
    <a href="chat.php"><i class="fas fa-comments"></i> Messages</a>
    <a href="about.php"><i class="fas fa-users"></i> About</a>
    <a href="javascript:void(0);" class="icon" onclick="myTopnav()"><i class="fa fa-bars"></i></a>
  </div>

  <div class="slideshow-container">
    <div class="mySlides fade">
      <div class="numbertext">1 / 3</div>
      <img src="search.jpg" style="width:100% ;height:100%">
      <div class="text">Find used books!</div>
    </div>

    <div class="mySlides fade">
      <div class="numbertext">2 / 3</div>
      <img src="sellbook.jpg" style="width:100%;height:100%">
      <div class="text">Sell your old course books!</div>
    </div>

    <div class="mySlides fade">
      <div class="numbertext">3 / 3</div>
      <img src="contact.jpg" style="width:100% ;height:100%">
      <div class="text">Talk with other students!</div>
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>
  <br>

  <div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
  </div>

  <script>
    var slideIndex = 1;
    showSlides(slideIndex);
  </script>

</body>
</html>
