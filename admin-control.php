<?php
session_start();

if (isset($_POST['upload'])) {
    include 'dbh.php';

    // Path for the uploaded files
    $targetvid = "video-uploads/" . basename($_FILES['video']['name']);
    $targetimg = "uploads/" . basename($_FILES['image']['name']);

    // Get input data
    $name = strtolower(mysqli_real_escape_string($conn, $_POST['mname']));
    $rdate = mysqli_real_escape_string($conn, $_POST['release']);
    $genre = strtolower(mysqli_real_escape_string($conn, $_POST['genre']));
    $rtime = mysqli_real_escape_string($conn, $_POST['rtime']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $image = $_FILES['image']['name'];
    $video = $_FILES['video']['name'];

    // SQL statement with prepared statements
    $stmt = $conn->prepare("INSERT INTO movies (name, rdate, genre, runtime, description, imgpath, videopath) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $rdate, $genre, $rtime, $desc, $image, $video);

    // Execute the query and handle file uploads
    if ($stmt->execute() && move_uploaded_file($_FILES['image']['tmp_name'], $targetimg) && move_uploaded_file($_FILES['video']['tmp_name'], $targetvid)) {
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error . "<br>Error uploading files.";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
