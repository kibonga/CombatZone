<?php
header('Content-Type: application/json');
include "../../../../config/routes.php";
if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST)) {
    // Includes 
    include MODELS . "/Helpers.php";
    include CONFIG . "/connection.php";
    include "../Admin.php";

    // Variables
    $id_cat = $_POST['cat'];

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Select available sizes
        $sizes = selectAvailableSizes($id_cat);
        if (!count($sizes) > 0) {
            // Failed to retrieve sizes
            $errs[] = "Failed to retrieve sizes";
            $errCodes[] = 404;
        } else {
            $success = "Sizes fetched successfully";
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
            echo json_encode(["msg" => $success, "data" => $sizes], JSON_FORCE_OBJECT);
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
