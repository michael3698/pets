
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}


$servername = "localhost";
$username = "root";
$password = "1234567";
$dbname = "pets";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $role = trim($_POST['role']); // Either 'user' or 'admin'

    
    if (!empty($name) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("INSERT INTO user (name, email, password, created_at, type) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php?page=users");
    }
}

// Add pets
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_pet'])) {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
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


    $stmt = $conn->prepare("INSERT INTO mypets (name, type, image, age, location, description, user_id ,created_at) VALUES (?, ?, ?, ?, ?, ? ,?, NOW())");
    $stmt->bind_param("ssssssi", $name, $type, $image_path, $age, $location, $description, $user_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Pet added successfully!'); 
            window.location.href = 'dashboard.php?page=pets';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Delete User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $conn->prepare("DELETE FROM user WHERE u_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=users");
}

// Delete pets
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_pets'])) {
    $pets_id = intval($_POST['pets_id']);
    $stmt = $conn->prepare("DELETE FROM mypets WHERE p_id = ?");
    $stmt->bind_param("i", $pets_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?page=pets");
}

// Fetch summary counts
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM user")->fetch_assoc()['count'];
$totalPets = $conn->query("SELECT COUNT(*) AS count FROM mypets")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #004947;
            color: white;
            padding-top: 20px;
        }
        .text-center{
            margin-bottom: 20px !important;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            text-decoration: none;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar{
            background-color: #004947 !important;
            position: static;
        }
        .pet-image{
            max-width: 70px;
        }
        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-text {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .btn-outline-primary, .btn-outline-success {
            margin-top: 15px;
        }


    </style>
</head>
<body>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this user?");
    }
    function confirmDeletepets() {
        return confirm("Are you sure you want to delete this pets?");
    }


    </script>


    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center">Admin Dashboard</h3>
        <a href="?page=dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a href="?page=users"><i class="fas fa-users"></i> Manage Users</a>
        <a href="?page=pets"><i class="fas fa-paw"></i> Manage Pets</a>
        <a href="index.php"><i class="fas fa-shield-cat"></i> Home</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
                <?php
                $result = $conn->query("SELECT * FROM user");
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc()
                            
                ?>
            <a class="navbar-brand ms-auto" href="#" >Welcome <?= htmlspecialchars($row['name']) ?></a>
            <?php } ?>
        </div>
    </nav>

    <div class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

        switch ($page) {
            case 'dashboard':
                echo "<div class='container'>";
                echo "<h2 class='text-center mb-4'><strong>Dashboard Overview</strong></h2>";

                echo "<div class='row'>";

                echo <<<HTML
                <div class="col-md-4">
                    <div class="card shadow-sm border-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-4 text-primary">$totalUsers</p>
                            <a href="?page=users" class="btn btn-outline-primary">Manage Users</a>
                        </div>
                    </div>
                </div>
        HTML;

                echo <<<HTML
                <div class="col-md-4">
                    <div class="card shadow-sm border-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Pets</h5>
                            <p class="card-text display-4 text-success">$totalPets</p>
                            <a href="?page=pets" class="btn btn-outline-success">Manage Pets</a>
                        </div>
                    </div>
                </div>
        HTML;


                echo "</div>";
                echo "</div>"; 
            break;


            case 'users':
                echo "<h2>Manage Users</h2>";

                // Add User Form
                echo <<<HTML
                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-success">Add User</button>
                </form>
HTML;

                // Display Users
                $result = $conn->query("SELECT * FROM user");
                if ($result->num_rows > 0) {
                    echo "<table class='table'><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>type</th><th>Actions</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo <<<HTML
                        <tr>
                            <td>{$row['u_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td style="color: red;">{$row['type']}</td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="user_id" value="{$row['u_id']}">
                                    <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
HTML;
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No users found.</p>";
                }
                break;

            case 'pets':
                echo "<h2>Manage Pets</h2>";
                echo <<<HTML
                <form method="POST" class="mb-4" enctype="multipart/form-data">
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
                        <button type="submit" name="add_pet" class="btn btn-primary px-5">Add Pet</button>
                    </div>
                </form>
                HTML;

                $result = $conn->query("SELECT mypets.*, user.name AS owner_name FROM mypets LEFT JOIN user ON mypets.user_id = user.u_id");
                if ($result->num_rows > 0) {
                    echo "<table class='table text-center'><thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Age</th><th>image</th><th>Owner ID</th><th>Owner Name</th><th>Actions</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo <<<HTML
                        <tr>
                        <td>{$row['p_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['age']}</td>
                        <td> 
                            <img src="{$row['image']}" class="pet-image" alt="User Image">
                        </td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['owner_name']}</td>
                        <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirmDeletepets()">
                                    <input type="hidden" name="pets_id" value="{$row['p_id']}">
                                    <button type="submit" name="delete_pets" class="btn btn-danger btn-sm">Delete</button>
                                </form>

                            </td>
                        </tr> 
                        HTML;
                    }
                    echo "</tbody></table>";

                } else {
                    echo "<p>No pets found.</p>";
                }
                break;



            default:
                echo "<h2>Page Not Found</h2><p>The page you are trying to access does not exist.</p>";
        }
        ?>
        
    </div>
</body>
</html>

<?php
$conn->close();
?>
