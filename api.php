<?php

include "connect.php";
include "functions.php";
$conn = OpenCon();

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

if (!empty($_GET['year']) && !empty($_GET['term'])) {

  //four digits, first = 21st century, middle 2 = year, last = semester
  //1 spring, 4 summer, 7 fall
  //eg. 1097 = fall 2009

  //GET {base-url}?{year}/{term}
  $api_url = 'http://www.sfu.ca/bin/wcm/course-outlines?'
  .$_GET['year'].'/'
  .$_GET['term'];

  $departments_json = file_get_contents($api_url);
  $departments_array = json_decode($departments_json, true);

  $text = array();
  $value = array();
  $course = array();
  $offering_text = array();
  $offering_value = array();
  $semester = 1187;
  $sql ="INSERT into Semester (semesterId) VALUES (?)";
  if($stmt = mysqli_prepare($conn, $sql)){
      mysqli_stmt_bind_param($stmt, "i", $semester);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
  }

 foreach ($departments_array as $data0) {
    //insert department into database
    $sql ="INSERT into Departments ( departmentName ) VALUES (?)";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $data0['text']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    array_push($value, $data0['value']);
  }


  foreach ($value as $data) {
    //GET {base-url}?{year}/{term}/{department}
    $api_url = 'http://www.sfu.ca/bin/wcm/course-outlines?'
    .$_GET['year'].'/'
    .$_GET['term'].'/'
    .$data;
    //$gooddata = strtoupper($data);
    //sfu "special" exceptionsz
    if ($data == 'bot' || $data =='ddp' || $data =='devs') {
        continue;
    }

    $courses_json = file_get_contents($api_url);
    $courses_array = json_decode($courses_json, true);
    $gooddata = strtoupper($data);

    foreach ($courses_array as $data1){
      $sql ="INSERT into Classes ( classNumber, departmentName ) VALUES (?, ?)";
      if($stmt = mysqli_prepare($conn, $sql)){
          mysqli_stmt_bind_param($stmt, "ss", $data1['text'], $gooddata);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
      }
     array_push($course, $data1['value']);
    }

    foreach ($course as $data2) {
      //GET {base-url}?{year}/{term}/{department}/{courseNumber}
      $api_url = 'http://www.sfu.ca/bin/wcm/course-outlines?'
      .$_GET['year'].'/'
      .$_GET['term'].'/'
      .$data.'/'
      .$data2;

      //sfu "special" exceptions
      if (($data == 'eco' && $data2 == '630') || ($data == 'econ' && $data2 == '899') || ($data == 'econ' && $data2 == '999')) {
          continue;
      }

      $offerings_json = file_get_contents($api_url);
      $offerings_array = json_decode($offerings_json, true);

      foreach ($offerings_array as $data3) {
        array_push($offering_value, $data3['value']);
      }
      foreach ($offering_value as $data4) {
         //GET {base-url}?{year}/{term}/{department}/{courseNumber}/{courseSection}
         $api_url = 'http://www.sfu.ca/bin/wcm/course-outlines?'
         .$_GET['year'].'/'
         .$_GET['term'].'/'
         .$data.'/'
         .$data2.'/'
         .$data4;


         $info_json = file_get_contents($api_url);
         $info_array = json_decode($info_json, true);


         //special exceptions for SFU API
         if(!(isset($info_array['requiredText']))){
           continue;
         }

         $instructor = $info_array['instructor'][0]['name'];
         if($instructor == null){
           continue;
         }


         $book = $info_array['requiredText'][0]['details'];


         if (get_string_between($book, '<em>','</em>') == null){
           $book_name = $book;
         }
         else{
           $book_name = get_string_between($book, '<em>','</em>');
         }


        if((strlen($book_name) > 250)){
          continue;
        }


         echo "<br></br>";
         echo $data;
         echo "<br></br>";
         echo $data2;
         echo "<br></br>";
         echo $data4;
         echo "<br></br>";
         echo $instructor;
         echo "<br></br>";
         echo $book;
         echo "<br></br>";
         echo $book_name;
         echo "<br></br>";

         $book_name = str_replace('"', "", $book_name);
         $book_name = str_replace("'", "", $book_name);
         $book_name = strip_tags($book_name);
         $book_name = preg_replace("/&#?[a-z0-9]+;/i","",$book_name);


         $sql ="INSERT into Book (bookName) VALUES (?)";
         if($stmt = mysqli_prepare($conn, $sql)){
             mysqli_stmt_bind_param($stmt, "s", $book_name);
             mysqli_stmt_execute($stmt);
             mysqli_stmt_close($stmt);
         }

         $sql = "SELECT bookId from Book where bookName = '$book_name'";
         $arr = makeArr($conn, $sql);
         $book_id = $arr[0]['bookId'];

         $sql ="INSERT into Instructor (name) VALUES (?)";
         if($stmt = mysqli_prepare($conn, $sql)){
             mysqli_stmt_bind_param($stmt, "s", $instructor);
             mysqli_stmt_execute($stmt);
             mysqli_stmt_close($stmt);
         }

         $sql = "SELECT instructorId from Instructor where Instructor.name = '$instructor'";
         $arr = makeArr($conn, $sql);
         print_r($arr);
         $instructor_id = $arr[0]['instructorId'];

         $class_name = strtoupper("$data2");
         $department_name = strtoupper("$data");
         $section = strtoupper("$data4");

         $sql = "SELECT classId from Classes where (Classes.classNumber = '$class_name' and Classes.departmentName = '$department_name' ) ";
         $arr = makeArr($conn, $sql);
         print_r($arr);
         $class_id = $arr[0]['classId'];

         $sql ="INSERT into Offering (classId, instructorId, semesterId, section) VALUES (?, ?, ?, ?)";
         $stmt = mysqli_prepare($conn, $sql);
         mysqli_stmt_bind_param($stmt, "iiis", $class_id, $instructor_id, $semester, $section);
         mysqli_stmt_execute($stmt);
         mysqli_stmt_close($stmt);

         $sql ="INSERT into RequiredBook (bookId, classId) VALUES (?, ?)";
         $stmt = mysqli_prepare($conn, $sql);
         mysqli_stmt_bind_param($stmt, "ii", $book_id, $class_id);
         mysqli_stmt_execute($stmt);
         mysqli_stmt_close($stmt);


      }
      unset($offering_value);
      $offering_value = array();
    }
    unset($course);
    $course = array();
  }
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Populate Booktrade Database</title>
</head>
<body>

<form action="" method="get">
    <input type="text" placeholder="year" value="2018" name="year"/>
    <input type="text" placeholder="term" value="fall" name="term"/>
    <button type="submit">Submit</button>
</form>

</body>
</html>
