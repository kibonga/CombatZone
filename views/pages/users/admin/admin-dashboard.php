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
// equalizer
$visits = returnPageVisits();
list($total, $pages) = $visits;
?>
<div class="container mt-5">
    <div class="lead text-white bg-c-primary px-5 py-3 d-flex justify-content-between align-items-center" style="font-size: 1.8rem!important;">
        <h3 class="lead">Total visits: <?= $total ?></h3>
    </div>
    <div class="mb-5 px-5 py-3">
        <?php foreach ($pages as $i => $p) : ?>
            <div class="box-shadow my-4">
                <div class="lead" style="font-size: 1.5rem!important;">
                    <div class="d-inline-block px-2 py-1">
                        <span class="material-icons text-secondary">
                            description
                        </span>
                        <span>
                            Page: <?= $i ?>
                        </span>
                    </div>
                </div>
                <div class="lead" style=" font-size: 1.5rem!important;">
                    <div class="d-inline-block px-2 py-1">
                        <span class="material-icons text-secondary">
                            equalizer
                        </span>
                        <span>
                            Visits:(<?= $p ?>) <?= round((+$p / +$total) * 100, 2) ?>%
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>