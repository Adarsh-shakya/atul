<?php
// Connect to the MySQL database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'datting';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full-name'];
    $age = $_POST['age'];
    $country = $_POST['country'];
    $gender = $_POST['gender'];

    // Upload user image
    $target_dir = "uploads/";
    $user_image = $_FILES['user_image']['name'];
    $target_file = $target_dir . basename($_FILES["user_image"]["name"]);

    // Prepare the SQL statement
    $sql = "INSERT INTO user_info (user_image, full_name, age, country, gender) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ssiss", $user_image, $full_name, $age, $country, $gender);

    // Execute the statement
    if ($stmt->execute()) {
        // Move uploaded file after saving data to the database
        if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
            echo "Data saved to the database successfully.";
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!-- Rest of your HTML code remains the same -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 450px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .circle {
            width: 150px;
            height: 150px;
            background-color: #ddd;
            border-radius: 50%;
            margin: 0 auto;
            margin-top: 20px;
            overflow: hidden;
            border: 0.00001px solid black;
        }

        .circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        form {
            text-align: left;
            width: 425px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            /* display: block; */
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 400px;
            padding: 10px;
            border-radius: 14px;
        }

        .form-group select {
            /* height: 100px; */
            overflow-y: scroll;
            width: 100%;
        }

        #user-form button[type="submit"] {
    /* Your button styles here */
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    width: 100%;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="circle">
            <img id="user-image" src="img/upload_icon.jpg" alt="User Image">
        </div>
        <form method="post" action="home2.php" id="user-form">


            <div class="form-group">
                <label for="image-input">Upload Image</label>
                <input type="file" id="image-input" name="user_image">
            </div>
            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="full-name" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <select id="country" name="country" required>
                      
            
                    <option value="">Select Country</option>
                    <option value="usa">USA</option>
                    <option value="canada">Canada</option>
                    <option value="uk">UK</option>
                    <!-- Add more country options here -->
                </select>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label><br>
                <input type="radio" id="male" name="gender" value="male">
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female">
                <label for="female">Female</label>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
    
    <script>
        // JavaScript to handle image upload
        const userImage = document.getElementById('user-image');
        const imageInput = document.getElementById('image-input');
        
        imageInput.addEventListener('change', function () {
            const file = imageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    userImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
