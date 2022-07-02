<?php
header('Content-Type: application/json');
include "../../../../config/routes.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Includes 
    include MODELS . "/Helpers.php";
    include CONFIG . "/connection.php";
    include MODELS . "/Validation.php";
    include MODELS . "/Patterns.php";
    include "../Admin.php";
    include "../../Users.php";
    // display($_POST);

    $id_user = $_POST['userID'];
    // $id_user = "666";

    $errs = [];
    $errCodes = [];
    $success;

    try {
        //Begin Transaction
        $conn->beginTransaction();

        // Check if it is registered user
        $isUser = isUserID($id_user);
        if ($isUser) {
            // User is registered
            $email_user = $isUser->email_user;
            $isDelete = deleteLoginFailedData($id_user, $email_user);

            if (!$isDelete) {
                // No such user
                $errs[] = "Failed to remove failed login data";
                $errCodes[] = 422;
            } else {
                // Update isLock column in user
                $isUpdate = unlockUser($id_user);
                if (!$isUpdate) {
                    $errs[] = "Failed to unlock user";
                    $errCodes[] = 422;
                } else {
                    $success = "User succefully unlocked";
                }
            }
        } else {
            // No such user
            $errs[] = "There is no such user";
            $errCodes[] = 404;
        }


        // Rollback or Commit
        if (!empty($errs)) {
            // Rollback
            $conn->rollback();
            echo json_encode($errs, JSON_FORCE_OBJECT);
            http_response_code($errCodes[0]);
        } else {
            // Commit
            $conn->commit();
            if (isset($t_img)) {
                // Send thumbnail image name, if exists
                echo json_encode(['msg' => $success, "img" => $t_name], JSON_FORCE_OBJECT);
            } else {
                // Image was not uploaded but is success
                echo json_encode(['msg' => $success], JSON_FORCE_OBJECT);
            }
            http_response_code(201);
        }
    } catch (PDOException $ex) {
        // Try block failed
        $errs[] = $ex->getMessage();
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code(422);
    }
} else {
    // Unathorized access
    $errs[] = "Unauthorized access";
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code(403);
}
