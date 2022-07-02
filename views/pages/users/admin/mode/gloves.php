<?php
include_once M_ADMIN . "/Admin.php";
if (!isset($_SESSION['loggedUser'])) {
    header("Location: index.php");
} else {
    $user = $_SESSION['loggedUser'];
    if ($user->name_role != "admin") {
        // User is unauthorized
        header("Location: index.php");
    }
}
if (isset($_GET["mode"])) {
    // Determine which mode is it
    $brands = selectAllFromTable("brand");
    $categories = selectAllFromTable("category");
    $colors = selectAllFromTable("color");

    $mode = $_GET['mode'];
    if ($mode == 'edit') {
        // Edit mode
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // Get the values for glove with ID
            // An object that contains all info for selected glove
            // FK are represented as id's
            // Image names(paths)
            // Stringified array of sizes represented as id's
            $filters = selectCurrentGloveValues($id);
            if (!isset($filters) || empty($filters)) {
                header("Location: index.php");
            }
            // display($filters);
            $color = $filters->colorID;
            $cat = $filters->catID;
            $brand = $filters->brandID;
            $name = $filters->name;
            $desc = $filters->description;
            $price = $filters->price;
            $n_img = $filters->n_img;
            $t_img = $filters->t_img;

            // Array of sizes that need to be checked, converted from string
            $sizesGloveID = explode(",", $filters->sizesID);
            // display($sizesGloveID);

            // Available sizes is an array of objects that contain measure[OZ, Circumference], size[m, l, 8, xl, 16], id[1, 2, 3...]
            // Used to extract info for Helpers arrays
            // Used for looping through and checking if ID's match
            // NOT all sizes are present in every glove (8, 10, m, l) thats why we use available sizes
            $availableSizesObj = selectAvailableSizes($cat);
            // display($availableSizesObj);

            foreach ($availableSizesObj as $i => $as) {
                $as = array($as);
                foreach ($as as $i => $s) {
                    // Helpers arrays
                    $sizesGloveValues[] = $s->size;
                    $sizesGloveMeasures[] = $s->measure;
                }
            }
        }
    } else {
        // JUST FOR INSERT PAGE

    }
}
?>
<div class="container">
    <div class="row">
        <div class="container">
            <div class="col-12 mt-5">
                <div class="form-group">
                    <a href="index.php?page=admin-gloves" class="box-shadow btn bg-c-primary d-inline-flex align-items-center text-white">
                        <span class="material-icons">
                            arrow_back
                        </span>
                        <span class="ms-2">Back to gloves</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 my-5">
            <form id="glovesModeForm<?= isset($_GET['id']) ? "Edit" : "Insert" ?>" action="models/users/admin/gloves/<?= isset($_GET['id']) ? "Edit.php" : "Insert.php" ?>" method="POST" enctype="multipart/form-data">
                <?php if (isset($_GET['id'])) : ?>
                    <input type="hidden" value="<?= $_GET['id'] ?>" name="inHidden" id="inHidden">
                <?php endif; ?>
                <div class="form-row">
                    <!-- BRANDS -->
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                sell
                            </span>
                            <span class="ms-2">Brand</span>
                        </div>
                        <select name="ddlBrand" id="ddlBrand" class="form-control">
                            <option value="">Select brand</option>
                            <!-- FOREACH -->
                            <?php if (!isset($_GET['id'])) {
                                foreach ($brands as $i => $b) : ?>
                                    <option value="<?= $b->id_brand ?>"><?= $b->name_brand ?></option>
                                <?php endforeach;
                            } else {
                                foreach ($brands as $i => $b) : ?>
                                    <option value="<?= $b->id_brand ?>" <?= $b->id_brand == $brand ? "selected" : "" ?>><?= $b->name_brand ?></option>
                            <?php endforeach;
                            } ?>
                            <!-- ENDFOREACH -->
                        </select>
                    </div>
                    <!-- CATEGORIES -->
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                category
                            </span>
                            <span class="ms-2">Category</span>
                        </div>
                        <!-- Selected category determines what type of sizes will be available
                        eg. boxing -> oz; karate/mma -> s/m/l/xl -->
                        <select name="ddlCat" id="ddlCat" class="form-control">
                            <option value="">Select category</option>
                            <!-- FOREACH -->
                            <?php if (!isset($_GET['id'])) {
                                foreach ($categories as $i => $c) : ?>
                                    <option value="<?= $c->id_cat ?>"><?= $c->name_cat ?></option>
                                <?php endforeach;
                            } else {
                                foreach ($categories as $i => $c) :
                                ?>
                                    <option value="<?= $c->id_cat ?>" <?= $c->id_cat == $cat ? "selected" : "" ?>><?= $c->name_cat ?></option>
                            <?php endforeach;
                            } ?>
                            <!-- ENDFOREACH -->
                        </select>
                    </div>
                    <!-- SIZES -->
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                straighten
                            </span>
                            <span class="ms-2">Sizes</span><br>
                        </div>
                        <!-- Selected category determines what type of sizes will be available
                        eg. boxing -> oz; karate/mma -> s/m/l/xl -->
                        <div id="cbSizes">
                            <?php if (isset($_GET['id'])) {
                                foreach ($availableSizesObj as $i => $as) :
                            ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="cbSize[]" value="<?= $as->id ?>" <?= in_array($as->id, $sizesGloveID) ? "checked" : "" ?> id="">
                                        <label class="form-check-label" for="flexCheckDefault"><?= $sizesGloveValues[$i] ?> <?= $as->measure == "OZ" ? $sizesGloveMeasures[$i] : "" ?></label>
                                    </div>
                            <?php endforeach;
                            } ?>
                        </div>
                    </div>
                    <!-- COLORS -->
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                palette
                            </span>
                            <span class="ms-2">Colors</span>
                        </div>
                        <!-- Selected category determines what type of sizes will be available
                        eg. boxing -> oz; karate/mma -> s/m/l/xl -->
                        <select name="ddlColor" id="ddlColor" class="form-control">
                            <option value="">Select color</option>
                            <!-- FOREACH -->
                            <?php if (!isset($_GET['id'])) {
                                foreach ($colors as $i => $c) :
                            ?>
                                    <option value="<?= $c->id_color ?>"><?= ucfirst($c->color_name) ?></option>
                                <?php endforeach;
                            } else {
                                foreach ($colors as $i => $c) :
                                ?>
                                    <option value="<?= $c->id_color ?>" <?= $c->id_color == $color ? "selected" : "" ?>><?= ucfirst($c->color_name) ?></option>
                            <?php endforeach;
                            } ?>
                            <!-- ENDFOREACH -->
                        </select>
                    </div>
                </div>



                <div class="form-row">
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                description
                            </span>
                            <span class="ms-2">Product name</span>
                        </div>
                        <input type="text" placeholder="Leone Pink Fight" value="<?= isset($_GET['id']) ? $name : "" ?>" name="inName" id="inName" class="form-control" />
                    </div>
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                image
                            </span>
                            <span class="ms-2">Image</span>
                        </div>
                        <br>
                        <input type="file" class="" id="fileImg" name="fileImg" /><br>
                        <span class="lead d-block my-3"><img class="box-shadow" id="editThumbImg" src="assets/img/gloves/thumbnail/<?= isset($_GET['id']) ? $t_img : "" ?>" /></span>
                    </div>
                    <div class="form-group col-md-7 my-3">
                        <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                            <span class="material-icons">
                                paid
                            </span>
                            <span class="ms-2">Price</span>
                        </div>
                        <input type="text" placeholder="59.99" value="<?= isset($_GET['id']) ? $price : "" ?>" class="form-control " id="inPrice" name="inPrice" />
                    </div>
                </div>
                <div class="form-group col-md-7 my-3 p-0">
                    <div class="box-shadow btn bg-c-ternary d-inline-flex align-items-center text-white mb-3">
                        <span class="material-icons">
                            article
                        </span>
                        <span class="ms-2">Description</span>
                    </div>
                    <textarea name="taDesc" id="taDesc" class="form-control" placeholder="Enter description"><?= isset($_GET['id']) ? $desc : "" ?></textarea>
                </div>
                <div class="my-5 col-md-7">
                    <hr>
                </div>
                <div class="form-group">
                    <button type="submit" name="btnSubmit" id="btnSubmit" class="text-white" value="btnSubmit">
                        <div class="box-shadow btn bg-c-secondary d-inline-flex align-items-center text-white">
                            <span class="material-icons">
                                add_circle
                            </span>
                            <span class="ms-2"><?= !isset($_GET['id']) ? "Insert glove" : "Update glove" ?></span>
                        </div>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>