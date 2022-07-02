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
$u = selectAllFromTableWhere("user", "id_user", $id);
if (!$u) {
    header("Location: index.php");
}
?>
<div class="container">
    <div class="my-5">
        <a href="index.php?page=admin-users" class="box-shadow btn bg-c-secondary d-inline-flex align-items-center text-white">
            <span class="material-icons me-2">
                arrow_back
            </span>
            <span>All Users</span>
        </a>
    </div>
    <div>
        <h3 class="lead text-white bg-c-primary px-5 py-3" style="font-size: 1.8rem!important;">User</h3>
        <div class="mb-5 box-shadow px-5 py-3">
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-center">
                        person
                    </span>
                    <span>
                        Full name
                    </span>
                </div>
                <p class="lead mt-3"><?= $u->first_name ?> <?= $u->last_name ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-center">
                        email
                    </span>
                    <span>
                        Email
                    </span>
                </div>
                <p class="lead mt-3"><?= $u->email_user ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-center">
                        home
                    </span>
                    <span>
                        Address
                    </span>
                </div>
                <p class="lead mt-3"><?= $u->address ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-center">
                        phone_iphone
                    </span>
                    <span>
                        Phone number
                    </span>
                </div>
                <p class="lead mt-3"><?= $u->phone_num ?></p>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div>
            <p class="p-0">Number of orders: <span id="ordersCount"></span></p>
        </div>
        <div class="form-inline">
            <div class="mb-2">
                <div>
                    <label class="lead">Orders per page: </label>
                    <select id="perPage" class="form-select" name="perPage" id="perPage">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="4" selected>4</option>
                        <option value="6">6</option>
                        <option value="8">8</option>
                        <option value="12">12</option>
                        <option value="24">24</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-inline">
            <div class="mb-2">
                <label class="lead">Sort: </label>
                <select class="mr-2 form-select" id="sort" name="sort">
                    <option value="">Select</option>
                    <option value="latest">Latest</option>
                    <option value="numberOfItems">Number of items</option>
                    <option value="priceDesc">Most Expensive</option>
                    <option value="priceAsc">Least Expensive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="my-5">
        <h3 class="lead" style="font-size: 1.8rem" id="tableName">Customer's orders</h3>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Items</th>
                    <th scope="col">Total</th>
                    <th scope="col">Date</th>
                    <th scope="col">More details</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody" class="table-striped align-center">
                <!-- Insert orders -->
            </tbody>
        </table>
    </div>
    <nav aria-label="...">
        <ul class="pages pagination" id="pages">

        </ul>
    </nav>
</div>