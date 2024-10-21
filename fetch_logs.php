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

$rowsPerPage = $_GET['rows'];
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
        echo '<a href="?page=' . $i . '&rows=' . $rowsPerPage . '" class="' . $active . '">' . $i . '</a>';
    }
    echo '</div>';
} else {
    echo '<p class="no-logs-found">No logs found.</p>';
}

$conn->close();
?>