<?php
header('Content-Type: application/json');

// Session
if (!isset($_SESSION)) {
    session_start();
}
// Includes (routes, helpers)
include_once "../../config/routes.php";
include MODELS . "/Helpers.php";

// include M_USERS . "/Users.php";

// Form submitted
if (isset($_POST['btnLogin'])) {
    // Includes (connection, users model)
    include CONFIG . "/connection.php";
    include M_USERS . "/Users.php";
    include MODELS . "/Validation.php";
    include MODELS . "/Patterns.php";
    // Try
    try {
        $conn->beginTransaction();
        // Inputs
        $inEmail = $_POST['inEmail'];
        $inPass = $_POST['inPass'];

        // Input validation
        $valid = new Validation();
        // $valid->name('Email')->value($inEmail)->customPattern($regEmail)->required();
        // $valid->name('Password')->value($inPass)->customPattern($regPass)->required();

	$lock = null;
        $errs = [];
        $errCodes = [];
        $success = [];

        // if ($valid->isSuccess()) {
        if (True) {
            // Form is valid

            $isEmail = isEmail($inEmail);
            if ($isEmail) {
                // There is user with that email
                // Hash
                $passSha = hash("sha256", $inPass);
                $isUser = fetchUser($inEmail, $passSha);

                // Try getting the user
                if ($isUser) {
                    $_SESSION['loggedUser'] = $isUser;
                    if ($isUser->name_role == "admin") {
                        // ADMIN logged in
                        $success = "Admin logged in sucessfully";
                    } else {
                        // REGULAR USER logged in
                        $isCartActive = isAvailable("cart", "id_user", $isUser->id_user);
                        if (!$isCartActive) {
                            // Successfull login, fetch the cart
                            // Customer has items in cart
                            $isCart = selectAllFromCartWhereId($isUser->id_user);
                            if (empty($isCart)) {
                                // Customer logged in but failed to retreive data from cart
                                $errs[] = "Failed to retreive cart";
                                $errCodes[] = 422;
                            } else {
                                $success[] = "Customer logged in and fetched cart succesfully";
                            }
                        } else {
                            // Success
                            // Customer doesn't have items in cart, success
                            $success[] = "Customer logged in succesfully, no items in cart";
                        }
                    }
                } else {
                    // We know the email is good but, we don't want to show that
                    // We only log error to failed login in if we know that the user
                    // who is trying to login is registered
                    $errs[] = "Invalid email, password or account is locked (failed login)";
                    $errCodes[] = 404;
                    // Log the failed attempt into database
                    $isInsert = insertIntoFailedLogin($inEmail);
                    if ($isInsert) {
                        // Successfully inserterd into Failed Login
                        $isLockCount = isLockUserCount($inEmail);

                        $errs[] = "Tried to login $isLockCount times (last 5 min)";
                        $errCodes[] = 422;
                        $lock = true;

                        // User tried to login more than 5 times in last 5 mins
                        // User will be locked
                        if ((int)$isLockCount >= 5) {
                            $isLockUser = lockUser($inEmail);
                            if (!$isLockUser) {
                                // Failed to lock the user
                                $errs[] = "Failed to lock the user";
                                $errCodes[] = 422;
                            } else {
                                $errs[] = "Your account has been locked";
                                $errCodes[] = 422;
                            }
                        } 
                    } else {
                        // Failed to insert into Failed Login table
                        $errs[] = "Failed to insert into failed login";
                        $errCodes[] = 422;
                    }
                }
            } else {
                // There is no such user with that email
                // But here we know that there is no user with that email, so we
                // don't log error into failed_login
                $errs[] = "Invalid email or password";
                $errCodes[] = 404;
            }
        } else {
            // Validation failed
            // Unprocessable entity
            $errs[] = $valid->errors;
            $errCodes[] = 422;
        }

        // Rollback or commit
        // Check if there were any errors
        if (!empty($errs)) {
            if($lock) {
                $conn->commit();
            }
            else {
                $conn->rollback();
            }
            echo json_encode($errs, JSON_FORCE_OBJECT);
            http_response_code($errCodes[0]);
        } else {
            $conn->commit();
            if (empty($isCart)) {
                echo json_encode(["msg" => $success, "user" => $isUser->name_role], JSON_FORCE_OBJECT);
            } else {
                echo json_encode(["msg" => $success, "user" => $isUser->name_role, "cart" => $isCart], JSON_FORCE_OBJECT);
            }
            http_response_code(200);
        }
    }
    // Catch err
    catch (PDOException $ex) {
        // Try block failed
        $errs[] =  $ex->getMessage();
        echo json_encode($errs, JSON_FORCE_OBJECT);
        http_response_code(422);
    }
} else {
    // Unauthorized access
    $errs[] = "Unauthorized access";
    $errCodes[] = 403;
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code($errCodes[0]);
}
