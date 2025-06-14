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
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            margin: 20px auto;
            padding: 16px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .card img {
            border-radius: 8px 8px 0 0;
            max-width: 100%;
            transition: .5s ease;
        }
        .card img:hover {
            transform: scale(1.1);
        }
        .card h2 {
            margin: 16px 0;
            font-size: 1.5em;
        }
        .card button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .card button:hover {
            background-color: #0056b3;
        }
        @media( max-width: 570px){
            .card {
                max-width: 800px;
            }

        }
    </style>
</head>
        
<body >
    <?php
    session_start();
    include 'nav.php'; 
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

    $selected_type = isset($_GET['type']) ? $_GET['type'] : '';

    if ($selected_type && $selected_type != 'all') {
        $sql_pets = "SELECT * FROM mypets WHERE type = ?";
        $stmt = $conn->prepare($sql_pets);
        $stmt->bind_param("s", $selected_type);
        $stmt->execute();
        $result_pets = $stmt->get_result();
    } else {
        $sql_pets = "SELECT * FROM mypets";
        $result_pets = $conn->query($sql_pets);
    }

     ?>

<br>
<div style=" min-height:81vh;">
    <div class="container text-center">

        <div class="row ">
            <h3>Pets victoria has a lot to offer!</h3>
            <p>FOR ALMOST TWO DECADES, PETS VICTORIA HAS HELPED IN CREATING TRUE SOCIAL
                CHANGE BY BRINGING PET ADOPTION INTO THE MAINSTREAM. OUR WORK HAS HELPED MAKE A DIFFERENCE TO THE 
                VICTORIAN RESCUE COMMUNITY AND THOUSANDS OF PETS IN NEED OF RESCUE AND 
                REHABILITATION. BUT, UNTIL EVERY PET IS SAFE, RESPECTED, AND LOVED, WE ALL
                STILL HAVE BIG, HAIRY WORK TO DO.


            </p>
        </div>
    </div>


    <div class="container-fluid text-center">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-sm-12">
                <form method="GET" class="d-flex" role="search">
                    <select class="form-control me-2" name="type" id="type" onchange="this.form.submit()">
                        <option value="">Select your pet type</option>
                        
                        <option value="all" <?= $selected_type == 'all' ? 'selected' : '' ?>>All Types</option>
                        <?php
                        
                        $sql = "SELECT DISTINCT type FROM mypets";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $pet_type = htmlspecialchars($row['type']);
                                echo "<option value=\"$pet_type\">$pet_type</option>";
                            }
                        }
                        else {
                            echo "<option value=''>no pets type to show</option>";
                        }

                        ?>
                    </select>

                </form>
            </div>

            
        </div>

        <div class="row " style=" margin:auto; margin-bottom:20px; column-gap: 150px; row-gap:20px;">

                <?php               
            
                if ($result_pets->num_rows > 0) {
                    while ($row = $result_pets->fetch_assoc()) {
                        $pet_id = $row['p_id']; 
                        $pet_name = $row['name'];
                        $pet_image = $row['image'];
                ?>
                        <div class="col-lg-6 col-md-12 col-sm-12 card">
                            <a href="details.php?p_id=<?= $pet_id ?>" style="text-decoration: none;">
                                <img src="<?= $pet_image ?>" alt="<?= htmlspecialchars($pet_name) ?>" />
                                <h2 class="fw-normal"><?= htmlspecialchars($pet_name) ?></h2>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No pets found.</p>";
                }

                $conn->close();
                ?>
                


        </div>

    </div>

</div>

    
    <?php
    include 'footer.php'; 
    ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js" integrity="sha512-Sct/LCTfkoqr7upmX9VZKEzXuRk5YulFoDTunGapYJdlCwA+Rl4RhgcPCLf7awTNLmIVrszTPNUFu4MSesep5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>

