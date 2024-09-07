<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}
require "cred/_dbconnect.php";

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_GET['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $delete_id = intval($_GET['delete_id']);

    $stmt = $dbconnect->prepare("DELETE FROM `modmenus` WHERE `Id` = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header("Location: modmenus.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/panel.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
    <title>Stardust Menu Panel</title>
</head>

<body>
<?php require "element/_nav.php"; ?>
<br>
<div class="container-sm" style="color: white;">
    <div class="card bg-transparent navbar-glass">
        <h5 class="card-header" style="color: #e0ffcd;"><b>Add Mod Menu</b></h5>
        <div class="card-body bg-transparent">
            <h5 class="card-title">Here You Can Add Your Mod Menu</h5>
            <p class="card-text">Please Remember Your Mod Menu Id !</p>
            <?php if ($dbconnect) { echo '<p class="card-text"><b>Database Status</b>: Connected</p>'; } ?>

            <form id="lgform" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="mb-3">
                    <label for="MenuId" class="form-label">Mod Menu Id (Numbers Only)</label>
                    <input type="number" class="form-control" id="MenuId" name="id" required>
                    <label for="MenuName" class="form-label">Mod Menu Name</label>
                    <input type="text" class="form-control" id="MenuName" name="name" required>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                <button type="submit" name="addmenu" class="btn btn-primary">Add Menu</button>
                </div>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                    die('Invalid CSRF token');
                }

                $ID = $_POST['id'];
                $Name = $_POST['name'];

                $stmt = $dbconnect->prepare("SELECT * FROM `modmenus` WHERE `Id` = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<script type="text/javascript">alert("ID already exists");</script>';
                } else {
                    $stmt = $dbconnect->prepare("INSERT INTO `modmenus` (`MenuName`, `Id`) VALUES (?, ?)");
                    $stmt->bind_param("si", $Name, $ID);
                    $stmt->execute();
                    header("Location: modmenus.php");
                    exit;
                }

                $stmt->close();
            }
            ?>
        </div>
    </div>
    <br>
    <div class="card bg-transparent navbar-glass">
        <h5 class="card-header" style="color: #e0ffcd;">Added Mod Menus</h5>
        <div class="card-body bg-transparent">
            <p>These are all the mod menus registered in our database:</p>
            <table class="table table-hover">
                <thead style="color: #e0ffcd;">
                    <tr>
                        <th>Name</th>
                        <th>ID</th>
                        <th>Delete Menu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fetchqry = "SELECT * FROM `modmenus`";
                    $result = mysqli_query($dbconnect, $fetchqry);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr style="color: white;">';
                            echo '<td>' . htmlspecialchars($row['MenuName']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Id']) . '</td>';
                            echo '<td>
                                <a href="modmenus.php?delete_id=' . urlencode($row['Id']) . '&csrf_token=' . urlencode($_SESSION['csrf_token']) . '">
                                    <button type="button" class="btn btn-danger">Remove</button>
                                </a>
                            </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo "<tr><td colspan='3' style='color:white;'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
