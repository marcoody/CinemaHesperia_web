<?php

$target_dir = "../images/covers/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$name_file = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 0;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$errore = "<p class=\"error\">Non è un immagine.</p>";


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 0;
  } else {
    $uploadOk = 1;
  }

// Check file size
if ($_FILES["fileToUpload"]["size"] > 3000000) {
  $uploadOk = 2;
}


if ($uploadOk == 0) {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
}
