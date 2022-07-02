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

    // Variables
    $brand = $_POST['ddlBrand'];
    $cat = $_POST['ddlCat'];
    $color = $_POST['ddlColor'];
    $sizes = isset($_POST['cbSize']) ? $_POST['cbSize'] : null;
    $name = $_POST['inName'];
    $price = $_POST['inPrice'];
    $desc = $_POST['taDesc'];
    $img = $_FILES['fileImg'];

    // Validation
    $valid = new Validation();
    // $valid->name("Glove name")->value($name)->customPattern($regGloveName)->required();
    // $valid->name("Price")->value($price)->customPattern($regPrice)->required();
    // $valid->name("Description")->value($desc)->required();

    // Errors array
    $errs = [];
    $errCodes = [];

    // Check dropdowns and checkboxes
    if (!isset($brand) || empty($brand)) {
        $errs[] = "Must select a brand";
        $errCodes[] = 422;
    };
    if (!isset($cat) || empty($cat)) {
        $errs[] = "Must select a category";
        $errCodes[] = 422;
    };
    if (!isset($color) || empty($color)) {
        $errs[] = "Must select a color";
        $errCodes[] = 422;
    };
    if (empty($sizes)) {
        $errs[] = "Must select at least one size";
        $errCodes[] = 422;
    }
    // Check input fields
    // if (!$valid->isSuccess()) {
    if (false) {
        // Validation failed
        $errs[] = $valid->errors;
        $errCodes[] = 422;
    }

    if (empty($errs)) {
        // Validation success, errors array is empty
        // Try
        try {
            //Begin Transaction
            $conn->beginTransaction();

            // Image parts
            // Image is required
            $n_name = time() . "__" . $img['name'];
            $n_type = $img['type'];
            $n_tmp = $img['tmp_name'];
            $n_size = $img['size'];

            // Allowed types
            $allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];

            // Check for image errors
            if (!in_array($n_type, $allowed_types)) {
                $errs[] = "Image type is not valid";
                $errCodes[] = 422;
            }
            if ($n_size > 3000000) {
                $errs[] = "Uploaded image is too large";
                $errCodes[] = 422;
            }

            if (!count($errs)) {
                // Creating thumnail image
                list($n_w, $n_h) = getimagesize($n_tmp);
                $n_img = imagecreatefromstring(file_get_contents($n_tmp));
                $t_w = 150;
                $t_h = ($t_w / $n_w) * $n_h;
                $t_img = imagecreatetruecolor($t_w, $t_h);
                imagecopyresampled($t_img, $n_img, 0, 0, 0, 0, $t_w, $t_h, $n_w, $n_h);
                // Definig name/path
                $t_name = "thumb__" . $n_name;
                $t_path = "assets/img/gloves/thumbnail/{$t_name}";

                // Upload thumbnail img
                switch ($n_type) {
                    case "image/png":
                        imagepng($t_img, '../../../../' . $t_path);
                        break;
                    default:
                        imagejpeg($t_img, '../../../../' . $t_path);
                }

                // Upload normal img
                $n_path = "assets/img/gloves/normal/" . $n_name;
                if (move_uploaded_file($n_tmp, '../../../../' . $n_path)) {
                    // Image uploaded successfully
                    $lastInsertedId = insertGlove($cat, $brand, $color, $name, $desc);
                    if ($lastInsertedId) {
                        // Glove is inserted
                        $isInserted = insertGloveSizes($sizes, $lastInsertedId);
                        if ($isInserted) {
                            $isInserted = insertGlovePrice($price, $lastInsertedId);
                            if ($isInserted) {
                                // Price is inserted
                                $isInserted = insertImages($n_name, $t_name, $lastInsertedId);
                                if ($isInserted) {
                                    // Image is inserted
                                    $success = "Glove addedd succesfully";
                                } else {
                                    $errs[] = "Failed to insert image";
                                    $errCodes[] = 422;
                                }
                            } else {
                                $errs[] = "Failed to insert glove price";
                                $errCodes[] = 422;
                            }
                        } else {
                            $errs[] = "Failed to insert glove sizes";
                            $errCodes[] = 422;
                        }
                    } else {
                        // Couldn't get the lastInsertedId
                        // Glove was not inserted
                        $errs[] = "Failed to insert glove";
                        $errCodes[] = 422;
                    }
                } else {
                    // Failed to upload the image
                    $errs[] = "Failed to upload image";
                    $errCodes[] = 422;
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
        }
        // Catch
        catch (PDOException $ex) {
            // Try block failed
            $errs[] = $ex->getMessage();
            echo json_encode($errs, JSON_FORCE_OBJECT);
            http_response_code(422);
        }
    } else {
        // Form validation failed (checkboxes, radio buttons, input fileds, textarea)
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code(422);
    }
} else {
    $errs[] = "Unauthorized access";
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code(403);
}
