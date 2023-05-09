<?php
// Start session
session_start();
?>

<header class="mb-5">
    <nav class="navbar sticky-top navbar-expand-lg bg-wood">
        <div class="container-xxl">
            <a id="titlelogo" class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="Logo" width="32" height="32" class="d-inline-block align-text-center">
                Jackarry </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php
            if ( $_SERVER['PHP_SELF'] <> '/login.php' && $_SERVER['PHP_SELF'] <> '/signup.php') {
                ?>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                        if ($_SERVER['PHP_SELF'] == '/index.php') {
                            ?>

                            <form class="d-flex" method="post">
                                <input class="form-control me-2" type="search" name="input" placeholder="Search">
                                <button class="nav-link" type="submit" name="search"><i
                                        class="fa-solid fa-magnifying-glass fa-2xl"></i></button>
                            </form>

                        <?php } ?>

                    </ul>
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <!-- Cart -->
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i id="navbar-cart" class="fa-solid fa-cart-shopping fa-2xl"></i>
                            </a>
                        </li>

                        <!-- User account -->
                        <?php if (empty($_SESSION['loggedin'])) { ?>

                            <li class="nav-link">
                                <a href="login.php"> <i class="fa-solid fa-user fa-2xl"></i> </a>
                            </li>

                        <?php } else { ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-circle-user fa-2xl"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg-end">
                                    <!-- <li><a class="dropdown-item" href="#">Profile</a></li> -->
                                    <li><a class="dropdown-item" href="logout.php">Log out</a>
                                </ul>
                            </li>

                        <?php } ?>

                    </ul>

                </div>
            <?php } ?>
        </div>
    </nav>

</header>