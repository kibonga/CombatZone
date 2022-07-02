<?php
if (!isset($_SESSION)) {
    session_start();
}
include "../../config/routes.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST) && isset($_SESSION['loggedUser'])) {
    // Includes 
    include MODELS . "/Helpers.php";
    include CONFIG . "/connection.php";
    include M_USERS . "/Users.php";


    // Check user role
    if ($_SESSION['loggedUser']->name_role == "admin") {
        // Admin wants to logout
        unset($_SESSION['loggedUser']);
        echo json_encode("Succesfully logged out", JSON_FORCE_OBJECT);
        http_response_code(201);
    } else if ($_SESSION['loggedUser']->name_role == "regular_user") {
        // We sending appended order inside Form Data
        // We stringified array of objects so it could be sent
        // Now we need to decode it
        // Important: $_POST is an array so it can be empty and still set
        if (isset($_POST['order'])) {
            $order = json_decode($_POST['order']);

            // Insert order into cart
            try {
                // Begin transaction
                $conn->beginTransaction();

                $errs = [];
                $errCodes = [];
                $success = [];

                $isUserIdAvailable = isAvailable("cart", "id_user", $order[0]->inUserID);
                if ($isUserIdAvailable) {
                    // That user id is not in the cart table
                    // INSERT
                    $isInserted = insertUserCart($order);
                    if ($isInserted) {
                        // Commit and then logout user
                        $success[] = "Successfully inserted into cart";
                    } else {
                        $errs[] = "Failed to insert data into cart for user with Id: {$order[0]->inUserID}";
                        $errCodes[] = 422;
                    }
                } else {
                    // There are rows with that users id
                    // DELETE, INSERT
                    $isDeleted = deleteAllFromTableWhere("cart", "id_user", $order[0]->inUserID);
                    if ($isDeleted) {
                        // Successfully deleted user data from cart
                        $isInserted = insertUserCart($order);
                        if ($isInserted) {
                            // Commit and then logout user
                            $success[] = "Successfully inserted into cart";
                        } else {
                            $errs[] = "Failed to insert data into cart for user with Id: {$order[0]->inUserID}";
                            $errCodes[] = 422;
                        }
                    } else {
                        $errs[] = "Failed to delete user data from cart";
                        $errCodes[] = 404;
                    }
                }

                if (!empty($errs)) {
                    $conn->rollback();
                    // Log all of the errors
                    echo json_encode($errs, JSON_FORCE_OBJECT);
                    http_response_code($errCodes[0]);
                } else {
                    // There were no errors, so we can commit 
                    $conn->commit();
                    unset($_SESSION['loggedUser']);
                    echo json_encode("Succesfully added items to cart and logged out", JSON_FORCE_OBJECT);
                    http_response_code(201);
                }
            } catch (PDOException $ex) {
                // Error, some exception thrown
                $errs[] = $ex->getMessage();
                echo json_encode($errs, JSON_FORCE_OBJECT);
                http_response_code(422);
            }
        } else {
            // Post is an empty array, which means the cart is empty
            // Post can be empty so nothing is being inserted
            // $errs[] = "No appropriate data was sent";
            // Just because order is not set doesn't mean that there is nothing in cart for that user
            // display($_SESSION['loggedUser']->id_user);
            $isUserIdAvailable = isAvailable("cart", "id_user", $_SESSION['loggedUser']->id_user);
            if (!$isUserIdAvailable) {
                // User is NOT available, aka there are items in cart"
                // We emptied the Local storage so we need to update the cart
                $isDeleted = deleteAllFromTableWhere("cart", "id_user", $_SESSION['loggedUser']->id_user);
                if (!$isDeleted) {
                    $errs[] = "Failed to delete user data from cart";
                    $errCodes[] = 404;
                }
            }

            unset($_SESSION['loggedUser']);
            echo json_encode("Succesfully logged out", JSON_FORCE_OBJECT);
            http_response_code(201);
        }
    }
} else {
    // Unauthorized access: Not POST or it's not User(admin/customer)
    $errs[] = "Unauthorized access";
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code(403);
}
