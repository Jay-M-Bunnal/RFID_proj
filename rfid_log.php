<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>

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
        
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
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
        
        tr:hover {
            background-color: #ffffcc;
        }
        
        .no-logs-found {
            color: #666;
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
        }
        
        .logout-button {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            margin-top: -20px;
        }
        
        .logout-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <h1>RFID Log</h1>
        <a href="logout.php" class="logout-button">Logout</a>
        
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

        $sql = "SELECT * FROM log";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Timestamp</th><th>Tag ID</th><th>Access Status</th><th>Ultrasonic Sensor Reading</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . $row["timestamp"]. "</td>";
                echo "<td>" . $row["tag_id"]. "</td>";
                echo "<td>" . $row["access_status"]. "</td>";
                echo "<td>" . $row["ultrasonic_sensor_reading"]. "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo '<p class="no-logs-found">No logs found.</p>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>