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
$tableId = "messagesTableBody";
$tableCount= "messagesCount";
$type = "messages";
$tableColumns = ["No.", "Full name", "Email", "Subject", "Date", "More details"];
$tableIcon = "drafts";
include FIXED . "/admin-table.php";
