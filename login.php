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


    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    if (empty($email) || empty($password)) {
        $error = "Please fill in both fields.";
    } 
    else {

        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error = "No user found with this email address.";
        } else {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['u_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type']  = $user['type'];


                if($user['type'] == "user"){
                    echo " user";
                    header("Location: index.php"); 
                }
                else{
                    echo " admin";
                    header("Location: dashboard.php");
                }
                exit;
            } else {
                $error = "Incorrect password.";
            }
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PetWorld</title>
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

        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .login-container {
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
            margin-bottom: 20px;
        }


        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            font-size: 1em;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="email"],
        input[type="password"] {
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
        input[type="password"]:focus {
            outline: none;
            border-color: #ff8c00;
            background-color: #fff;
        }

        button.login-btn {
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

        button.login-btn:hover {
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


        .signup-link {
            margin-top: 20px;
            font-size: 1em;
            color: #555;
        }


        @media (max-width: 768px) {
            .login-container {
                padding: 25px;
            }

            .logo img {
                width: 120px;
            }

            h2 {
                font-size: 1.6em;
            }

            button.login-btn {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="logo">
                <img src="images/R.png" alt="PetWorld Logo">
            </div>
            <h2>Login to Your Account</h2>
            <p class="subheading">Log in to PetWorld and manage your pets.</p>

            <?php if (isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="login-btn">Log In</button>

                <div class="signup-link">
                    <p>Don't have an account? <a href="register.php">Sign up here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
