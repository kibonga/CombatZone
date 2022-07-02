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
$tableId = "usersTableBody";
$tableCount = "usersCount";
$type = "users";
$tableColumns = ["No.", "Customer", "Email", "Orders", "Total spent", "More details", "Account status"];
$tableIcon = "person";
include FIXED . "/admin-table.php";
