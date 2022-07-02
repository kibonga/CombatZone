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
?>
<div class="container">
    <nav class="navbar navbar-expand-lg bg-c-secondary box-shadow">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="material-icons navbar-toggler-icon text-white d-flex align-items-center">
                    menu
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <?php foreach (ADMIN_NAV as $i => $n) : ?>
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
            </div>
        </div>
    </nav>
</div>