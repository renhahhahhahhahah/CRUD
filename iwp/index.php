<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IWP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    
</head>
<body>
    <div class = "container my-5">
        <h2>List of clients</h2>
        <a class="btn btn-primary" href="/iwp/create.php" role="button">New Client</a>
        <br>
        <table class="table">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Action</th>
            </thead>
            <tbody>
            <?php
            

            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "db_pms";

            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql= "SELECT * FROM tbl_patient";
            $result= $conn->query($sql);

            if (!$result) {
                die("Invalid query: " . $conn->error);
            }

            while ($row =$result->fetch_assoc()) {
                echo "
                <tr>
                    <td>$row[patient_id]</td>
                    <td>$row[name]</td>
                    <td>$row[address]</td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='/iwp/update.php?id=" . $row['patient_id'] . "'>Edit</a>
                        <a class='btn btn-danger btn-sm'href='delete.php?patient_id=" . $row['patient_id'] . "'>Delete</a>
                    </td>
                </tr>
                ";
            }
            ?>   
            </tbody>
        </table>
    </div>
</body>
</html>