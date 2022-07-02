<?php
// Insert new Order Detail
function insertOrderDetail($id)
{
    global $conn;

    $query = "INSERT INTO order_detail(id_user) VALUES(?)";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        return $conn->lastInsertId();
    }
}
// Return glove size from Glove size table
function returnGloveSize($id_size, $id_glove)
{
    global $conn;

    $query = "SELECT id_glove_size FROM glove_size WHERE id_size = ? AND id_glove = ?";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id_size, $id_glove])) {
        return $prep->fetch()->id_glove_size;
    }
}
// Insert new Order Line
function insertOrderLine($id_order_detail, $id_glove_size, $price_purchase, $quantity)
{
    global $conn;

    $query = "INSERT INTO order_line(id_order_detail, id_glove_size, price_purchase, quantity) VALUES(?, ?, ?, ?)";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id_order_detail, $id_glove_size, $price_purchase, $quantity])) {
        return true;
    }
}
