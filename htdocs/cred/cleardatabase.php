<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

require "_dbconnect.php";
try {
    $dbconnect->begin_transaction();
    $sql = "TRUNCATE TABLE userkeys";
    $sql1 = "TRUNCATE TABLE modmenus";

    if ($dbconnect->query($sql) !== TRUE) {
        throw new Exception("Error truncating userkeys table: " . $dbconnect->error);
    }

    if ($dbconnect->query($sql1) !== TRUE) {
        throw new Exception("Error truncating modmenus table: " . $dbconnect->error);
    }

    $dbconnect->commit();

    $message = "Database cleared successfully";
} catch (Exception $e) {
    $dbconnect->rollback();
    $message = $e->getMessage();
}

$dbconnect->close();
?>

<script type="text/javascript">
    alert("<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>");
    window.location.href = '/panel.php';
</script>
