<?php

function login() {
	if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
		echo '<a href="login.php" class="button"><span>Sign in</span></a>';
  } else {
		$username = $_SESSION['email'];
    echo '<a href="profile.php" class="button"><i class="fas fa-user-graduate"></i> My Account</a>';
    echo '<a href="logout.php" class="button"><span>Sign out</span></a>';
		return $username;
  }
}

function makeArr($conn,$sql){
	$result = mysqli_query($conn, $sql);
	$arr = array();
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$arr[] = $row;
		}
}
return $arr;
}

function getUserId($conn, $sql){
	$result = mysqli_query($conn, $sql);
	$arr = array();
	if(mysqli_num_rows($result) > 0) {
	 while($row = mysqli_fetch_assoc($result)) {
		 $arr[] = $row;
	 }
	}
	return $arr[0]['userId'];
}

function getSaleId($arr){
	  $array = array();
	  foreach($arr as $data){
			  array_push($array, $data['saleId']);
		}
		return $array;
}
?>
