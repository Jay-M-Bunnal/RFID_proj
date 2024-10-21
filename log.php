<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        
        .container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .success-message {
            color: #2ecc71;
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f5f5f5;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>RFID Log</h1>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "rfid_log";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle POST request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $timestamp = $_POST["timestamp"];
            $tag_id = $_POST["tag_id"];
            $access_status = $_POST["access_status"];
            $ultrasonic_sensor_reading = $_POST["ultrasonic_sensor_reading"];

            $sql = "INSERT INTO log (timestamp, tag_id, access_status, ultrasonic_sensor_reading) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $timestamp, $tag_id, $access_status, $ultrasonic_sensor_reading);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {
                echo '<p class="success-message">Data logged successfully!</p>';
            } else {
                echo '<p class="error-message">Error logging data: ' . $conn->error . '</p>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>