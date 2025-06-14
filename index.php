<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" integrity="sha512-72OVeAaPeV8n3BdZj7hOkaPSEk/uwpDkaGyP4W2jSzAC8tfiO4LMEDWoL3uFp5mcZu+8Eehb4GhZWFwvrss69Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .div1{
            background-color: #fefae0;
        }
    </style>
</head>
<body>

<?php
session_start();
include 'nav.php'; 
?>


<div style=" min-height:84vh;">

    <div class="container-fluid text-center">
        <div class="row justify-content-center div1">
            <div class="col-lg-5 col-md-6 col-6">
                <img src="images/cat.jpg" class="img-fluid" alt="..." style="width:100%; max-width:400px; margin-top:30px; margin-bottom: 20px;">
            </div>
            <div class="col-lg-7 col-md-6 col-6 "  style=" margin: auto; ">
                <h2 style=" font-family: 'Permanent Marker', cursive; font-weight: 800; font-style: normal; color:  #ff8e45;">PETS VICTORIA</h2>
                
                <h2 style=" font-family: 'Itim', cursive; font-weight: 600; font-style: normal; color: #007f7b;">WELCOME TO PET ADOPTION</h2>
            </div>
        </div>


    </div>
    <br>
    <div class="container ">

        <div class="row justify-content-center">
            <form class="d-flex" action="details.php" method="GET" role="search">
                <input class="form-control me-2" type="search" name="query" placeholder="Search by name" aria-label="Search">
                
                <select class="form-control me-2" name="type" id="type">
                    <option value="">Select your pet type</option>
                    <?php
                    
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "pets";
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    
                    $sql = "SELECT DISTINCT type FROM mypets ";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { 
                            $pet_type = htmlspecialchars($row['type']);
                            $upper_type = strtoupper($pet_type);
                            $lower_type = strtolower($pet_type);

                            echo "<option value=\"$pet_type\">$upper_type </option>";
                        }
                    }
                    else {
                        echo "<option value=''>no pets type to show</option>";
                    }

                    $conn->close();
                    ?>
                </select>
                
                <button class="btn" type="submit" style="background-color:#007f7b; color: #fff;">Search</button>
            </form>
        </div>


        <div class="row justify-content-left">
            <h3>Discover Pets victoria</h3>
            <p>PETS VICTORIA IS A DEDICATED PET ADOPTION ORGANIZATION BASED IN VICTORIA, AUSTRALIA, FOCUSED ON PROVIDING A SAFE AND LOVING ENVIRONMENT FOR PETS IN NEED. WITH A COMPASSIONATE
                APPROACH, PETS VICTORIA WORKS TIRELESSLY TO RESCUE, REHABILITATE, AND REHOME DOGS, CATS, AND OTHER ANIMALS. THEIR MISSION IS TO CONNECT THESE DESERVING PETS WITH CARING
                INDIVIDUALS AND FAMILIES, CREATING LIFELONG BONDS. THE ORGANIZATION OFFERS A RANGE OF SERVICES, INCLUDING ADOPTION COUNSELING, PET EDUCATION, AND COMMUNITY SUPPORT PROGRAMS,
                ALL AIMED AT PROMOTING RESPONSIBLE PET OWNERSHIP AND REDUCING THE NUMBER OF HOMELESS ANIMALS. 
            </p>
        </div>


    </div>

</div>





<?php
include 'footer.php'; 
?>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js" integrity="sha512-Sct/LCTfkoqr7upmX9VZKEzXuRk5YulFoDTunGapYJdlCwA+Rl4RhgcPCLf7awTNLmIVrszTPNUFu4MSesep5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
