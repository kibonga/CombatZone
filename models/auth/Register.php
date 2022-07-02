<?php
header('Content-Type: application/json');
// Session
if (!isset($_SESSION)) {
    session_start();
}
// Includes
include_once "../../config/routes.php";
include MODELS . "/Helpers.php";
// Form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["btnRegister"])) {
    // Includes
    include CONFIG . "/connection.php";
    include M_USERS . "/Users.php";
    include MODELS . "/Validation.php";
    include MODELS . "/Patterns.php";

    // Inputs
    $inEmail = $_POST["inEmail"];
    $inFname = $_POST["inFname"];
    $inLname = $_POST["inLname"];
    $inPass = $_POST["inPass"];
    $inPassConf = $_POST["inPassConf"];
    $inPhone = $_POST["inPhone"];
    $inAddr = $_POST["inAddr"];

    // Input validation
    // $valid = new Validation();
    // $valid->name('Email')->value($inEmail)->customPattern($regEmail)->required();
    // $valid->name('First name')->value($inFname)->customPattern($regName)->required();
    // $valid->name('Last name')->value($inLname)->customPattern($regName)->required();
    // $valid->name('Password')->value($inPass)->customPattern($regPass)->required();
    // $valid->name('Address')->value($inAddr)->customPattern($regAddress)->required();
    // $valid->name('Phone number')->value($inPhone)->customPattern($regPhone)->required();

    $errs = [];
    $errCodes = [];
    $success = [];

    // Check if passwords match
    $pass_match = false;
    if ($inPass == $inPassConf) {
        $pass_match = true;
    } else {
        $errs[] = "Passwords don't match";
        $errCodes[] = 422;
    }
    // Check if there were any errors
    if ($pass_match) {
    // if ($valid->isSuccess() && $pass_match) {
        // Form is valid
        // Try
        try {
            $conn->beginTransaction();
            // Check if email is available
            $table = 'user';
            $where = 'email_user';
            $isEmailAvailable = isAvailable($table, $where, $inEmail);
            if ($isEmailAvailable) {
                // Hash password
                $passSha = hash("sha256", $inPass);

                // Try to register new user
                $loggedUser = registerUser($inEmail, $passSha, $inFname, $inLname, $inAddr, $inPhone);
                if ($loggedUser) {
                    // User successfully registered
                    $success = "You have registered successfully";
                    // $resp = ["msg" => "You have registered successfully"];
                    // echo json_encode(["msg" => "You have registered successfully", $loggedUser->name_role], JSON_FORCE_OBJECT);
                } else {
                    // Log error
                    $errs[] = "Failed to register a user";
                    $errCodes[] = 422;
                }
            } else {
                // Email is not available
                $errs[] = "Email is already in use";
                $errCodes[] = 409;
            }

            // Rollback or commit
            // Check if there were any errors
            if (!empty($errs)) {
                $conn->rollback();
                echo json_encode($errs, JSON_FORCE_OBJECT);
                http_response_code($errCodes[0]);
            } else {
                $conn->commit();
                $_SESSION['loggedUser'] = $loggedUser;
                echo json_encode(["msg" => $success, "user" => $loggedUser->name_role], JSON_FORCE_OBJECT);
                http_response_code(201);
            }
        } catch (PDOException $ex) {
            // Try block failed
            $errs[] = $ex->getMessage();
            echo json_encode($errs, JSON_FORCE_OBJECT);
            http_response_code(422);
        }
    } else {
        // Form is not valid
        // Validation failed
        // Unprocessable entity
        $errs[] = $valid->errors;
        $errCodes[] = 422;
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code($errCodes[0]);
    }
} else {
    // Unauthorized access
    $errs[] = "Unauthorized access";
    $errCodes[] = 403;
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code($errCodes[0]);
}
