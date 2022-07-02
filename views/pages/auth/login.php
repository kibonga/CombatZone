<?php
if (isset($_SESSION['loggedUser'])) {
    header("Location: index.php");
}
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-5 py-5">
            <form action="models/auth/Login.php" id="loginForm" name="loginForm" method="post">
                <div class="form-group">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            alternate_email
                        </span>
                        <label for="" class="ms-2">Email</label>
                    </div>
                    <input type="email" name="inEmail" id="inEmail" class="form-control" minlength="2" size="25" />
                </div>
                <div class="form-group mt-3">
                    <div class="d-flex mb-2">
                        <span class="material-icons text-ternary ms-2 d-inline-block">
                            lock
                        </span>
                        <label for="" class="ms-2">Password</label>
                    </div>
                    <input type="password" name="inPass" id="inPass" class="form-control" minlength="2" size="25" />
                </div>
                <div class="my-4">
                    <hr>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" name="btnLogin" id="btnLogin" class="text-white submit" value="btnLogin">
                        <div class="btn bg-c-ternary d-inline-flex align-items-center text-white">
                            <span class="material-icons">
                                login
                            </span>
                            <span class="ms-2">Login</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>