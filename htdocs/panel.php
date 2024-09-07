<?php
ob_start(); // Start output buffering
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: index.php");
    exit;
}

require "cred/_dbconnect.php";

// Handle key deletion if 'no' parameter is set
if (isset($_GET['no'])) {
    $keyToDelete = $_GET['no'];

    // Prepare and execute the delete statement
    $stmt = $dbconnect->prepare("DELETE FROM `userkeys` WHERE `CreatedKeys` = ?");
    $stmt->bind_param("s", $keyToDelete);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Key deleted successfully."); window.location.href = "panel.php";</script>';
    } else {
        echo '<script type="text/javascript">alert("Error deleting key."); window.location.href = "panel.php";</script>';
    }

    $stmt->close();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/panel.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
    <title>Stardust Admin Panel</title>
</head>
<body>


<?php require "element/_nav.php"; ?>

<br>
<div class="container-sm" style="color: white;">
    <div class="card bg-transparent navbar-glass">
        <h5 class="card-header" style="color: #e0ffcd;"><b>Key Management</b></h5>
        <div class="card-body bg-transparent">
            <h5 class="card-title">Generate Your Keys Here</h5>
            <p class="card-text">With this panel you can easily manage your keys!</p>
            <?php 
                if ($dbconnect) {
                    echo '<p class="card-text"><b>Database Status</b> : Connected</p>';
                }
            ?>
            <form id="lgform" method="post">
                <div class="mb-3">
                    <label for="newkey" class="form-label">Key</label>
                    <input type="text" class="form-control" id="keyInput" name="newkey" required>
                </div>
                <div class="mb-3">
                    <label for="dateInput">Select Expiry Date:</label>
                    <input type="text" id="date" name="expdate" class="form-control datepicker" required>
                </div>
                <div class="mb-3">
                    <label for="mods" class="form-label">Choose a Mod Menu:</label>
                    <select name="mods" id="mods" class="form-select">
                        <?php
                        $sql3 = "SELECT Id, MenuName FROM modmenus";
                        $result3 = $dbconnect->query($sql3);
                        if ($result3->num_rows > 0) {
                            while($row = $result3->fetch_assoc()) {
                                echo "<option value=\"" . htmlspecialchars($row["Id"]) . "\">" . htmlspecialchars($row['Id'].' - '.$row["MenuName"]) . "</option>";
                            }
                        } else {
                            echo "<option value=\"\">No mods available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="container form-check mb-3 d-flex align-items-center">
                  <input class="form-check-input" type="checkbox" value="ondl" name="ondl" id="flexCheckChecked" style="width: 30px; height: 30px; margin-right: 10px;">
                  <label class="form-check-label" for="flexCheckChecked" style="font-size: 1.2rem;">One-Device Login</label>
                </div>
                
                <div class="row">
                   <div class="col-12 col-md-6 col-lg-3 mb-2">
                 <button type="submit" name="register" class="btn btn-primary btn-sm w-100">Submit</button>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                   <button type="button" onclick="displayKey()" class="btn btn-success btn-sm w-100">Random Key</button>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                   <a href="/modmenus.php" class="btn btn-info btn-sm w-100">Add New Mod Menu</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                     <a href="/cred/cleardatabase.php" class="btn btn-danger btn-sm w-100">Clear Database (Think Before Click)</a>
                </div>
            </div>
              
                <?php
                if (isset($_POST['register'])) {
                    $NewKey = htmlspecialchars($_POST['newkey'], ENT_QUOTES, 'UTF-8');
                    if (isset($_POST['mods'])) {
                        $selectedmodId = intval($_POST['mods']); 
                        $stmt = $dbconnect->prepare("SELECT MenuName FROM modmenus WHERE id = ?");
                        $stmt->bind_param("i", $selectedmodId);
                        $stmt->execute();
                        $result3 = $stmt->get_result();
                        
                        if ($row = $result3->fetch_assoc()) {
                            $modname = htmlspecialchars($row['MenuName'], ENT_QUOTES, 'UTF-8');
                            $modid = $selectedmodId;
                        } else {
                            echo '<script type="text/javascript">alert("Invalid mod selection");</script>';
                            exit; 
                        }
                    }
                    $onedev = isset($_POST['ondl']) ? 1 : 0;
                    $Expiration = $_POST['expdate'];
                    $date = date("Y-m-d");
                    $stmt = $dbconnect->prepare("SELECT * FROM `userkeys` WHERE `CreatedKeys` = ?");
                    $stmt->bind_param("s", $NewKey);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        echo '<script type="text/javascript">alert("Key already exists");</script>';
                    } else {
                        $stmt = $dbconnect->prepare("INSERT INTO `userkeys` (`CreatedKeys`, `StartDate`, `EndDate`, `ModName`, `ModID`, `OneDevLogin`, `Time`) VALUES (?, ?, ?, ?, ?, ?, current_timestamp())");
                        $stmt->bind_param("ssssii", $NewKey, $date, $Expiration, $modname, $modid, $onedev);
                        $stmt->execute();
                        unset($_SESSION['register']);
                        header("Location: panel.php");
                        exit;
                    }
                }
                ?>
            </form>
        </div>
    </div>
    <br>
    <div class="card bg-transparent navbar-glass card">
        <h5 class="card-header" style="color: #e0ffcd;">Keys</h5>
        <div class="card-body bg-transparent">
            <p>These are all the keys registered in our database:</p> 
            <div class="table-responsive">           
                <table class="table table-hover table-responsive">
                    <thead style="color: #e0ffcd;">
                        <tr class="info">
                            <th>Keys</th>
                            <th>Created On</th>
                            <th>Expire Date</th>
                            <th>Mod Name</th>
                            <th>Mod Id</th>
                            <th>One Device Login</th>
                            <th>Status</th>
                            <th>Delete Key</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fetchqry = "SELECT * FROM `userkeys`";
                        $result = mysqli_query($dbconnect, $fetchqry);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr style="color: white;">
                                    <td><?php echo htmlspecialchars($row['CreatedKeys']); ?></td>
                                    <td><?php echo htmlspecialchars($row['StartDate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['EndDate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ModName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ModID']); ?></td>
                                    <td><?php echo ($row['OneDevLogin'] == 1) ? "Yes" : "No"; ?></td>
                                    <td><?php echo (strtotime(date("Y-m-d")) <= strtotime($row['EndDate'])) ? "Active" : "Expired"; ?></td>
                                    <td>
                                        <a href="panel.php?no=<?php echo urlencode($row['CreatedKeys']); ?>">
                                            <button type="button" class="btn btn-danger">Remove</button>
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            echo "<tr><td colspan='8' style='color:white;'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    function generateKey(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function displayKey() {
        const key = generateKey(20); 
        document.getElementById("keyInput").value = key; 
    }
</script>
</body>
</html>

<?php
ob_end_flush(); // End buffering and send output
?>
