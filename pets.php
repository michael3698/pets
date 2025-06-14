<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" integrity="sha512-72OVeAaPeV8n3BdZj7hOkaPSEk/uwpDkaGyP4W2jSzAC8tfiO4LMEDWoL3uFp5mcZu+8Eehb4GhZWFwvrss69Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .img-fluid{
            border-radius:10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

        }
        .table td{
            text-transform: lowercase; 

        }
    </style>
</head>
        
<body >
    <?php
    session_start();
    include 'nav.php'; 
    ?>

<br>
<div style=" min-height:81vh;">
    <div class="container text-center">

        <div class="row ">
            <h3>Discover Pets victoria</h3>
            <p>PETS VICTORIA IS A DEDICATED PET ADOPTION ORGANIZATION BASED IN VICTORIA, AUSTRALIA, FOCUSED ON PROVIDING A SAFE AND LOVING ENVIRONMENT FOR PETS IN NEED. WITH A COMPASSIONATE
                APPROACH, PETS VICTORIA WORKS TIRELESSLY TO RESCUE, REHABILITATE, AND REHOME DOGS, CATS, AND OTHER ANIMALS. THEIR MISSION IS TO CONNECT THESE DESERVING PETS WITH CARING
                INDIVIDUALS AND FAMILIES, CREATING LIFELONG BONDS. THE ORGANIZATION OFFERS A RANGE OF SERVICES, INCLUDING ADOPTION COUNSELING, PET EDUCATION, AND COMMUNITY SUPPORT PROGRAMS,
                ALL AIMED AT PROMOTING RESPONSIBLE PET OWNERSHIP AND REDUCING THE NUMBER OF HOMELESS ANIMALS. 
            </p>
        </div>
    </div>


    <div class="container-fluid text-center">
        <div class="row justify-content-center div1">
            

            <div class="col-lg-4 col-md-4 col-sm-12">
                <img src="images/cat.jpg" class="img-fluid" alt="..." style="width:100%; max-width:400px; margin-top:20px;">
            </div>

            <div class="col-lg-1 ">
            </div>

            <div class="col-lg-6 col-md-8 col-sm-12 "  style=" margin-top:20px; margin-right:5px;">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th scope="col">Pet</th>
                    <th scope="col">Type</th>
                    <th scope="col">Age</th>
                    <th scope="col">Location</th>
                    </tr>
                </thead>

                <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "pets";
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql_pets = "SELECT * FROM mypets";
                    $result_pets = $conn->query($sql_pets);
                   
                ?>

                <tbody>
                    <?php
                        if ($result_pets->num_rows > 0) {
                            while ($row = $result_pets->fetch_assoc()) {
                                $pet_id = $row['p_id']; 
                                $pet_name = $row['name'];
                                $pet_type = $row['type'];
                                $pet_age = $row['age'];
                                $pet_location = $row['location'];
                                $pet_image = $row['image']; 
                    ?>
                            <tr>
                                <td><a href="details.php?p_id=<?= $pet_id ?>"><?= htmlspecialchars($pet_name) ?></a></td>
                                <td><?= htmlspecialchars($pet_type) ?></td>
                                <td><?= htmlspecialchars($pet_age) ?></td>
                                <td><?= htmlspecialchars($pet_location) ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<p>No pets found.</p>";
                    }

                    $conn->close();
                    ?>

                    <tr>
                    
                </tbody>
            </table>
            </div>
        </div>


    </div>
</div>

    
    <?php
    include 'footer.php'; 
    ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js" integrity="sha512-Sct/LCTfkoqr7upmX9VZKEzXuRk5YulFoDTunGapYJdlCwA+Rl4RhgcPCLf7awTNLmIVrszTPNUFu4MSesep5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>

