<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_pms";

$connection = new mysqli($servername, $username, $password, $database);

$name= "";
$address= "";

$errorMessage = "";
$successMessage= "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address =$_POST['address'];

    do{
        if (empty($name) || empty($address)) {
            $errorMessage = "All the fields are required";
            break;
        }

        $stmt = $connection->prepare("INSERT INTO tbl_patient (name, address) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $address);

        if (!$stmt->execute()) {
            $errorMessage = "Error: " . $stmt->error;
            break;
        }
        $name= "";
        $address = "";

        $successMessage= "Client added";
        header("location: /iwp/index.php");
        exit;

    }while(false);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IWP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <script src= "https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class= "container my-5">
        <h2>New client</h2>
        <?php
        if(!empty($errorMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>$errorMessage</strong>
            <button type= 'button' class= 'btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";

        }
        ?>
        
        <form method="post">
            <div class= "row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class= "col-sm-6">
                    <input type="text" class= "form-control" name="name" value="<?php echo $name;?>">
                </div>
            </div>
            <div class= "row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class= "col-sm-6">
                    <input type="text" class= "form-control" name="address" value="<?php echo $address;?>">
                </div>
            </div>
            <?php
        if(!empty($successMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>$successMessage</strong>
            <button type= 'button' class= 'btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";

        }
        ?>
            <div class= "row mb-3">
                <div class= "offset-sm-3 col-sm-3 dgrid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <div class="col-sm-3 dgrid">
                        <a class="btn btn-outline-primary" href="/iwp/index.php" role= "button">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
