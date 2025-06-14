<style>
    nav {
        font-family: "Itim", cursive;
        font-weight: 500;
        font-style: normal;
        font-size: 18px;
        margin: 0;
        padding: 0px;
    }

    .navbar {
        background-color: #007f7b !important;
    }

    .nav-item {
        margin-left: 15px;
    }

    .nav-item a {
        color: #9cc6bd;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .nav-item a:hover {
        color: #fff;
    }
    .nav-item .logout:hover {
        color: red !important;
        transform: scale(1.1);
    }

    .active {
        color: #fff !important;
    }
</style>

<?php
$pageName = basename($_SERVER['PHP_SELF']); 
?>


<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">

    <img  src="images/R.png" alt="Bootstrap" width="35" height="29">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" >
        <li class="nav-item" >
          <a class="nav-link <?php if ($pageName == "index.php") echo "active"; ?>" href="index.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($pageName == "pets.php") echo "active"; ?>" href="pets.php">Pets</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($pageName == "gallery.php") echo "active"; ?>" href="gallery.php">Gallery</a>
        </li>

        <?php if (isset($_SESSION['user_id']) ) 
        { ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($pageName == "add_pets.php") echo "active"; ?>" href="add_pets.php">Add More</a>
            </li>
            <li class="nav-item">
                <a class="nav-link logout <?php if ($pageName == "logout.php") echo "active"; ?>" href="logout.php">Logout</a>
            </li>

        <?php 
        }
         else 
         { ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($pageName == "register.php") echo "active"; ?>" href="register.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($pageName == "login.php") echo "active"; ?>" href="login.php">Login</a>
            </li>
        <?php 
        } ?>

        <?php if (isset($_SESSION['user_id']) &&  $_SESSION['user_type'] == 'admin') 
        { ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($pageName == "dashboard.php") echo "active"; ?>" href="dashboard.php">dashboard</a>
            </li>
        <?php
        } ?>
        
      </ul>

      <form class="d-flex" action="details.php" method="GET" role="search">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pets";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT name FROM mypets ";
        $result = $conn->query($sql);
        ?>

        <input class="form-control me-2" type="search" name="query" list="petTypes" placeholder="Search" aria-label="Search">

        <button class="btn" type="submit"> <i class="fa-solid fa-magnifying-glass" style="color: #fff;"></i> </button>
    </form>


    </div>
  </div>
</nav>

