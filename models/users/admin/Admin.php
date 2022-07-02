<?php
// INSERT/UPDATE/DELTE functions 
// (everyone can read(some) but not everyone can execute(only admin))

// Insert glove in the Glove table
function insertGlove($cat, $brand, $color, $name, $desc)
{
    global $conn;
    $query = "INSERT INTO glove (id_cat, id_brand, id_color, name_glove, desc_glove) VALUES (?, ?, ?, ?, ?)";
    $prep = $conn->prepare($query);

    if ($prep->execute([$cat, $brand, $color, $name, $desc])) {
        return $conn->lastInsertId();
    }
}
// Update glove in the Glove table 
function updateGlove($id, $cat, $brand, $color, $name, $desc)
{
    global $conn;
    $query = "UPDATE glove SET id_cat=?, id_brand=?, id_color=?, name_glove=?, desc_glove=? WHERE id_glove = ?";
    $prep = $conn->prepare($query);
    return $prep->execute([$cat, $brand, $color, $name, $desc, $id]);
}
// Insert sizes selected in checkboxes
function insertGloveSizes($sizes, $glove_id)
{
    global $conn;
    $query = "INSERT INTO glove_size (id_size, id_glove) VALUES(?, ?)";
    $prep = $conn->prepare($query);
    foreach ($sizes as $i => $s) {
        if (!$prep->execute([$s, $glove_id])) {
            return false;
        }
    }
    return true;
}
function deleteGloveSizes($id)
{
    global $conn;
    $time = date("Y-m-d H:i:s");
    $query = "UPDATE glove_size SET date_remove='$time' WHERE id_glove = ? AND date_remove IS NULL";
    // display($query);
    $prep = $conn->prepare($query);
    return $prep->execute([$id]);
}
// function deleteGloveSizes($id)
// {
//     global $conn;
//     $query = "DELETE FROM glove_size WHERE id_glove = ?";
//     $prep = $conn->prepare($query);
//     return $prep->execute([$id]);
// }
// Insert glove images
function insertImages($normal_img, $thumb_img, $glove_id)
{
    global $conn;
    $query = "INSERT INTO image(normal_img, thumb_img, id_glove) VALUES(?, ?, ?)";
    $prep = $conn->prepare($query);
    return $prep->execute([$normal_img, $thumb_img, $glove_id]);
}
// Update glove images 
function updateImages($normal_img, $thumb_img, $glove_id)
{
    global $conn;
    $query = "UPDATE image (id_size, id_glove) VALUES(?, ?)";
    $query = "UPDATE image SET normal_img=?, thumb_img=? WHERE id_glove = ?";
    $prep = $conn->prepare($query);
    return $prep->execute([$normal_img, $thumb_img, $glove_id]);
}
// Insert glvoe price
function insertGlovePrice($price, $id)
{
    global $conn;
    $query = "INSERT INTO price(id_glove, price) VALUES(?, ?)";
    $prep = $conn->prepare($query);
    return $prep->execute([$id, $price]);
}
// Update glove price
function updateGlovePrice($price, $id)
{
    global $conn;
    $query = "UPDATE price SET price=? WHERE id_glove = ?";
    $prep = $conn->prepare($query);
    return $prep->execute([$price, $id]);
}
// Remove glove from Database (SET date_removed)
function removeGlove($id) {
    global $conn;
    $time = date('Y-m-d H:i:s');
    $query = "UPDATE glove SET date_removed='$time' WHERE id_glove=?";
    $prep = $conn->prepare($query);
    if($prep->execute([$id])) {
        return true;
    }
}
// Selects all orders, displays it in admin-orders
function selectAllOrders() {
    global $conn;

    $query = "SELECT u.first_name, u.last_name, SUM(ol.price_purchase * ol.quantity) as total, COUNT(ol.id_order_line) FROM user u INNER JOIN order_detail od ON u.id_user = od.id_user INNER JOIN order_line ol ON od.id_order_detail = ol.id_order_detail GROUP BY od.id_order_detail";
    return $conn->query($query)->fetchAll();
}

// Checks if data is available(no duplicates)
function isUserID($id_user)
{
    global $conn;
    // Table/Column name cannot be parameterized
    // display($email);
    $query = "SELECT * FROM user WHERE id_user = ?";
    $prep = $conn->prepare($query);
    // display($query);
    // display($conn->query($query)->fetch());

    // display($query);

    if ($prep->execute([$id_user])) {
        // Counts the number of rows
        // display($prep->fetch());
        return $prep->fetch();
    }
}


// Update sizes selected in checkboxes 
// function updateGloveSizes($sizes, $glove_id)
// {
//     global $conn;
//     $query = "UPDATE glove_size SET id_size=? WHERE id_glove = ?";
//     $prep = $conn->prepare($query);
//     foreach ($sizes as $i => $s) {
//         if (!$prep->execute([$s, $glove_id, $glove_id])) {
//             return false;
//         }
//     }
//     return true;
// }
