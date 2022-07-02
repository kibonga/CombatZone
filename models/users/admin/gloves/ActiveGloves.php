<?php
include "../../../../config/routes.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Includes
    include_once MODELS . "/Helpers.php";
    include_once CONFIG . "/connection.php";
    include_once "../Admin.php";

    $errs = [];
    $errCodes = [];

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Fetch all active gloves
        $gloves = selectAllActiveGloves();
        if (!count($gloves) > 0) {
            // Failed to retrieve sizes
            $errs[] = "Failed to retrieve gloves";
            $errCodes[] = 404;
        } else {
            $success = "Gloves fetched successfully";
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
            echo json_encode(["msg" => $success, "data" => $gloves], JSON_FORCE_OBJECT);
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
