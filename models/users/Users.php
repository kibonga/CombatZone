<?php
// Login user
function fetchUser($email, $pass)
{
    // Connection
    global $conn;
    // Query
    $query = "SELECT * FROM user u JOIN role r ON u.id_role = r.id_role WHERE u.email_user = ? AND u.pass_user = ? AND u.isLock = 0";
    // Prepared Statement
    $prep = $conn->prepare($query);
    // Result
    if ($prep->execute([$email, $pass])) {
        $res = $prep->fetch();
        return count((array)$res) == 0 ? null : $res;
    }
}
// Register user
function registerUser($email, $passSha, $fname, $lname, $address, $phone)
{
    global $conn;

    $query = "INSERT INTO user (email_user, pass_user, first_name, last_name, address, phone_num) VALUES(?, ?, ?, ?, ?, ?)";
    $prep = $conn->prepare($query);
    if ($prep->execute([$email, $passSha, $fname, $lname, $address, $phone])) {
        $id = $conn->lastInsertId();
        $query = "SELECT u.id_user, u.email_user, u.pass_user, u.id_role, u.first_name, u.last_name, u.address, u.phone_num, r.name_role FROM user u INNER JOIN role r ON u.id_role = r.id_role WHERE id_user = ?";
        $prep = $conn->prepare($query);
        if ($prep->execute([$id])) {
            return $prep->fetch();
        }
    } else {
        return false;
    }
}
// Insert user's cart into cart table
function insertUserCart($order)
{
    global $conn;
    $errs = [];

    foreach ($order as $i => $o) {
        $id_user = $o->inUserID;
        $id_glove = $o->inGloveID;
        $id_size = $o->raSize;
        $name_glove = $o->inGloveName;
        $value_size = $o->raSizeName;
        $name_measure = $o->inMeasure;
        $id_brand = $o->inBrandID;
        $name_brand = $o->inBrandName;
        $id_cat = $o->inCatID;
        $name_cat = $o->inCatName;
        $id_color = $o->inColorID;
        $color_name = $o->inColor;
        $t_img = $o->inImg;
        $price = $o->inPrice;
        $quantity = $o->inQuantity;

        $query = "INSERT INTO cart(id_user, id_glove, id_size, name_glove, value_size, name_measure, id_brand, name_brand, id_cat, name_cat, id_color, color_name, t_img, price, quantity) VALUES(?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $prep = $conn->prepare($query);
        if (!$prep->execute([$id_user, $id_glove, $id_size, $value_size, $name_glove, $name_measure, $id_brand, $name_brand, $id_cat, $name_cat, $id_color, $color_name, $t_img, $price, $quantity])) {
            return true;
        }
    }

    if (empty($errs)) {
        return true;
    } else {
        return false;
    }
}
// Select all from cart WHERE
function selectAllFromCartWhereId($id)
{
    global $conn;
    $query = "SELECT id_user as inUserID, id_glove as inGloveID, id_size as raSize, name_glove as inGloveName, value_size as raSizeName, name_measure as inMeasure, id_brand as inBrandID, name_brand as inBrandName, id_cat as inCatID, name_cat as inCatName, id_color as inColorID, color_name as inColor, t_img as inImg, price as inPrice, quantity as inQuantity FROM cart WHERE id_user = ?";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        return $prep->fetchAll();
    }
}

// Insert into failed login
function insertIntoFailedLogin($email_user)
{
    global $conn;

    $query = "INSERT INTO failed_login(email_user) VALUES(?)";
    $prep = $conn->prepare($query);
    if ($prep->execute([$email_user])) {
        return true;
    }
}

// Lock user 
function lockUser($email_user)
{
    global $conn;
    $query = "UPDATE user SET isLock=1 WHERE email_user = ? ";
    $prep = $conn->prepare($query);
    if ($prep->execute([$email_user])) {
        return true;
    }
}

// Unlock user 
function unlockUser($id_user)
{
    global $conn;
    $query = "UPDATE user SET isLock=0 WHERE id_user = ? ";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id_user])) {
        return true;
    }
}


// Delete login failed data for user
function deleteLoginFailedData($id_user, $email_user)
{
    global $conn;

    $query = "DELETE FROM failed_login WHERE email_user = ?";
    $prep = $conn->prepare($query);

    if ($prep->execute([$email_user])) {
        return true;
    }
}
