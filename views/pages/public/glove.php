<?php
$id = $_GET['id'];
$g = selectCurrentGloveValues($id);
if (!isset($g) || empty($g)) {
    header("Location: index.php");
}
$sizes = explode(",", $g->sizes);
$sizesID = explode(",", $g->sizesID);
?>
<section class="mb-5 pb-5">
    <div class="container">
        <form method="post" action="models/users/customer/AddToCart.php">
            <div class="col-12 my-5">
                <div class="form-group">
                    <a href="index.php?page=shop" class="box-shadow btn bg-c-secondary d-inline-flex align-items-center text-white">
                        <span class="material-icons">
                            arrow_back
                        </span>
                        <span class="ms-2">Shop</span>
                    </a>
                </div>
            </div>
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0 box-shadow" src="assets/img/gloves/normal/<?= $g->n_img ?>" alt="..."></div>
                <div class="col-md-6">
                    <span class="mb-3 px-3 py-2 d-inline-block box-shadow"><?= $g->brand ?></span>
                    <h1 class="display-6 text-primary"><?= $g->name ?></h1>
                    <div class="fs-5 mb-3">
                        <span>$<?= $g->price ?></span>
                    </div>
                    <div>
                        <div class="mt-3 d-flex align-items-center">
                            <span class="palette border align-bottom" style="background-color:<?= $g->color ?>"></span>
                            <label class="capitalize ms-2 lead"><?= ucfirst($g->color) ?></label>
                        </div>
                    </div>
                    <div>
                        <?php foreach ($sizes as $i => $s) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="raSizes" id="raSizes-<?= $i ?>" <?= $i == 0 ? "checked" : "" ?> value="<?= $sizesID[$i] ?>">
                                <label class="form-check-label">
                                    <span class="size-name"><?= $s ?></span> <?= $g->measure == "OZ" ? "OZ" : "" ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="lead mt-3"><?= $g->description ?></p>
                    <div class="d-flex">
                        <?php if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']->name_role == 'regular_user') : ?>
                            <input class="form-control text-center me-3" id="inQuantity" name="inQuantity" type="number" min="1" max='999' value="1" style="width: 5rem!important">
                            <button class="btn btn-outline-dark flex-shrink-0" type="button" name="btnSubmit" id="btnSubmit">
                                <span class="material-icons align-bottom text-secondary">
                                    add_shopping_cart
                                </span>
                                <span>Add to cart</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div>
                <?php 
                if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']->name_role == 'regular_user') : ?>
                    <input type="hidden" name="inUserID" id="inUserID" value="<?= $_SESSION['loggedUser']->id_user ?>">
                    <?php
                    $hidden = ["id", "name", "colorID", "color", "catID", "cat", "brandID", "brand", "sizesID", "sizes", "measure", "price", "t_img"];
                    foreach ($hidden as $i => $h) :
                    ?>
                        <input type="hidden" name="<?= $h ?>" id="<?= $h ?>" value="<?= $g->$h ?>">
                <?php endforeach;
                endif; ?>
            </div>
        </form>
    </div>
</section>
