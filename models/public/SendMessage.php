<?php
header('Content-Type: application/json');
include "../../config/routes.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {

    // Includes 
    include MODELS . "/Helpers.php";
    include CONFIG . "/connection.php";
    include MODELS . "/Validation.php";
    include MODELS . "/Patterns.php";

    // Variables
    $msg_first_name = isset($_POST['inFname']) ? $_POST['inFname'] : null;
    $msg_last_name = isset($_POST['inLname']) ? $_POST['inLname'] : null;
    $msg_email = isset($_POST['inEmail']) ? $_POST['inEmail'] : null;
    $msg_subject = isset($_POST['inSubject']) ? $_POST['inSubject'] : null;
    $msg_body = isset($_POST['taMessage']) ? $_POST['taMessage'] : null;

    // Validation
    $valid = new Validation();
    // $valid->name("First name")->value($msg_first_name)->customPattern($regName)->required();
    // $valid->name("Last name")->value($msg_last_name)->customPattern($regName)->required();
    // $valid->name("Email")->value($msg_email)->customPattern($regEmail)->required();
    // $valid->name("Subject")->value($msg_subject)->customPattern($regSubject)->required();
    // $valid->name("Message body")->value($msg_body)->required();

    // Check input fields
    // if (!$valid->isSuccess()) {
    if (false) {
        // Validation failed
        $errs[] = $valid->errors;
        $errCodes[] = 422;
        // Form validation failed (checkboxes, radio buttons, input fileds, textarea)
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code(422);
    }

    // Try block
    try {
        //Begin Transaction
        $conn->beginTransaction();

        $isInserted = insertMessage($msg_first_name, $msg_last_name, $msg_email, $msg_subject, $msg_body);

        if (!$isInserted) {
            $errs[] = "Failed to send a message";
            $errCodes[] = 422;
        }

        // Rollback or commit
        if (!empty($errs)) {
            // Rollback
            $conn->rollback();
            echo json_encode($errs, JSON_FORCE_OBJECT);
            http_response_code($errCodes[0]);
        } else {
            // Commit
            $conn->commit();
            echo json_encode(["msg" => "Message successfully sent"], JSON_FORCE_OBJECT);
            http_response_code(201);
        }
    } catch (PDOException $ex) {
        // Try block failed
        $errs[] = $ex->getMessage();
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code(422);
    }
} else {
    $errs[] = "Unauthorized access";
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code(403);
}
