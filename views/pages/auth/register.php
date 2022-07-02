<?php
if (isset($_SESSION['loggedUser'])) {
    header("Location: index.php");
}
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-5 py-5">
            <form id="registerForm" name="registerForm" action="models/auth/Register.php" method="post">
                <div class="form-group">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-primary mr-2 d-inline-block">
                            <span class="material-icons text-ternary ms-2 d-inline-block">
                                person_outline
                            </span>
                        </span>
                        <label for="" class="d-flex mb-2">Full name</label>
                    </div>
                    <div class="d-flex mb-2">
                        <input type="text" name="inFname" id="inFname" placeholder="First name" class="form-control me-3">
                        <input type="text" name="inLname" id="inLname" placeholder="Last name" class="form-control">
                    </div>
                    <div>
                        <span id="inFnameErr" name="inFnameErr"></span><br>
                        <span id="inLnameErr" name="inLnameErr"></span>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            alternate_email
                        </span>
                        <label for="" class="d-flex ms-2 ">Email</label>
                    </div>
                    <input type="email" name="inEmail" id="inEmail" class="form-control" />
                </div>
                <div class="form-group mt-3">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            lock
                        </span>
                        <label for="" class="d-flex ms-2">Password</label>
                    </div>
                    <input type="password" name="inPass" id="inPass" class="form-control" />
                </div>
                <div class="form-group mt-3">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            lock_open
                        </span>
                        <label for="" class="d-flex ms-2">Confirm password</label>
                    </div>
                    <input type="password" name="inPassConf" id="inPassConf" class="form-control" />
                </div>
                <div class="form-group mt-3">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            call
                        </span>
                        <label for="" class="d-flex ms-2">Contact phone</label>
                    </div>
                    <input type="text" name="inPhone" id="inPhone" class="form-control" />
                </div>
                <div class="form-group mt-3">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            home
                        </span>
                        <label for="" class="d-flex ms-2">Address</label>
                    </div>
                    <input type="text" name="inAddr" id="inAddr" class="form-control" />
                </div>
                <div class="my-4">
                    <hr>
                </div>
                <div class="form-group">
                    <button type="submit" name="btnRegister" id="btnRegister" class="text-white" value="btnRegister">
                        <div class="btn bg-c-ternary d-inline-flex align-items-center text-white">
                            <span class="material-icons">
                                app_registration
                            </span>
                            <span class="ms-2">Register</span>
                        </div>
                    </button>
                </div>
                <?php
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $i => $err) :
                ?>
                        <div class="form-group">
                            <span class="text-danger"><?= $err ?></span>
                        </div>
                <?php endforeach;
                } ?>
            </form>
        </div>
    </div>
</div>