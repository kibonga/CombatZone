<?php

// Displaying data
function display($content)
{
    echo "<pre>";
    print_r($content);
    echo "</pre>";
}

// Getting all meta data
function getViewData($page)
{
    global $conn;
    $query = "SELECT * FROM view WHERE name_view = ? ";
    $prep = $conn->prepare($query);
    if ($prep->execute([$page])) {
        $res = $prep->fetch();
        define("HEADING_PRIMARY", $res->heading_primary);
        define("HEADING_SECONDARY", $res->heading_secondary);
        define("FOOTER", $res->footer_view);
        define("META_DESC", $res->meta_desc);
        define("META_KEYWORDS", $res->meta_keywords);
        define("TITLE", $res->title_view);
    }
}

// Define nav
function defineNav()
{
    define("GENERAL_NAV", getNav('general'));
    define("AUTH_NAV", getNav('auth'));
    define("ADMIN_NAV", getNav('admin'));
    define("CUSTOMER_NAV", getNav('customer'));
}

// Get nav
function getNav($name)
{
    global $conn;
    $query = "SELECT * FROM view v INNER JOIN nav n ON v.nav_type = n.id_nav WHERE n.nav_name = ?";
    $prep = $conn->prepare($query);
    if ($prep->execute([$name])) {
        // Constanst cannot be defined with objects
        // Must use arrays
        return $prep->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Select all from table
function selectAllFromTable($table)
{
    global $conn;
    $query = "SELECT * FROM $table";
    $res = $conn->query($query)->fetchAll();
    return $res;
}

// Delete all from table WHERE
function deleteAllFromTableWhere($table, $where, $param)
{
    global $conn;
    $query = "DELETE FROM $table WHERE $where = ?";
    $prep = $conn->prepare($query);
    if ($prep->execute([$param])) {
        return true;
    }
}

// Check if view is valid
function isView($view)
{
    $arr = [
        // Default
        "index",

        // Auth
        "login",
        "register",

        // Admin
        "admin-dashboard",
        "admin-orders",
        "admin-gloves",
        "admin-users",
        "admin-messages",
        "admin-message",
        "admin-order",
        "admin-user",

        // Customer
        "customer",

        // General
        "cart",
        "shop",
        "about",
        "contact",
        "author",
        "glove"
    ];
    return in_array($view, $arr);
}

// Checks if data is available(no duplicates)
function isAvailable($table, $where, $param)
{
    global $conn;
    // Table/Column name cannot be parameterized
    $query = "SELECT " . $where . " as count FROM " . $table . " WHERE " . $where . " = ?";
    $prep = $conn->prepare($query);

    if ($prep->execute([$param])) {
        // Counts the number of rows
        $res = $prep->rowCount();
        return !$res;
    }
}
// Checks if data is available(no duplicates)
function isEmail($email)
{
    global $conn;
    // Table/Column name cannot be parameterized
    // display($email);
    $query = "SELECT * FROM user WHERE email_user = ?";
    $prep = $conn->prepare($query);
    // display($query);
    // display($conn->query($query)->fetch());

    // display($query);

    if ($prep->execute([$email])) {
        // Counts the number of rows
        // display($prep->fetch());
        return $prep->fetchColumn();
    }
}

// Check if user should be diabled
function isLockUserCount($email)
{
    global $conn;
    $query = "SELECT COUNT(*) FROM failed_login WHERE email_user = ? AND login_date > DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND email_user <> 'ko.bi.reko.da.je.mala01@gmail.com' ";
    $prep = $conn->prepare($query);
    if ($prep->execute([$email])) {
        // display($prep->fetchAll());
        return $prep->fetchColumn();
    }
}



//////////////////////////////////////////////
// Functions for gloves which everyone can use


// Count all active gloves
function countAllActiveGloves($search = "")
{
    global $conn;
    $where = "";
    if ($search) {
        $where = " AND (LOWER(name_glove) LIKE '%$search%') ";
        // $where = " AND name_glove = ?";
        // (LOWER(u.first_name) LIKE '%$search%'
    }
    $query = "SELECT COUNT(*) FROM glove WHERE date_removed IS NULL" . $where;
    // display($query);
    // if ($search) {
    //     $prep = $conn->prepare($query);
    //     if ($prep->execute([$search])) {
    //         return $prep->fetchColumn();
    //     }
    // } else {
    //     return $conn->query($query)->fetchColumn();
    // }
    return $conn->query($query)->fetchColumn();
}

// Count all from table
function countAllFromTable($table)
{
    global $conn;
    $query = "SELECT COUNT(*) FROM $table";
    return $conn->query($query)->fetchColumn();
}

// Count all from table
function countAllOrdersUsersMessages($table, $search = "")
{
    global $conn;
    // display($table);
    $where = "";
    if ($search) {
        $where = " WHERE (LOWER(u.first_name) LIKE '%$search%' OR LOWER(u.last_name) LIKE '%$search%')";
    }
    if ($table == "users") {
        // $query = "SELECT COUNT(DISTINCT od.id_user) FROM order_detail od INNER JOIN user u ON u.id_user = od.id_user " . $where;
        // display("Are we in here users");
        $query = "SELECT COUNT(DISTINCT od.id_user) FROM order_detail od INNER JOIN user u ON u.id_user = od.id_user " . $where;
    } else if ($table == "order_detail") {
        // display("Are we in here order");
        $query = "SELECT COUNT(od.id_user) FROM order_detail od INNER JOIN user u ON u.id_user = od.id_user " . $where;
    } else if ($table == "message") {
        // display("Are we in here message");
        $where2 = " WHERE (LOWER(msg_first_name) LIKE '%$search%' OR LOWER(msg_last_name) LIKE '%$search%')";
        $query = "SELECT COUNT(*) FROM message " . $where2;
    }
    // display($query);
    if ($search) {
        $prep = $conn->prepare($query);
        if ($prep->execute([$search])) {
            return $prep->fetchColumn();
        }
    } else {
        return $conn->query($query)->fetchColumn();
    }
}

// Count all from table where
function countAllFromTableWhere($table, $where, $param)
{
    global $conn;
    $query = "SELECT COUNT(*) FROM $table WHERE $where = ?";
    $prep = $conn->prepare($query);
    if ($prep->execute([$param])) {
        return $prep->fetchColumn();
    } else {
        return false;
    }
}

// Select all from table where
function selectAllFromTableWhere($table, $where, $param)
{
    global $conn;
    try {
        $isAvailable = isAvailable($table, $where, $param);
        if (!$isAvailable) {
            $query = "SELECT * FROM $table WHERE $where = ?";
            $prep = $conn->prepare($query);
            if ($prep->execute([$param])) {
                return $prep->fetch();
            }
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}

// Select current glove values
function selectCurrentGloveValues($id)
{
    global $conn;
    $query = "SELECT g.id_glove as id, g.name_glove as name, g.desc_glove as description, g.date_added, clr.id_color as colorID, clr.color_name as color, c.id_cat as catID, c.name_cat as cat, b.id_brand as brandID, b.name_brand as brand, GROUP_CONCAT(s.id_size SEPARATOR ',') as sizesID, GROUP_CONCAT(s.value_size SEPARATOR ',') as sizes, m.name_measure as measure, p.price, i.normal_img as n_img, i.thumb_img as t_img FROM glove as g INNER JOIN color clr ON g.id_color = clr.id_color INNER JOIN brand b ON b.id_brand = g.id_brand INNER JOIN category c ON c.id_cat = g.id_cat INNER JOIN glove_size gs ON g.id_glove = gs.id_glove INNER JOIN size s ON s.id_size = gs.id_size INNER JOIN image i ON i.id_glove = g.id_glove INNER JOIN measure m ON m.id_measure = s.id_measure INNER JOIN price p ON p.id_glove = g.id_glove WHERE g.id_glove = ? AND g.date_removed IS NULL AND gs.date_remove IS NULL GROUP BY g.id_glove";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        return $prep->fetch();
    }
}

// Selects All available sizes
function selectAvailableSizes($id)
{
    global $conn;

    $query = "SELECT m.name_measure as measure, s.value_size as size, s.id_size as id FROM size s INNER JOIN category_size cs ON s.id_size = cs.id_size INNER JOIN category c ON c.id_cat = cs.id_cat INNER JOIN measure m ON m.id_measure = s.id_measure WHERE c.id_cat = ? ";
    $prep = $conn->prepare($query);

    if ($prep->execute([$id])) {
        $res = $prep->fetchAll();
        return $res;
    }
}

// Check if glove(row) exists for ID plus if date_deleted is NULL
function isGloveAndActive($id)
{
    global $conn;
    $query = "SELECT COUNT(*) FROM glove WHERE id_glove = ? AND date_removed IS NULL";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        return $prep->fetchColumn();
    }
}

// Select all active gloves
function selectAllActiveGloves()
{
    global $conn;
    $query = "SELECT g.id_glove as id, g.name_glove as name, g.desc_glove as description, g.date_added, clr.color_name as color, c.name_cat as cat, b.name_brand as brand, GROUP_CONCAT(s.value_size SEPARATOR ', ') as sizes, m.name_measure as measure, i.normal_img as n_img, i.thumb_img as t_img FROM glove as g INNER JOIN color clr ON g.id_color = clr.id_color INNER JOIN brand b ON b.id_brand = g.id_brand INNER JOIN category c ON c.id_cat = g.id_cat INNER JOIN glove_size gs ON g.id_glove = gs.id_glove INNER JOIN size s ON s.id_size = gs.id_size INNER JOIN image i ON i.id_glove = g.id_glove INNER JOIN measure m ON m.id_measure = s.id_measure WHERE g.date_removed IS NULL AND gs.date_remove IS NULL GROUP BY g.id_glove";
    return $conn->query($query)->fetchAll();
}

// Filter Active Gloves 
function filterActiveGlovesQuery($categories, $brands, $colors, $search, $sort, $range, $perPage, $page)
{
    global $conn;
    // $num_rows = countAllActiveGloves($search);
    // display($num_rows);

    // // If number of rows divided by number of pages is greater than 0
    // // We define LIMIT $start $offset -> LIMIT 0(start from) 6(take next 6 rows)
    // $numOfPages = $num_rows / $perPage;
    // if ($num_rows % $perPage > 0) {
    //     // display("I GO IF");
    //     $start = ($page - 1) * $perPage;
    // } else {
    //     // display("I GO ELSE");
    //     $start = ($page - 1) * $perPage;
    // }
    // // Important to use ceil to get exact number of pages
    // // 2 per page, 7 total -> 7 % 2  -> 2 + 2 + 2 + 1 -> 4 pages
    // // 6 per page, 7 total -> 7 % 6  -> 6 + 1 -> 2 pages
    // $numOfPages = ceil($numOfPages);
    // // display($numOfPages);



    $where = " WHERE g.date_removed IS NULL AND gs.date_remove IS NULL ";
    // Every variable is a string so if it not empty string, we concatenate result
    if (!empty($categories)) {
        $where .= " AND c.id_cat IN ($categories)";
    }
    if (!empty($brands)) {
        $where .= " AND b.id_brand IN ($brands)";
    }
    if (!empty($colors)) {
        $where .= " AND clr.id_color IN ($colors)";
    }
    if (!empty($range)) {
        $where .= " AND p.price < $range";
    }
    if (!empty($search)) {
        $where .= " AND LOWER(g.name_glove) LIKE '%$search%'";
    }
    $where .= " GROUP BY g.id_glove ORDER BY ";

    switch ($sort) {
        case "latest":
            $where .= "g.date_added DESC";
            break;
        case "nameAsc":
            $where .= "g.name_glove ASC";
            break;
        case "nameDesc":
            $where .= "g.name_glove DESC";
            break;
        case "priceDesc":
            $where .= "p.price DESC";
            break;
        case "priceAsc":
            $where .= "p.price ASC";
            break;
        default:
            $where .= "g.id_glove ASC";
    }

    // Get total amount 
    $query = "SELECT g.id_glove as id, g.name_glove as name, g.desc_glove as description, g.date_added, clr.color_name as color, c.name_cat as cat, b.name_brand as brand, GROUP_CONCAT(s.value_size SEPARATOR ', ') as sizes, p.price, m.name_measure as measure, i.normal_img as n_img, i.thumb_img as t_img FROM glove as g INNER JOIN color clr ON g.id_color = clr.id_color INNER JOIN brand b ON b.id_brand = g.id_brand INNER JOIN category c ON c.id_cat = g.id_cat INNER JOIN glove_size gs ON g.id_glove = gs.id_glove INNER JOIN size s ON s.id_size = gs.id_size INNER JOIN image i ON i.id_glove = g.id_glove INNER JOIN measure m ON m.id_measure = s.id_measure INNER JOIN price p ON p.id_glove = g.id_glove ";
    $queryCount = $query . "" . $where;
    // $gloves = $conn->query($queryCount)->fetchAll();
    // display($gloves);
    $num_rows = count($conn->query($queryCount)->fetchAll());
    $resp = returnNumberOfPages($num_rows, $perPage, $page);
    $numOfPages = $resp[0];
    $start = $resp[1];
    $numOfPages = ceil($numOfPages);

    // Get products for one page (LIMIT)
    $where .= " LIMIT $start, $perPage";
    $query = $query . '' . $where;
    $gloves = $conn->query($query)->fetchAll();
    // display($query);
    // display($query);
    $res = array([
        "gloves" => $gloves,
        "pages" => $numOfPages,
        "total" => $num_rows
    ]);
    // display($res);
    return $res;
}

// Filter Orders
function filterOrdersQuery($search = "", $sort, $perPage, $page, $id)
{
    global $conn;

    if ($id) {
        $num_rows = countAllFromTableWhere("order_detail", "id_user", $id);
        // display($num_rows);
    } else {
        $num_rows = countAllOrdersUsersMessages("order_detail", $search);
    }
    // $num_rows = countAllOrdersUsersMessages("order_detail", $search);

    $resp = returnNumberOfPages($num_rows, $perPage, $page);
    $numOfPages = $resp[0];
    $start = $resp[1];
    $numOfPages = ceil($numOfPages);
    // display($numOfPages);

    if ($id) {
        $where = " WHERE u.id_user = ? ";
    } else {
        $where = " ";
    }

    if ($search) {
        $where .= " WHERE (LOWER(u.first_name) LIKE '%$search%' OR LOWER(u.last_name) LIKE '%$search%')";
    }

    // $resp = returnNumberOfPages($num_rows, $perPage, $page);
    // $numOfPages = $resp[0];
    // $start = $resp[1];

    // display($where);

    $query = "SELECT od.id_order_detail, u.first_name, u.last_name, u.id_user, SUM(ol.price_purchase * ol.quantity) as total, od.date_order as date, COUNT(ol.id_order_line) as count FROM user u INNER JOIN order_detail od ON u.id_user = od.id_user INNER JOIN order_line ol ON od.id_order_detail = ol.id_order_detail";
    // display($query);
    // Every query has this condition
    $query = $query . $where;
    // display($query);
    // Copy of the query, assignment not concataenation
    // We will use this query NOT to SELECT products, but to COUNT
    if ($search) {
        $queryCount = "SELECT COUNT(*) FROM order_detail od INNER JOIN user u ON u.id_user = od.id_user " . $where;
    } else {
        $queryCount = "SELECT COUNT(*) FROM order_detail od INNER JOIN user u ON u.id_user = od.id_user " . $where;
    }
    // display($queryCount);
    // Every query needs a group by
    $query .= " GROUP BY od.id_order_detail";

    if ($sort) {
        $query .= " ORDER BY ";
    }
    // display($sort);
    switch ($sort) {
        case "latest":
            // display("in here");
            $query .= " od.date_order DESC ";
            break;
        case "nameAsc":
            $query .= " u.first_name ASC ";
            break;
        case "nameDesc":
            $query .= "u.first_name DESC";
            break;
        case "priceDesc":
            $query .= "SUM(ol.price_purchase * ol.quantity) DESC";
            break;
        case "priceAsc":
            $query .= "SUM(ol.price_purchase * ol.quantity) ASC";
            break;
        case "numberOfItems":
            $query .= "COUNT(ol.id_order_line) DESC";
            break;
            // default:
            //     $where .= "od.id_order_detail ASC";
    }

    if ($id) {
        // Id was passed, getting all orders for specific user
        $prep = $conn->prepare($queryCount);
        if ($prep->execute([$id])) {
            $total = $prep->fetchColumn();
        }
    } else {
        // No Id was passed, getting all orders for all users
        // display($queryCount);
        $total = $conn->query($queryCount)->fetchColumn();
    }

    // Sets the limit of fetched rows
    $limit = " LIMIT $start, $perPage";
    $query = $query . $limit;

    // display($query);

    if ($id) {
        // Preparing query because we passed the parameter
        $prep = $conn->prepare($query);
        if ($prep->execute([$id])) {
            $orders = $prep->fetchAll();
        }
    } else {
        // Basic query, because there are no parameters
        // display($query);
        $orders = $conn->query($query)->fetchAll();
    }


    $res = array([
        "orders" => $orders,
        "pages" => $numOfPages,
        "total" => $total
    ]);
    // display($res);
    return $res;
}

// Filter Messages
function filterMessagesQuery($search, $sort, $perPage, $page, $id)
{
    global $conn;

    // if ($id) {
    //     $num_rows = countAllFromTableWhere("message", "msg_email", $id);
    // } else {
    //     $num_rows = countAllOrdersUsersMessages("message", $search);
    // }
    $num_rows = countAllOrdersUsersMessages("message", $search);
    // display($num_rows);

    $resp = returnNumberOfPages($num_rows, $perPage, $page);
    $numOfPages = $resp[0];
    $start = $resp[1];

    $where = " ";
    $order = " ";
    $limit = " ";

    if (!empty($search)) {
        $where .= " WHERE (LOWER(msg_first_name) LIKE '%$search%' OR LOWER(msg_last_name) LIKE '%$search%')";
    }

    if ($sort) {
        $order .= " ORDER BY ";
    }

    switch ($sort) {
        case "latest":
            // display("in here");
            $order .= " msg_date DESC ";
            break;
        case "nameAsc":
            $order .= " msg_first_name ASC ";
            break;
        case "nameDesc":
            $order .= "msg_first_name DESC";
            break;
        case "subject":
            $order .= "msg_subject ASC";
            // default:
            //     $where .= "od.id_order_detail ASC";
    }


    $query = "SELECT * FROM message ";
    $query = $query . $where . $order;

    $limit = " LIMIT $start, $perPage";
    $query = $query . $limit;

    $messages = $conn->query($query)->fetchAll();

    $res = array([
        "messages" => $messages,
        "pages" => $numOfPages,
        "total" => $num_rows
    ]);
    // display($res);
    return $res;
}

// Filter Users
function filterUsersQuery($sort, $search = "", $perPage, $page)
{
    global $conn;
    // $num_rows = countAllFromTable("user");
    // $num_rows = countAllOrdersUsersMessages("users", $search);
    // // display($num_rows);

    // $resp = returnNumberOfPages($num_rows, $perPage, $page);
    // $numOfPages = $resp[0];
    // $start = $resp[1];

    $where = " ";
    $order = " ";

    if (!empty($search)) {
        $where .= " AND (LOWER(u.first_name) LIKE '%$search%' OR LOWER(u.last_name) LIKE '%$search%')";
    }

    if ($sort) {
        $order .= " ORDER BY ";
    }

    switch ($sort) {
        case "latest":
            $order .= "u.date_register DESC";
            break;
        case "nameAsc":
            $order .= "u.first_name ASC";
            break;
        case "nameDesc":
            $order .= "u.first_name DESC";
            break;
        case "priceDesc":
            $order .= "p.price DESC";
            break;
        case "priceAsc":
            $order .= "p.price ASC";
            break;
        case "numberOfItems":
            $order .= "COUNT(ol.id_order_line) DESC";
            break;
            // default:
            //     $where .= "od.id_order_detail ASC";
    }

    $query = "SELECT u.id_user, u.isLock, u.first_name, u.email_user, u.last_name, SUM(ol.price_purchase * ol.quantity) as total, COUNT(DISTINCT od.id_order_detail) as count FROM user u LEFT JOIN order_detail od ON u.id_user = od.id_user LEFT JOIN order_line ol ON od.id_order_detail = ol.id_order_detail  WHERE u.id_user <> 1";
    // $query = "SELECT u.first_name, u.last_name, SUM(ol.price_purchase * ol.quantity) as total, od.date_order as date, COUNT(ol.id_order_line) as count FROM user u INNER JOIN order_detail od ON u.id_user = od.id_user INNER JOIN order_line ol ON od.id_order_detail = ol.id_order_detail GROUP BY od.id_order_detail";
    $group = " GROUP BY u.id_user";
    $queryCount = $query . $where . $group . $order;


    $users = $conn->query($query)->fetchAll();
    $total = count($users);


    $num_rows = count($conn->query($queryCount)->fetchAll());
    $resp = returnNumberOfPages($num_rows, $perPage, $page);
    $numOfPages = $resp[0];
    $start = $resp[1];
    $numOfPages = ceil($numOfPages);


    // display($queryCount);
    $total = count($conn->query($queryCount)->fetchAll());
    // display($total);
    $limit = " LIMIT $start, $perPage";
    $query = $query . $where . $group .  $order . $limit;
    // display($query);
    $users = $conn->query($query)->fetchAll();
    // $total = count($users);




    $res = array([
        "users" => $users,
        "pages" => $numOfPages,
        // "total" => $total
        "total" => $total
        // "num" => 
    ]);
    // display($query);
    // display($res);
    return $res;
}

// Returns number of pages
function returnNumberOfPages($num_rows, $perPage, $page)
{
    $numOfPages = $num_rows / $perPage;
    if ($num_rows % $perPage > 0) {
        // display("I GO IF");
        $start = ($page - 1) * $perPage;
    } else {
        // display("I GO ELSE");
        $start = ($page - 1) * $perPage;
    }
    $numOfPages = ceil($numOfPages);
    // display($numOfPages);
    return [$numOfPages, $start];
}

// Returns order detail info
function returnOrderDetailInfo($id)
{

    global $conn;

    // Begin Transaction
    // $conn->beginTransaction();

    $errs = [];

    $query = "SELECT COUNT(*) as count FROM order_detail WHERE id_order_detail = ?";
    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        $isOrder = true;
    }
    // Checks if there is such order
    $isOrder = countAllFromTable("order_detail");
    if (!$isOrder) {
        $errs[] = "There is no such order";
    }

    $query = "SELECT od.date_order as date, g.name_glove, g.id_glove, b.name_brand, cat.name_cat, ol.price_purchase as price, clr.color_name, i.thumb_img as img, s.value_size as size, m.name_measure as measure, ol.quantity FROM order_detail od INNER JOIN order_line ol ON od.id_order_detail = ol.id_order_detail INNER JOIN glove_size gs ON ol.id_glove_size = gs.id_glove_size INNER JOIN size s ON s.id_size = gs.id_size INNER JOIN measure m ON m.id_measure = s.id_measure INNER JOIN glove g ON gs.id_glove = g.id_glove INNER JOIN category cat ON cat.id_cat = g.id_cat INNER JOIN image i ON i.id_glove = g.id_glove INNER JOIN brand b ON b.id_brand = g.id_brand INNER JOIN color clr ON clr.id_color = g.id_color ";
    $query .= "WHERE od.id_order_detail = ?";

    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        $orders = $prep->fetchAll();
        $count = count($orders);
    } else {
        $errs[] = "Failed to retreive items";
    }

    $query = "SELECT u.first_name, u.last_name, u.email_user, u.id_user FROM user u INNER JOIN order_detail od ON od.id_user = u.id_user";
    $query .= " WHERE od.id_order_detail = ?";

    $prep = $conn->prepare($query);
    if ($prep->execute([$id])) {
        $user = $prep->fetch();
    } else {
        $errs[] = "Failed to retreive user";
    }

    // End transaction with commit or rollback
    if (!empty($errs)) {
        // Rollback
        // $conn->rollBack();
        return false;
    } else {
        // Commit
        // $conn->commit();
        // display($orders);
        // display($user);
        return [$orders, $user, $count];
    }
}

// Insert message
function insertMessage($msg_first_name, $msg_last_name, $msg_email, $msg_subject, $msg_body)
{
    global $conn;

    $query = "INSERT INTO message(msg_first_name, msg_last_name, msg_email, msg_subject, msg_body) VALUES(?, ?, ?, ?, ?)";
    $prep = $conn->prepare($query);
    if ($prep->execute([$msg_first_name, $msg_last_name, $msg_email, $msg_subject, $msg_body])) {
        return true;
    }
}

// Returns message details
function returnMessageInfo($id)
{
    global $conn;
    try {
        // Begin Transaction
        $conn->beginTransaction();

        $query = "SELECT * from message ";
        $query .= "WHERE id_msg = ?";

        $prep = $conn->prepare($query);
        if ($prep->execute([$id])) {
            $message = $prep->fetch();
            if (!$message->id_msg) {
                header("Location: index.php?page=index");
            }
        } else {
            // $errs[] = "Failed to retreive messages";
            header("Location: index.php?page=index");
        }

        // End transaction with commit or rollback
        if (!empty($errs)) {
            // Rollback
            $conn->rollBack();
            return false;
        } else {
            // Commit
            $conn->commit();
            return [$message];
        }
    } catch (PDOException $ex) {
        // Try block failed
        $errs[] = $ex->getMessage();
    }
}


// Log page visits
function pageVisitsWrite($page, $user = "Unknonwn user")
{
    $open = fopen(DATA . "/page-visits.txt", "a+");
    $file = file(DATA . "/page-visits.txt");
    $ip = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');

    $content = $user . "__" . $ip . "__" . $date . "__" . $page . "\n";
    fwrite($open, $content);
    fclose($open);
    pageVisitsOverwrite();
}

// Rewrite page visits
function pageVisitsOverwrite()
{
    $file = file(DATA . "/page-visits.txt");
    $log = [];
    foreach ($file as $i => $r) {
        list($email, $ip, $date, $page) = explode("__", $r);
        $difference = time() - strtotime($date);
        if ($difference < 60 * 60 * 24) {
            $log[] = $r;
        }
    }
    // display($log);
    $log = implode("", $log);
    $open = fopen(DATA . "/page-visits.txt", "w");
    fwrite($open, $log);
    fclose($open);
}

// Return page visits as array
function returnPageVisits()
{
    $file = file(DATA . "/page-visits.txt");
    $count = count($file);



    foreach ($file as $i => $r) {
        list($email, $ip, $date, $page) = explode("__", $r);
        $pages[] = $page;
    }

    $total = count($pages);
    $pages = array_count_values($pages);
    // display($res);
    return array($total, $pages);
}
