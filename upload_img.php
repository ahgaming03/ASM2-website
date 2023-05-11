<?php
// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Get the image file
    $image = $_FILES["image"]["tmp_name"];
    $imageName = $_FILES["image"]["name"];
    $imageType = $_FILES["image"]["type"];
    $imageSize = $_FILES["image"]["size"];
  
    // Check if the image file is valid
    if($image !== false){

        // Set the upload directory
        $uploadDir = "img/products/";

        // Create the upload directory if it doesn't exist
        if(!is_dir($uploadDir)){
            mkdir($uploadDir);
        }

        // Generate a unique file name
        $timestamp = time();
        $randomNumber = rand(10000, 99999);
        $customName = $timestamp . "_" . $randomNumber . "_" . $imageName;

        // Set the file path and name
        $filePath = $uploadDir . $customName;

        // Check if the file already exists
        $i = 0;
        while(file_exists($filePath)){
            $i++;
            $customName = $timestamp . "_" . $randomNumber . "_" . $i . "_" . $imageName;
            $filePath = $uploadDir . $customName;
        }

        // Upload the file to the server
        if(move_uploaded_file($image, $filePath)){
            echo "File uploaded successfully";
        } else {
            echo "Error uploading file";
        }

    } else {
        echo "Invalid file";
    }
}
?>

<!-- HTML form to upload the image -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit" value="Upload">
</form>