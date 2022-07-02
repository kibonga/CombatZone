<?php
if (!isset($_GET['id'])) {
    // Order detail id was not set
    header("Location: index.php");
}
if (!isset($_SESSION['loggedUser'])) {
    // User tried to access admin only page
    header("Location: index.php");
} else {
    $user = $_SESSION['loggedUser'];
    if ($user->name_role != "admin") {
        // Customer tried to access admin only page
        header("Location: index.php");
    }
}
$id = $_GET['id'];
$order = returnOrderDetailInfo($id);
list($order_lines, $u, $count) = $order;
$total = 0;
if (!isset($order) || empty($order[0])) {
    header("Location: index.php");
}
$tableId = "orderLineTableBody";
$tableCount = "numOfOrderLines";
$type = "order lines";
$tableColumns = ["No.", "Image", "Name", "Category", "Brand", "Color", "Size", "Price", "Quantity", "Total"];
$tableIcon = "person";
$total = 0;
$num = 0;
foreach ($order_lines as $i => $ol) {
    $num += 1;
    $total += +$ol->quantity * +$ol->price;
};
?>
<div class="container">
    <div class="col-12 my-5">
        <div class="form-group">
            <a href="index.php?page=admin-user&id=<?= $u->id_user ?>" class="box-shadow btn bg-c-secondary d-inline-flex align-items-center text-white">
                <span class="material-icons">
                    arrow_back
                </span>
                <span class="ms-2"><?= $u->first_name ?>'s account</span>
            </a>
        </div>
    </div>
    <div>
        <h3 class="lead text-white bg-c-primary px-5 py-3 d-flex justify-content-between align-items-center" style="font-size: 1.8rem!important;">
            <p class="my-2">Order</p>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <a href="models/users/admin/orders/Excel.php?id=<?= $_GET['id'] ?>" id="download-order-detail-excel" data-orderid='<?= $_GET["id"] ?>' class="d-inline-block d-flex align-items-center text-white justify-content-between">
                    <span class="material-icons ">
                        file_download
                    </span>
                    <span>
                        Excel
                    </span>
                </a>
            </div>
        </h3>
        <div class="mb-5 box-shadow px-5 py-3">
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary">
                        pin
                    </span>
                    <span>
                        Order ID
                    </span>
                </div>
                <p class="lead mt-3"><a class="text-secondary" href="index.php?page=admin-user&id=<?= $u->id_user ?>"><?= isset($_GET['id']) ? $_GET["id"] : "" ?></a></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary">
                        person
                    </span>
                    <span>
                        Customer
                    </span>
                </div>
                <p class="lead mt-3"><a class="text-secondary" href="index.php?page=admin-user&id=<?= $u->id_user ?>"><?= $u->first_name ?> <?= $u->last_name ?></a></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary">
                        event
                    </span>
                    <span>
                        Date of purchase
                    </span>
                </div>
                <p class="lead mt-3"><?= $order_lines[0]->date ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary">
                        shopping_cart
                    </span>
                    <span>
                        Number of items
                    </span>
                </div>
                <p class="lead mt-3"><?= $num ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary">
                        request_quote
                    </span>
                    <span>
                        Total
                    </span>
                </div>
                <p class="lead mt-3">$ <?= $total ?></p>
            </div>
        </div>
    </div>
</div>
<?php
include FIXED . "/admin-table.php";
?>
