<?php
$id = $name = $address = "";
$errorMessage = "";
$successMessage = "";

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_pms";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    // If the connection failed, die with an error message
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];  // Get patient ID from URL parameter

    // Query to get the patient's data based on the patient_id
    $sql = "SELECT * FROM tbl_patient WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind patient_id parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $address = $row['address'];
    } else {
        $errorMessage = "Patient not found!";
    }

    $stmt->close();
}

// Step 2: Update patient data (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];

    // Validate form data
    if (empty($id) || empty($name) || empty($address)) {
        $errorMessage = "All fields are required.";
    } else {
        // Prepare the UPDATE SQL query
        $stmt = $conn->prepare("UPDATE tbl_patient SET name = ?, address = ? WHERE patient_id = ?");
        $stmt->bind_param("ssi", $name, $address, $id);

        // Execute the query and check if the update is successful
        if ($stmt->execute()) {
            $successMessage = "Patient updated successfully!";
            header("Location: /iwp/index.php?message=" . urlencode($successMessage));  // Redirect to index.php
            exit;
        } else {
            $errorMessage = "Error updating patient: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Edit Patient</h2>

    <?php if (!empty($errorMessage)) : ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo htmlspecialchars($errorMessage); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo htmlspecialchars($successMessage); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Address</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="/iwp/index.php" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>