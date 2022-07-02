<?php ?>
<div class="container">
    <div class="row">
        <hr class="featurette-divider">
        <div class="container my-5">
            <div class="row">
                <div class="col-md-6">
                    <figure>
                        <img src="assets/img/hero/woman-kickboxing.jpg" class="img-fluid" alt="woman-kickboxing">
                    </figure>
                </div>
                <div class="col-md-6">
                    <div>
                        <h4 class="text-primary">Have any questions? <span class="text-secondary">Send us a message.</span></h4>
                        <p class="lead">Don't hesitate to contact us if you have any questions, we are here to help.</p>
                    </div>
                    <form class="" id="messageForm" name="messageForm" action="models/public/SendMessage.php" method="POST">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="firstName" class="form-label text-secondary">First name</label>
                                <input type="text" class="form-control" id="inFname" name="inFname" placeholder="Mark" value="<?= isset($_SESSION['loggedUser']) ? ($_SESSION['loggedUser']->name_role == 'regular_user' ? $_SESSION['loggedUser']->first_name : "") : "" ?>">
                                <span clas='inFnameError'> </span>
                            </div>
                            <div class="col-sm-6">
                                <label for="lastName" class="form-label text-secondary">Last name</label>
                                <input type="text" class="form-control" id="inLname" name="inLname" placeholder="Cuban" value="<?= isset($_SESSION['loggedUser']) ? ($_SESSION['loggedUser']->name_role == 'regular_user' ? $_SESSION['loggedUser']->last_name : "") : "" ?>">
                                <span clas='inLnameError'> </span>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label text-secondary">Email <span class="text-muted"></span></label>
                                <input type="email" class="form-control" id="inEmail" name="inEmail" placeholder="you@example.com" value="<?= isset($_SESSION['loggedUser']) ? ($_SESSION['loggedUser']->name_role == 'regular_user' ? $_SESSION['loggedUser']->email_user : "") : "" ?>">
                                <span clas='inLnameError'> </span>
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label text-secondary">Subject <span class="text-muted"></span></label>
                                <input type="text" class="form-control" id="inSubject" name="inSubject" placeholder="Subject" value="">
                                <span clas='text-danger'>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label text-secondary">Message <span class="text-muted"></span></label>
                                <textarea class="form-control" id="taMessage" name="taMessage" cols="30" rows="3"></textarea>
                                <span clas='text-danger'>
                                </span>
                            </div>
                            <hr class="my-4">
                            <div>
                                <?php if (isset($_SESSION['loggedUser']) ? ($_SESSION['loggedUser']->name_role == 'admin' ? false : true) : true) : ?>
                                    <button class="w-35 btn bg-c-secondary text-white border btn col-md-5 mb-3" id="btnSend" name="btnSend" value="btnSend" type="submit">Send</button>
                                <?php endif; ?>
                            </div>
                            <div>
                                <!-- <span id="message-sent" class="bg-c-primary text-white px-3 py-2 rounded">Message successfully sent</span> -->
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>