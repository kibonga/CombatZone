<div class="container">
    <?php if ($type != "order lines") : ?>
        <div class="mt-5">
            <article class="filter-group">
                <header class="card-header bg-c-primary">
                    <h6 class="lead text-white">Search for order</h6>
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
                <p class="p-0">Number of <?= $type ?>: <span id="<?= $tableCount ?>"></span></p>
            </div>
            <div class="form-inline">
                <div class="mb-2">
                    <div>
                        <label class="lead"><?= ucfirst($type) ?> per page: </label>
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
                        <option value="nameAsc">Name A-Z</option>
                        <option value="nameDesc">Name Z-A</option>
                        <option value="latest">Latest</option>
                        <?php if ($type == "orders") : ?>
                            <option value="priceDesc">Most Expensive</option>
                            <option value="priceAsc">Least Expensive</option>
                        <?php elseif ($type == "users") : ?>
                            <option value="numberOfItems">Number of orders</option>
                        <?php elseif ($type == "messages") : ?>
                            <option value="subject">Subject</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="my-5">
        <h3 class="lead" style="font-size: 1.8rem" id="tableName">All <?= $type ?></h3>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <?php foreach ($tableColumns as $i => $c) : ?>
                        <th scope="col"><?= $c ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody id="<?= $tableId ?>" class="table-striped align-center">
                <!-- Insert orders -->
                <?php if (isset($order)) :
                    // If order is set call function for interpolations
                    include_once FIXED . "/components/order-detail.component.php";
                    foreach ($order_lines as $i => $ol) :
                ?>
                        <?= returnAdminTableOrderHTML($ol, $i) ?>
                <?php
                    endforeach;
                endif; ?>
            </tbody>
        </table>
    </div>
    <nav aria-label="...">
        <ul class="pages pagination" id="pages">

        </ul>
    </nav>
</div>