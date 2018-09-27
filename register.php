<?php
session_start();
$_SESSION = array();
session_destroy();
require_once 'connect.php';
$conn = OpenCon();
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter a email.";
    } else {
        $sql = "SELECT email FROM Users WHERE email = ?";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);

            if(mysqli_stmt_execute($stmt)) {

                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    if(empty(trim($_POST['password']))) {
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST['password'])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }

    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = 'Please confirm password.';
    } elseif(strlen(trim($_POST['confirm_password'])) < 6) {
        $confirm_password_err = "Password must have atleast 6 characters.";
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO Users (email, password) VALUES (?, ?)";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if(mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
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
  <title>Register - BookTrade</title>
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
    <h1>Register</h1>
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
      <div class="row">
        <div class="col-25">
          <label for="cp">Confirm Password:</label>
        </div>
        <div class="col-75">
          <input type="password" id="cp" name="confirm_password" placeholder="Confirm password...">
          <span class="error"><?php echo $confirm_password_err; ?></span>
        </div>
      </div>
      <br />
      <div class="row">
        <input type="reset" value="Reset">
        <input type="submit" value="Register">
      </div>
    </form>
  </div>

</body>
</html>
