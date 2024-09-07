<?php
define("IntigrityKey", "K8fX5Z2tP7rL3mQ1vW9y"); // mod menu intigrity
header('Content-Type: application/json');

function parseSpecialChars($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function decodeKeyFromUrl($key) {
    return urldecode($key);
}

$key = isset($_GET['key']) ? decodeKeyFromUrl(parseSpecialChars($_GET['key'])) : '';
$integrityKey = isset($_GET['integrityKey']) ? parseSpecialChars($_GET['integrityKey']) : '';
$uuid = trim(isset($_GET['uuid']) ? parseSpecialChars($_GET['uuid']) : '');
$modid = trim(isset($_GET['modid']) ? parseSpecialChars($_GET['modid']) : '');
$response = array("Status" => "Failed", "MessageString" => "404", "Username" => "");

// login response tree dont touch it
// key verification
if ($integrityKey === IntigrityKey) { 
    
    include "cred/_dbconnect.php";
    $sql88 = $dbconnect->prepare("SELECT * FROM `userkeys` WHERE `CreatedKeys` = ?");
    $sql88->bind_param("s", $key);
    $sql88->execute();
    $result88 = $sql88->get_result();

    if ($result88->num_rows > 0) {     
        $exprow = $result88->fetch_assoc();
        $expdate = $exprow['EndDate'];
        $onedev = $exprow['OneDevLogin'];
        $storedUUID = trim($exprow['UUID']);
        $modidx = trim($exprow['ModID']);
        if(strtotime(date("Y-m-d")) <= strtotime($expdate)) {
            if ($modid == $modidx) {
                if($onedev == 1) {
                    // One device login block
                    if ($storedUUID == NULL && isset($uuid)) {
                            $sqlUpdate = $dbconnect->prepare("UPDATE `userkeys` SET `UUID` = ? WHERE `CreatedKeys` = ?");
                            $sqlUpdate->bind_param("ss", $uuid, $key);
                            $sqlUpdate->execute();
            
                            $response["Status"] = "Success_Stardust_Login";
                            $response["MessageString"] = "Your device registred succesfully on our server & your key is valid till ".$expdate;
                            $response["Username"] = $key;
                    } else {
                        if ($storedUUID == $uuid) {
                            $response["Status"] = "Success_Stardust_Login";
                            $response["MessageString"] = "One-dev login success your key is valid till ".$expdate;
                            $response["Username"] = $key;
                        } else {
                            $response["Status"] = "Failed";
                            $response["MessageString"] = "This key is registred on another device";
                            $response["Username"] = "";
                        }
                    }
                } else {
                    // Multi device login block
                    $response["Status"] = "Success_Stardust_Login";
                    $response["MessageString"] = "multi-dev login success your key is valid till ".$expdate;
                    $response["Username"] = $key;
                }
            } else {
                $response["Status"] = "Failed";
                $response["MessageString"] = "This Key belongs to another mod menu !";
                $response["Username"] = ""; 
            }
        } else {
            $response["Status"] = "Failed";
            $response["MessageString"] = "Key was expired on ".$expdate;
            $response["Username"] = ""; 
        }
            
    } else {
        $response["Status"] = "Failed";
        $response["MessageString"] = "Invalid key !";
        $response["Username"] = ""; 
    }
        
} else {
    $response["Status"] = "Failed";
    $response["MessageString"] = "Can't Verify Your Mod Menu ";
    $response["Username"] = ""; 
}
echo json_encode($response);
?>