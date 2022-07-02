<?php
header('Content-Type: application/json');
// Session
if (!isset($_SESSION)) {
    session_start();
}
// Includes (routes, helpers)
include_once "../../../config/routes.php";
include MODELS . "/Helpers.php";
include CONFIG . "/connection.php";
include "./Customer.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST)) {
        // Parse json data, and get array of objects
        $data = json_decode($_POST['order']);

        $errs = [];
        $errCodes = [];

        // $errs[] = 1;

        try {
            $conn->beginTransaction();
            // Check if data is empty
            if (empty($data)) {
                $errs[] = "Data is empty";
                $errCodes[] = 422;
            } else {
                // Try to Insert new order detail
                $isOrderDetailId = insertOrderDetail($data[0]->id_user);
                if (!$isOrderDetailId) {
                    // Failed to insert new Order detail
                    $errs[] = "Failed to insert new Order detail";
                    $errCodes[] = 422;
                } else {
                    // Try to get glove_size from gloveSize table
                    foreach ($data as $i => $o) {
                        $isGloveSizeId = returnGloveSize($o->id_size, $o->id_glove);
                        if (!$isGloveSizeId) {
                            // Invalid glove size combination
                            $errsGloveSize[] = "Invalid glove size combination: Size: " . $o->name_size . " " . $o->measure . " and Glove: " . $o->name_glove;
                        } else {
                            // Current glove size id is valid
                            $isInserted = insertOrderLine($isOrderDetailId, $isGloveSizeId, $o->price_purchase, $o->quantity);
                            if (!$isInserted) {
                                // Failed to insert new Order Line
                                $errsOrderLine[] = "Failed to insert new order line for: Glove: " . $o->name_glove . " with size: " . $o->name_size . " " . $o->measure;
                            }
                        }
                    }
                }
            }


            if (isset($errsGloveSize) && !empty($errsGloveSize)) {
                $errs[] = $errsGloveSize;
                $errCodes[] = 404;
            }
            if (isset($errsOrderLine) && !empty($errsOrderLine)) {
                $errs[] = $errsOrderLine;
                $errCodes[] = 422;
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
                // $conn->rollback();
                // Order and Order detail were inserted
                $success = "Order inserted successfully";
                echo json_encode(['msg' => $success], JSON_FORCE_OBJECT);
                http_response_code(201);
            }
        } catch (PDOException $ex) {
            // Error, some exception thrown
            $errs[] = $ex->getMessage();
            echo json_encode($errs, JSON_FORCE_OBJECT);
            http_response_code(422);
        }
    }
} else {
    // Unauthorized access
    $errs[] = "Unauthorized access";
    $errCodes[] = 403;
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code($errCodes[0]);
}
