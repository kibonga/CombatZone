<?php
$brands = selectAllFromTable("brand");
$categories = selectAllFromTable("category");
$colors = selectAllFromTable("color");
?>
<div id="shop-add-div"></div>

<section class="mt-5">
    <div class="p-5">

        <div class="row">
            <aside class="col-md-3">

                <div class="card">
                    <article class="filter-group">
                        <header class="card-header">
                            <a class="text-primary" href="#" data-toggle="collapse" data-target="#collapse_1" aria-expanded="true" class="">
                                <i class="icon-control fa fa-chevron-down"></i>
                                <h6 class="title">Search for glove</h6>
                            </a>
                        </header>
                        <div class="filter-content collapse show" id="collapse_1">
                            <div class="card-body">
                                <form class="pb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-light" type="button"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- card-body.// -->
                        </div>
                    </article> <!-- filter-group  .// -->


                    <!-- #region -->
                    <article class="filter-group">
                        <header class="card-header">
                            <a href="#" class="text-primary" data-toggle="collapse" data-target="#collapse_2" aria-expanded="true" class="">
                                <i class="icon-control fa fa-chevron-down"></i>
                                <h6 class="title">Glove type</h6>
                            </a>
                        </header>
                        <div class="p-3" id="collapse_2">
                            <div id="categories">
                                <?php foreach ($categories as $i => $c) : ?>
                                    <label class="custom-control custom-checkbox d-flex justify-content-between mb-2">
                                        <div>
                                            <input type="checkbox" value='<?= $c->id_cat ?>' name="categories" id="cat-<?= $c->id_cat ?>" class="custom-control-input"> <?= $c->name_cat ?>
                                        </div>
                                        <span class="badge badge-pill badge-danger bg-primary"></span>
                                    </label>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </article> <!-- filter-group .// -->
                    <!-- #endregion -->



                    <!-- #region -->
                    <article class="filter-group">
                        <header class="card-header">
                            <a href="#" class="text-primary" data-toggle="collapse" data-target="#collapse_2" aria-expanded="true" class="">
                                <i class="icon-control fa fa-chevron-down"></i>
                                <h6 class="title">Brand</h6>
                            </a>
                        </header>
                        <div class="p-3" id="collapse_2">
                            <div id="brands">
                                <?php foreach ($brands as $i => $b) : ?>
                                    <label class="custom-control custom-checkbox d-flex justify-content-between mb-2">
                                        <div>
                                            <input type="checkbox" value='<?= $b->id_brand ?>' name="brands" id="cat-<?= $b->id_brand ?>" class="custom-control-input"> <?= $b->name_brand ?>
                                        </div>
                                        <span class="badge badge-pill badge-danger bg-primary"></span>
                                    </label>
                                <?php endforeach ?>
                            </div> <!-- card-body.// -->
                        </div>
                    </article> <!-- filter-group .// -->
                    <!-- #endregion -->




                    <article class="filter-group">
                        <header class="card-header">
                            <a href="#" class="text-primary" data-toggle="collapse" data-target="#collapse_2" aria-expanded="true" class="">
                                <i class="icon-control fa fa-chevron-down"></i>
                                <h6 class="title">Color</h6>
                            </a>
                        </header>
                        <div class="p-3" id="collapse_2">
                            <div id="colors">
                                <?php foreach ($colors as $i => $c) : ?>
                                    <label class="custom-control custom-checkbox d-flex justify-content-between mb-2">
                                        <div>
                                            <input type="checkbox" value='<?= $c->id_color ?>' name="colors" id="cat-<?= $c->id_color ?>" class="custom-control-input"> <?= ucfirst($c->color_name) ?>
                                        </div>
                                        <span class="badge badge-pill badge-danger bg-primary"></span>
                                    </label>
                                <?php endforeach ?>
                            </div> <!-- card-body.// -->
                        </div>
                    </article> <!-- filter-group .// -->


                    <article class="filter-group">
                        <header class="card-header">
                            <a href="#" class="text-primary" data-toggle="collapse" data-target="#collapse_3" aria-expanded="true" class="">
                                <i class="icon-control fa fa-chevron-down"></i>
                                <h6 class="title">Price range</h6>
                            </a>
                        </header>
                        <div class="filter-content collapse show" id="collapse_3">
                            <div class="card-body">
                                <input type="range" class="custom-range" min="0" step="25" max="100" value="100" name="range" id="range">
                                <span id='range-value'>$100</span>
                            </div><!-- card-body.// -->
                        </div>
                    </article> <!-- filter-group .// -->
                </div> <!-- card.// -->

            </aside> <!-- col.// -->
            <main class="col-md-9">

                <header class="border-bottom mb-4 pb-3">
                    <div>
                        <p class="p-0">Available gloves: <span id="gloveCount"></span></p>
                    </div>
                    <div class="form-inline">
                        <div class="mb-2">
                            <div>
                                <label class="lead">Gloves per page: </label>
                                <select id="perPage" class="form-select" name="perPage" id="perPage">
                                    <option value="4">4</option>
                                    <option value="6">6</option>
                                    <option value="8" selected>8</option>
                                    <option value="12">12</option>
                                    <option value="24">24</option>
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
                </header><!-- sect-heading -->
                <div class="row" id="gloves">

                </div> <!-- row end.// -->


                <nav aria-label="...">
                    <ul class="pages pagination" id="pages">

                    </ul>
                </nav>

            </main> <!-- col.// -->

        </div>

    </div> <!-- container .//  -->
</section>
<!-- ========================= SECTION CONTENT END// ========================= -->