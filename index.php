<?php
session_start();
// session_destroy();
ob_start();
// Routes will be availble in every View
include "./config/routes.php";
include CONFIG . "/connection.php";
include MODELS . "/Helpers.php";
// Includes
defineNav();
// Switch
if (isset($_GET["page"]) && isset($_GET["page"]) && isView($_GET["page"])) {
    getViewData($_GET['page']);
    $user = "Unknown user";
    if (isset($_SESSION['loggedUser'])) {
        switch ($_SESSION['loggedUser']->name_role) {

            case "regular_user":
                $user = $_SESSION['loggedUser']->email_user;
                break;
            case "admin":
                $user = "admin";
                break;
            default:
                $user = "Unknown user";
        }
    }
    pageVisitsWrite($_GET["page"], $user);
    include FIXED . "/head.php";
    include FIXED . "/nav.php";
    switch ($_GET["page"]) {

            // IMPORTANT
            // 1. Define view in database
            // 2. Define view in array of allowed views Helpers.php -> isView()
            // 3. Define switch case "page" : (SERVER SIDE)
            // 4. Define switch case "page" : (CLIENT SIDE) 

            // GENERAL
        case "customer":
            include V_CUSTOMER . "/customer.php";
            break;
        case "author":
            include V_PUBLIC . "/author.php";
            break;
        case "contact":
            include V_PUBLIC . "/contact.php";
            break;
        case "shop":
            include V_PUBLIC . "/shop.php";
            break;
        case "about":
            include V_PUBLIC . "/about.php";
            break;
        case "glove":
            include V_PUBLIC . "/glove.php";
            break;
        case "cart":
            // Even though it's general, only users can access it (customers can actaully only buy)
            include V_USERS . '/cart.php';
            break;


            // AUTH
        case "register":
            include V_AUTH . "/register.php";
            break;
        case "login":
            include V_AUTH . "/login.php";
            break;

            // CUSTOMER


            // ADMIN
        case "admin-dashboard":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-dashboard.php";
            break;
        case "admin-orders":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-orders.php";
            break;
        case "admin-gloves":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-gloves.php";
            break;
        case "admin-users":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-users.php";
            break;
        case "admin-messages":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-messages.php";
            break;
        case "admin-message":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-message.php";
            break;
        case "admin-order":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-order.php";
            break;
        case "admin-user":
            include FIXED . "/admin.php";
            include V_ADMIN . "/admin-user.php";
            break;

            // Index
        case "index":
            include V_PUBLIC . "/index.php";
            break;
    }
} else {
    getViewData("index");
    include FIXED . "/head.php";
    include FIXED . "/nav.php";
    include V_PUBLIC . "/index.php";
}

// Footer
include FIXED . "/footer.php";
ob_flush();
