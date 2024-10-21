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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
        
        .rows-per-page {
            float: right;
            margin-top: -20px;
            margin-right: 10px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .rows-per-page label {
            font-weight: bold;
            margin-right: 10px;
        }
        
        .rows-per-page select {
            padding: 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        
        .pagination a {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 5px;
            text-decoration: none;
            color: #337ab7;
        }
        
        .pagination a:hover {
            background-color: #f5f5f5;
        }
        
        .pagination .active {
            background-color: #337ab7;
            color: #fff;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <h1>RFID Log</h1>
        <a href="logout.php" class="logout-button">Logout</a>
        <div class="rows-per-page">
            <label for="rows">Rows per page:</label>
            <select id="rows" onchange="changeRowsPerPage(this.value)">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
            </select>
        </div>
        
        <div id="log-table">
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

            $rowsPerPage = 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $rowsPerPage;

            $sqlCount = "SELECT COUNT(*) as total_rows FROM log";
            $resultCount = $conn->query($sqlCount);
            $totalRows = $resultCount->fetch_assoc()['total_rows'];
            $totalPages = ceil($totalRows / $rowsPerPage);

            $sql = "SELECT * FROM log LIMIT $rowsPerPage OFFSET $offset";
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
                
                // Display total rows and pagination
                echo '<p>Total Rows: ' . $totalRows . ' | Page ' . $page . ' of ' . $totalPages . '</p>';
                echo '<div class="pagination">';
                for ($i = 1; $i <= $totalPages; $i++) {
                    $active = $page == $i ? 'active' : '';
                    echo '<a href="?page=' . $i . '" class="' . $active . '">' . $i . '</a>';
                }
                echo '</div>';
            } else {
                echo '<p class="no-logs-found">No logs found.</p>';
            }

            $conn->close();
            ?>
        </div>
        
        <script>
            function changeRowsPerPage(rows) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("log-table").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "fetch_logs.php?rows=" + rows + "&page=1", true);
                xmlhttp.send();
            }
        </script>
    </div>
</body>
</html>