<footer class="text-white text-center text-lg-start mt-5 bg-c-primary">
    <!-- Grid container -->
    <div class="container p-4">
        <!--Grid row-->
        <div class="row">
            <!--Grid column-->
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Footer Content</h5>
                <p class="lead">
                    <?= FOOTER ?>
                </p>
            </div>
            <!--Grid column-->
            <!--Grid column-->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Navigation</h5>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 ">
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
            </div>
            <!--Grid column-->
            <!--Grid column-->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-0">Useful</h5>

                <ul class="list-unstyled">
                    <li>
                        <a href="docs.pdf" target="_blank" class="text-white">Docs</a>
                    </li>
                    <li>
                        <a href="models/Word.php" target="_blank" class="text-white">Word</a>
                    </li>
                    <li>
                        <a href="assets/js/main.js" target="_blank" class="text-white">JS</a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/" target="_blank" class="text-white">LinkedIn</a>
                    </li>
                    <li>
                        <a href="https://github.com/" target="_blank" class="text-white">Github</a>
                    </li>
                    <li>
                        <a href="https://pavle-say-what-one-mo-time.lzivadinovic.com/" target="_blank" class="text-white">Portfolio</a>
                    </li>
                </ul>
            </div>
            <!--Grid column-->
        </div>
        <!--Grid row-->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-3 bg-c-secondary">
        <p id="footer-copyright" class="lead">Made by Kibonga</p>
    </div>
    <!-- Copyright -->
</footer>
</body>

</html>