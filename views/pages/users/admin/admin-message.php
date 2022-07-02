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
$m = returnMessageInfo($id)[0];
?>
<div class="container mt-5">
    <div class="mb-5">
        <a href="index.php?page=admin-messages" class="box-shadow btn bg-c-secondary d-inline-flex align-items-center text-white">
            <span class="material-icons me-2">
                arrow_back
            </span>
            <span>All Messages</span>
        </a>
    </div>
    <div>
        <h3 class="lead text-white bg-c-primary px-5 py-3" style="font-size: 1.8rem!important;">Message</h3>
        <div class="mb-5 box-shadow px-5 py-3">
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-center">
                        person
                    </span>
                    <span>
                        Sender
                    </span>
                </div>
                <p class="lead mt-3"><?= $m->msg_first_name ?> <?= $m->msg_last_name ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-bottom">
                        event
                    </span>
                    <span>
                        Date received
                    </span>
                </div>
                <p class="lead mt-3"><?= $m->msg_date ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-bottom">
                        short_text
                    </span>
                    <span>
                        Subject
                    </span>
                </div>
                <p class="lead mt-3"><?= $m->msg_subject ?></p>
            </div>
            <div class="lead my-2" style="font-size: 1.5rem!important;">
                <div class="box-shadow d-inline-block px-2 py-1">
                    <span class="material-icons text-secondary align-bottom">
                        article
                    </span>
                    <span>
                        Message body
                    </span>
                </div>
                <p class="lead mt-3"><?= $m->msg_body ?></p>
            </div>
        </div>
    </div>
</div>