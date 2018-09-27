<?php
session_start();
$_SESSION = array();
session_destroy();
require_once 'connect.php';
$conn = OpenCon();
$email = $password = "";
$email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST["email"]))) {
        $email_err = 'Please enter your email.';
    } else {
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    if(empty($email_err) && empty($password_err)) {
        $sql = "SELECT email, password FROM Users WHERE email = ?";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION['email'] = $email;
                            header("location: home.php");
                        } else {
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else {
                    $email_err = 'No account found with that username.';
                }
            } else {
                echo "Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

<!doctype html>
<html class="bg">
<head>
  <title>Sign in - BookTrade</title>
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
    <h1>Sign in</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="row">
        <div class="col-25">
          <label for="em">Username:</label>
        </div>
        <div class="col-75">
          <input type="text" id="em" name="email" value="<?php echo $email; ?>" placeholder="Your username...">
          <span class="error"><?php echo $email_err; ?></span>
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="pw">Password:</label>
        </div>
        <div class="col-75">
          <input type="password" id="pw" name="password" placeholder="Your password...">
          <span class="error"><?php echo $password_err; ?></span>
        </div>
      </div>
      <br />
      <div class="row">
        <input type="submit" value="Sign in">
      </div>
      <div class="row">
        <p style="float:right;">Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </form>
  </div>

</body>
</html>
