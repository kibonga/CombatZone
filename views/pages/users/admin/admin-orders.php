<?php
if (!isset($_SESSION['loggedUser'])) {
    header("Location: index.php");
} else {
    $user = $_SESSION['loggedUser'];
    if ($user->name_role != "admin") {
        // User is unauthorized
        header("Location: index.php");
    }
}
$tableId = "ordersTableBody";
$tableCount= "ordersCount";
$type = "orders";
$tableColumns = ["No.", "Customer", "Items", "Total", "Date", "More details"];
$tableIcon = "receipt_long";
include FIXED . "/admin-table.php";
