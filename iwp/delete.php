<?php

if (isset($_GET['patient_id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "db_pms";

    $conn = new mysqli($servername, $username, $password, $database);
    $id = $_GET['patient_id'];

    ##$sql = "DELETE FROM tbl_patient WHERE patient_id = $id";
    ##$conn->query($sql);
    $stmt = $conn->prepare("DELETE FROM tbl_patient WHERE patient_id = ?");
    $stmt->bind_param("i", $id); 

    if ($stmt->execute()) {
        echo "Patient deleted successfully!";
    } else {
        echo "Error deleting patient: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    header("location: /iwp/index.php");
exit;
}else{echo "ID not set";}

?>