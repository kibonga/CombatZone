<div class="bg-c-primary box-shadow">
    <div class="row">
        <div class="container">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg px-5">
                    <a class="navbar-brand text-white" href="index.php">CombatZone</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="material-icons navbar-toggler-icon text-white d-flex align-items-center">
                            menu
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <?php foreach (GENERAL_NAV as $i => $n) : ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white acc <?= (!isset($_GET['page'])) ? "" : (($_GET['page'] == $n["name_view"]) ? "active" : "") ?>" href="index.php?page=<?= $n['name_view'] ?>">
                                        <span class="material-icons align-bottom">
                                            <?= $n['icon_name'] ?>
                                        </span>
                                        <span><?= $n['title_view'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-end">
                            <?php
                            if (isset($_SESSION['loggedUser'])) {
                            ?>
                                <li class="d-flex align-items-center">
                                    <a class="d-flex position-relative rounded-circle align-items-center justify-content-center text-center text-white acc" href="index.php?page=<?= isset($_SESSION['loggedUser']) ?  "cart"  : "" ?>">
                                        <span class="material-icons fs-2 py-2 px-1">
                                            shopping_cart
                                        </span>
                                        <span class='badge position-absolute end-0' id='cartNav'></span>
                                    </a>
                                </li>
                                <?php if ($_SESSION['loggedUser']->name_role == 'admin') : ?>
                                    <li class="d-flex align-items-center">
                                        <a class="d-flex align-items-center justify-content-center text-center text-white acc" href="index.php?page=admin-dashboard">
                                            <span class="material-icons fs-2 py-2 px-1">
                                                account_circle
                                            </span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item ms-3">
                                    <form action="models/auth/Logout.php" method="post">
                                        <div class="form-group m-0">
                                            <button type="submit" name="btnLogout" id="btnLogout" value="btnLogout">
                                                <div class="btn bg-c-ternary d-inline-flex align-items-center py-2 px-3  text-white">
                                                    <span class="material-icons">
                                                        logout
                                                    </span>
                                                    <span>Logout</span>
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                </li>
                            <?php
                            } else {
                            ?>
                                <ul class="navbar-nav mx-auto">
                                    <li class="nav-item p-0">
                                        <div class="form-group m-0 p-0">
                                            <a class="nav-link btn bg-c-ternary" href="index.php?page=login">
                                                <div class="bg-c-ternary d-inline-flex align-items-center align-bottom  text-white">
                                                    <span class="material-icons text-white">
                                                        login
                                                    </span>
                                                    <span>Login</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li class="nav-item ms-2">
                                        <div class="form-group m-0">
                                            <a class="btn nav-link d-inline-flex align-items-center btn bg-c-ternary" href="index.php?page=register">
                                                <div class="bg-c-ternary d-inline-flex align-items-center align-bottom px-2 p-0 text-white">
                                                    <span class="material-icons text-white">
                                                        app_registration
                                                    </span>
                                                    <span>Register</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="errModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-c-secondary">
                <h4 class="modal-title lead"><span id="errModalStatus" class="text-white"></span></h4>
                <button type="button" class="btn-close bg-c-ternary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <p>Error messages: </p>
                </div>
                <div class="lead" id="errModalMessage">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary bg-c-ternary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="defaultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-c-secondary">
                <h4 class="modal-title lead "><span id="defaultModalTitle" class="text-white"></span></h4>
                <button type="button" class="btn-close bg-c-ternary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="lead box-shadow py-2 px-3">Message: <span id="defaultModalMessage"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="defaultModalBtn" class="btn btn-secondary bg-c-ternary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="container py-3">
    <h1 class="display-5 mt-5"><span class="rounded box-shadow px-3 py-2"><span><?= HEADING_PRIMARY ?></h1>
    <p class="lead mt-4"><?= HEADING_SECONDARY ?></p>
</div>