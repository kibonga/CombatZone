<?php
header('Content-Type: application/json');
include "../../../../config/routes.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        // Id was set

        // Includes
        include MODELS . "/Helpers.php";
        include CONFIG . "/connection.php";
        include "../Admin.php";

        // Variables
        $id = $_POST['id'];
        $errs = [];
        $errCodes = [];

        try {
            //Begin Transaction
            $conn->beginTransaction();

            // Checks if there is a glove with that id
            $isGloveAndActive = isGloveAndActive($id);
            if (!$isGloveAndActive) {
                $errs[] = "There is no glove with that id";
                $errCodes[] = 404;
            } else {
                // Checks if the glove with that id is removed
                $isRemoved = removeGlove($id);
                if (!$isRemoved) {
                    $errs[] = "Failed to remove glove";
                    $errCodes[] = 422;
                } else {
                    $success = "Glove removed successfully";
                }
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
                echo json_encode(["msg" => $success], JSON_FORCE_OBJECT);
                http_response_code(201);
            }
        } catch (PDOException $ex) {
            // Try block failed
            $resp = ["msg" => $ex->getMessage()];
            echo json_encode($resp, JSON_FORCE_OBJECT);
            http_response_code(422);
        }
    } else {
        // Id was not sent 
        $errs[] = "Id was not sent";
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code(422);
    }
} else {
    // Unauthorized access
    $errs[] = "Method is not POST which means you are a Terrorist";
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code(403);
}
