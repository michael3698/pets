<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "1234567";
    $dbname = "pets";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($name) ||empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO user (email, password , name, created_at) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $created_at = date("Y-m-d H:i:s"); 
            $stmt->bind_param('ssss', $email, $hashed_password,$name, $created_at); 
            if ($stmt->execute()) {
                echo "Registration successful!";
                header("Location: login.php"); 
                exit;
            } else {
                echo "Error: " . $stmt->error;
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - PetWorld</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f4f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        p.subheading {
            font-size: 1em;
            color: #777;
            margin-bottom: 30px;
        }

        .error-message {
            color: red;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .login-wrapper, .signup-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .login-container, .signup-container {
            background-color: white;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo img {
            width: 100px;
            margin-bottom: 10px;
        }

        .input-group {
            margin-bottom: 12px;
            text-align: left;
        }

        .input-group label {
            font-size: 1em;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="checkbox"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #ff8c00;
            background-color: #fff;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            justify-content: flex-start;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 10px;
        }

        button.login-btn, button.signup-btn {
            width: 100%;
            padding: 14px;
            background-color: #ff8c00;
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.login-btn:hover, button.signup-btn:hover {
            background-color: #e07b00;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .forgot-password, .signup-link {
            margin-top: 10px;
        }

        .signup-link {
            margin-top: 20px;
            font-size: 1em;
            color: #555;
        }

        @media (max-width: 768px) {
            .login-container, .signup-container {
                padding: 25px;
            }

            .logo img {
                width: 120px;
            }

            h2 {
                font-size: 1.6em;
            }

            button.login-btn, button.signup-btn {
                font-size: 1em;
            }
        }


    </style>
</head>
<body>
    <div class="signup-wrapper">
        <div class="signup-container">
            <div class="logo">
                <img src="images/R.png" alt="PetWorld Logo">
            </div>
            <h2>Create Your Account</h2>
            <p class="subheading">Sign up to join PetWorld and manage your pets.</p>

            <?php if (isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <form action="register.php" method="POST">
            <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your name">
                </div>

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                </div>

                <button type="submit" class="signup-btn">Sign Up</button>

                <div class="login-link">
                    <p>Already have an account? <a href="login.php">Log in here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
