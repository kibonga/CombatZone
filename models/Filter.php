<?php
include "../config/routes.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Includes
    include MODELS . "/Helpers.php";
    include CONFIG . "/connection.php";

    // Variable
    // display($_POST);
    $type = $_POST['type'];

    // Determine what needs filtering
    switch ($type) {
        case 'gloves':
            $categories = $_POST['categories'];
            $brands = $_POST['brands'];
            // display($brands);
            $colors = $_POST['colors'];
            $search = $_POST['search'];
            $sort = $_POST['sort'];
            $range = $_POST['range'];
            $perPage = $_POST['perPage'];   // select 6, 12, 24
            $page = $_POST["page"];         // 1,2,3...Last

            $gloves = filterActiveGlovesQuery($categories, $brands, $colors, $search, $sort, $range, $perPage, $page);
            // display($gloves);
            echo json_encode($gloves, JSON_FORCE_OBJECT);
            http_response_code(200);
            break;
        case "messages":
            // Variables
            $sort = $_POST['sort'];
            $perPage = $_POST['perPage'];   // select 6, 12, 24
            $page = $_POST["page"];         // 1,2,3...Last
            $search = $_POST['search'];

            // Get id if set
            $id = null;
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];
            }
            // Filter messages
            $messages = filterMessagesQuery($search, $sort, $perPage, $page, $id);

            // Return result messages
            echo json_encode($messages, JSON_FORCE_OBJECT);
            http_response_code(200);
            break;
        case "orders":
            $sort = $_POST['sort'];
            $perPage = $_POST['perPage'];   // select 6, 12, 24
            $page = $_POST["page"];         // 1,2,3...Last
            $search = isset($_POST['search']) ? $_POST['search'] : "";

            // Get id if set
            $id = null;
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];
            }

            // Filter orders
            $orders = filterOrdersQuery($search, $sort, $perPage, $page, $id);

            // Return result orders
            echo json_encode($orders, JSON_FORCE_OBJECT);
            http_response_code(200);
            break;
        case "users":
            $sort = $_POST['sort'];
            $perPage = $_POST['perPage'];   // select 6, 12, 24
            $page = $_POST["page"];         // 1,2,3...Last
            $search = $_POST['search'];

            // Filter users
            $users = filterUsersQuery($sort, $search, $perPage, $page);

            // Return result users
            echo json_encode($users, JSON_FORCE_OBJECT);
            http_response_code(200);
            break;
    }
} else {
    // Unathorized access
    $errs[] = "Unauthorized access";
    echo json_encode($errs, JSON_FORCE_OBJECT);
    http_response_code(403);
}
