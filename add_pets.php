<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control, .btn-primary {
            border-radius: 20px;
        }
        .btn-primary {
            background-color: #007f7b;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #005f5d;
        }
        h2 {
            font-family: 'Arial', sans-serif;
            color: #007f7b;
            font-weight: bold;
        }
        label {
            font-weight: bold;
        }
        .form-label::after {
            content: "*";
            color: red;
            margin-left: 5px;
        }
    </style>
</head>
<body>

<?php
    include 'nav.php'; 
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Add a New Pet</h2>
    <form action="add_pets.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Pet Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your pet's name" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" id="type" name="type" placeholder="E.g., Dog, Cat, Bird" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="text" class="form-control" id="age" name="age" placeholder="Enter your pet's age" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" placeholder="Enter your location" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Add a brief description of your pet" required></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">Add Pet</button>
        </div>
    </form>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "1234567";
    $dbname = "pets";


    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $name = trim($_POST['name']);
    $type = strtoupper(trim($_POST['type']));
    $age = trim($_POST['age']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = 'images/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); 
        }
        
        $image_path = $upload_dir . basename($image_name);


        if (!move_uploaded_file($image_tmp, $image_path)) {
            die("Failed to upload the image.");
        }
    } else {
        die("Please upload a valid image.");
    }


    $stmt = $conn->prepare("INSERT INTO mypets (name, type, image, age, location, description, user_id) VALUES (?, ?, ?, ?, ?, ? ,?)");
    $stmt->bind_param("ssssssi", $name, $type, $image_path, $age, $location, $description, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pet added successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
    include 'footer.php'; 
?>

</body>
</html>
