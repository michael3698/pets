<?php
session_start();


    if (!isset($_SESSION['user_id']) ) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
?>

<?php

    $servername = "localhost";
    $username = "root";
    $password = "1234567";
    $dbname = "pets";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_pets'])) {
   
        $p_id = intval($_POST['p_id']);
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM mypets WHERE p_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $p_id, $user_id);
        $stmt->execute();   
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
        $pet = $result->fetch_assoc();

        $name = trim($_POST['name']);
        $type = trim($_POST['type']);
        $age = trim($_POST['age']);
        $location = trim($_POST['location']);
        $description = trim($_POST['description']);

        $sql_update = "UPDATE mypets SET name = ?, type = ?, age = ?, location = ?, description = ? , last_update = NOW() WHERE p_id = ? AND user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('sssssii', $name, $type, $age, $location, $description, $p_id, $user_id);

        if ($stmt_update->execute()) {
            echo "<script>alert('Pet details updated successfully.');</script>";
        } else {
            echo "<p>Failed to update pet details.</p>";
        }
        } 
        elseif($result->num_rows === 0){
            echo "Pet not found or you don't have permission to update this pet.";
                exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" integrity="sha512-72OVeAaPeV8n3BdZj7hOkaPSEk/uwpDkaGyP4W2jSzAC8tfiO4LMEDWoL3uFp5mcZu+8Eehb4GhZWFwvrss69Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

        .pet-container {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 50px;
            max-width:1520px;
        }
        .pet-image {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
        }
        .pet-details {
            font-family: 'Itim', cursive;
            padding-left: 15px; 
        }
        .pet-details h1 {
            color: #007f7b;
            margin-bottom: 20px;
        }
        .icon {
            color: #007f7b;
            margin-right: 10px;
        }
        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px; 
        }
        .detail-item strong {
            min-width: 90px; 
            display: inline-block;
        }
        .btn-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<script>
    function confirmupdatepets() {
        return confirm("Are you sure you want to update this pets?");
    }
    function confirmDeletepets() {
        return confirm("Are you sure you want to delete this pets?");
    }

</script>

<?php
    include 'nav.php'; 
?>
<div style=" min-height:78vh;">

<?php
if (isset($_GET['p_id'])) {
    $pet_id = $_GET['p_id'];


    $sql_pet = "SELECT * FROM mypets WHERE p_id = ?";
    $stmt = $conn->prepare($sql_pet);
    $stmt->bind_param('i', $pet_id);
    $stmt->execute();
    $result_pet = $stmt->get_result();

    if ($result_pet->num_rows > 0) {
        $pet = $result_pet->fetch_assoc();
        ?>
                <div class="row align-items-center pet-container">
                    <div class="col-md-5 text-center">
                        <img src="<?= htmlspecialchars($pet['image']) ?>" alt="<?= htmlspecialchars($pet['name']) ?>" class="pet-image">
                    </div>
                    <div class="col-md-7 pet-details">
                        <h1><?= htmlspecialchars($pet['name']) ?></h1>
                        <div class="detail-item">
                            <i class="fas fa-paw icon"></i>
                            <strong>Type:</strong> <?= htmlspecialchars($pet['type']) ?>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-birthday-cake icon"></i>
                            <strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt icon"></i>
                            <strong>Location:</strong> <?= htmlspecialchars($pet['location']) ?>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-info-circle icon"></i>
                            <strong>Description:</strong> <?= htmlspecialchars($pet['description']) ?>
                        </div>
                        <?php 
                            if ($pet['user_id'] == $user_id): ?>
                            
                                <div class="btn-container">
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirmDeletepets()">
                                        <input type="hidden" name="p_id" value="<?= $pet['p_id'] ?>">
                                        <button type="submit" name="delete_pets" class="btn btn-danger">Delete</button>
                                    </form>
                                    <form  style="display:inline-block;"  onclick="toggleUpdateForm()">
                                        <input type="hidden" name="p_id" value="<?= $pet['p_id'] ?>">
                                        <input type="hidden" name="user_id" value="<?=$pet['user_id']?>">
                                        <button type="button" class="btn btn-warning" >Update</button>
                                    </form>

                                </div>
                            <?php 
                            endif; 

                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_pets'])) {
                                $pets_id = intval($_POST['p_id']);
                                $stmt = $conn->prepare("DELETE FROM mypets WHERE p_id = ?");
                                $stmt->bind_param("i", $pets_id);
                                if ($stmt->execute()) {
                                    
                                    $stmt->close();  
                                    
                                    echo "<script>window.location.href = 'gallery.php';</script>";
                                    exit;
                                } else {
                                   
                                    echo "Error: " . $stmt->error;
                                }
                            }
                            ?>
                    </div>
                    
                </div>
                <div id="updateForm" class="container mt-5" style="display: none;">
                    <h2>Update Pet</h2>
                    <form method="POST">
                        <input type="hidden" name="p_id" value="<?= $pet['p_id'] ?>">
                        <input type="hidden" name="user_id" value="<?=$pet['user_id']?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Pet Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($pet['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" id="type" name="type" class="form-control" value="<?= htmlspecialchars($pet['type']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="text" id="age" name="age" class="form-control" value="<?= htmlspecialchars($pet['age']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($pet['location']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($pet['description']) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button style="margin-bottom: 10px;" type="submit" class="btn btn-primary" name="update_pets" onclick="return confirmupdatepets()">Update</button>
                            <a style="margin-bottom: 10px; margin-left: 10px;" href="pets.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
        <?php
    } else {
            echo "<p>Pet not found.</p>";
        }

    $conn->close();
    
}

elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';

    $servername = "localhost";
    $username = "root";
    $password = "1234567";
    $dbname = "pets";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM mypets WHERE 1=1";
    if (!empty($query)) {
        $sql .= " AND name LIKE ?";
    }
    if (!empty($type)) {
        $sql .= " AND type = ?";
    }

    $stmt = $conn->prepare($sql);
    if (!empty($query) && !empty($type)) {
        $search_query = "%$query%";
        $stmt->bind_param("ss", $search_query, $type);
    } elseif (!empty($query)) {
        $search_query = "%$query%";
        $stmt->bind_param("s", $search_query);
    } elseif (!empty($type)) {
        $stmt->bind_param("s", $type);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Display results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="row align-items-center pet-container">
                <div class="col-md-5 text-center">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="pet-image">
                </div>
                <div class="col-md-7 pet-details">
                    <h1><?= htmlspecialchars($row['name']) ?></h1>
                    <div class="detail-item">
                        <i class="fas fa-paw icon"></i>
                        <strong>Type:</strong> <?= htmlspecialchars($row['type']) ?>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-birthday-cake icon"></i>
                        <strong>Age:</strong> <?= htmlspecialchars($row['age']) ?>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt icon"></i>
                        <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-info-circle icon"></i>
                        <strong>Description:</strong> <?= htmlspecialchars($row['description']) ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    else {
        echo "<p>No pets found.</p>";
    }

    $conn->close();
}



else {
    echo "<p>Invalid request. No pet ID provided.</p>";
}
?>


</div>
<?php
    include 'footer.php'; 
?>

<script>

    function toggleUpdateForm() {
        const updateForm = document.getElementById('updateForm');
        updateForm.style.display = updateForm.style.display === 'none' ? 'block' : 'none';
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js" integrity="sha512-Sct/LCTfkoqr7upmX9VZKEzXuRk5YulFoDTunGapYJdlCwA+Rl4RhgcPCLf7awTNLmIVrszTPNUFu4MSesep5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>