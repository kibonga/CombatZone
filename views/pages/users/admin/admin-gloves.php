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
// Variables
$brands = selectAllFromTable("brand");
$categories = selectAllFromTable("category");
$colors = selectAllFromTable("color");

// Checks if action paramter is set
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
    include V_ADMIN_MODE . "/gloves.php";
} else {
?>
    <div class="container">
        <div class="col-12 my-5">
            <div class="form-group">
                <a href="index.php?page=admin-gloves&mode=insert" class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white">
                    <span class="material-icons">
                        add_circle
                    </span>
                    <span class="ms-2">Add new glove</span>
                </a>
            </div>
        </div>
        <hr>
        <div>
            <article class="filter-group">
                <header class="card-header bg-c-primary">
                    <h6 class="lead text-white">Search for glove</h6>
                </header>
                <div class="filter-content collapse show" id="collapse_1">
                    <div class="card-body">
                        <form class="pb-3">
                            <div class="input-group d-flex align-items-center">
                                <span class="material-icons align-bottom ">
                                    search
                                </span>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                            </div>
                        </form>
                    </div> <!-- card-body.// -->
                </div>
            </article> <!-- filter-group  .// -->
            <div>
                <p class="p-0">Available gloves: <span id="gloveCount"></span></p>
            </div>
            <div class="form-inline">

                <div class="mb-2">
                    <div>
                        <label class="lead">Gloves per page: </label>
                        <select id="perPage" class="form-select" name="perPage" id="perPage">
                            <option value="4" selected>4</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                            <option value="12">12</option>
                            <option value="24">24</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <div>
                        <label class="lead">Categories: </label>
                        <select class="form-select" name="categories" id="categories">
                            <option value="">Select</option>
                            <?php foreach ($categories as $i => $c) : ?>
                                <option value="<?= $c->id_cat ?>"><?= $c->name_cat ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <div>
                        <label class="lead">Brands: </label>
                        <select class="form-select" name="brands" id="brands">
                            <option value="">Select</option>
                            <?php foreach ($brands as $i => $b) : ?>
                                <option value="<?= $b->id_brand ?>"><?= $b->name_brand ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <div>
                        <label class="lead">Colors: </label>
                        <select class="form-select" name="colors" id="colors">
                            <option value="">Select</option>
                            <?php foreach ($colors as $i => $c) : ?>
                                <option value="<?= $c->id_color ?>"><?= ucfirst($c->color_name) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="lead">Sort: </label>
                    <select class="mr-2 form-select" id="sort" name="sort">
                        <option value="">Select</option>
                        <option value="nameAsc">Name A-Z</option>
                        <option value="nameDesc">Name Z-A</option>
                        <option value="newest">Latest</option>
                        <option value="priceDesc">Most Expensive</option>
                        <option value="priceAsc">Least Expensive</option>
                    </select>
                </div>
            </div>
            <article class="filter-group">
                <header class="card-header bg-c-primary">
                    <h6 class="lead text-white">Price range</h6>
                </header>
                <div class="filter-content collapse show" id="collapse_3">
                    <div class="card-body">
                        <input type="range" class="custom-range" min="0" step="25" max="100" value="100" name="range" id="range">
                        <span id='range-value'>$100</span>
                    </div><!-- card-body.// -->
                </div>
            </article> <!-- filter-group .// -->
        </div>
        <div class="my-5">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Color</th>
                        <th scope="col">Sizes</th>
                        <th scope="col">Measure</th>
                        <th scope="col">Date</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody id="glovesTableBody" class="table-striped align-center">
                    <!-- Insert gloves -->
                </tbody>
            </table>
        </div>
        <nav aria-label="...">
            <ul class="pages pagination" id="pages">

            </ul>
        </nav>
    </div>
<?php
}
?>