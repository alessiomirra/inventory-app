<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Management</title>

    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>
    
    <header>

        <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">

            <div class="container-fluid">
                <a class="navbar-brand" href="/">INVENTORY</a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

        </nav>

    </header>

    <main>

        <div>

            <div class="row">

                <div class="col-md-2">

                    <ul class="nav flex-column vh-100 overflow-y-scroll bg-dark bg-gradient" id="vertical-menu">
                        <li class="nav-item mb-3">
                            <div class="bg-dark">
                                <p class="nav-link text-white mb-0">
                                    <strong><i class="bi bi-list"></i> Menu</strong>
                                </p>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/' ? 'fw-bold' : '' ?>" href="/"><i class="bi bi-house"></i> HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/cart' ? 'fw-bold' : '' ?>" href="/cart">
                                <i class="bi bi-bag"></i> 
                                CART 
                                <?php if ($_SESSION["cart"]): ?>
                                    <span class="badge text-bg-danger">
                                        <?= count($_SESSION["cart"]) ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/add' ? 'fw-bold' : '' ?>" href="/add"><i class="bi bi-cloud-plus"></i> ADD PRODUCT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/category' ? 'fw-bold' : '' ?>" href="#collapseProject" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseProject"><i class="bi bi-bookmark"></i> CATEGORIES</a>
                            <div class="collapse" id="collapseProject">
                                <div class="ms-3">
                                    <a class="nav-link text-white" href="/category"><i class="bi bi-plus"></i> MANAGE</a>
                                </div>
                                <div class="ms-4 text-white">
                                    <hr>
                                </div>
                                <div class="ms-3" id="list">
                                    <!-- List categories [see vertical-navbar.js] -->
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/invoices' ? 'fw-bold' : '' ?>" href="/invoices"><i class="bi bi-receipt"></i> INVOICES</a>
                        </li>

                        <hr class="mx-2">

                        <li class="nav-item">
                            <?php if (isUserLoggedIn()): ?>
                            <div class="d-grid mx-2">
                                <a href="/login" class="btn btn-danger" type="button">Logout</a>
                            </div>
                            <?php else: ?>
                            <div class="d-grid mx-2">
                                <a href="/login" class="btn btn-danger" type="button">Sign In</a>
                            </div>
                            <?php endif; ?>
                        </li>

                        <li class="nav-item text-center mt-3">
                            <p class="text-light mb-0"><?= date('j F Y') ?></p> 
                            <p class="text-light"><?= date('G:i') ?></p> 
                        </li>

                    </ul>

                </div>

                <div class="col-md-10">

                    <div class="vh-100 overflow-y-scroll">
                        <?= $this->content ?>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- Offcanvas menu -->
    <div class="offcanvas offcanvas-start text-bg-primary bg-dark bg-gradient" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">
                <strong><i class="bi bi-list"></i> Menu</strong>
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">

            <ul class="nav flex-column">

                <li class="nav-item">
                    <a class="nav-link text-white" href="/"><i class="bi bi-house"></i> HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/cart' ? 'fw-bold' : '' ?>" href="/cart">
                        <i class="bi bi-bag"></i> 
                        CART 
                        <?php if ($_SESSION["cart"]): ?>
                            <span class="badge text-bg-danger">
                                <?= count($_SESSION["cart"]) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/add' ? 'fw-bold' : '' ?>" href="/add"><i class="bi bi-cloud-plus"></i> ADD PRODUCT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#collapseProject" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseProject"><i class="bi bi-bookmark"></i> CATEGORIES</a>
                    <div class="collapse" id="collapseProject">
                        <div class="ms-3">
                            <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/category' ? 'fw-bold' : '' ?>" href="/category"><i class="bi bi-plus"></i> MANAGE</a>
                        </div>
                        <div class="ms-4 text-white">
                            <hr>
                        </div>
                        <div class="ms-3" id="list">
                            <!-- List categories [see vertical-navbar.js] -->
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= $_SERVER['REQUEST_URI'] === '/invoices' ? 'fw-bold' : '' ?>" href="/invoices"><i class="bi bi-receipt"></i> INVOICES</a>
                </li>

                <hr class="mx-2">

                <li class="nav-item">
                    <?php if (isUserLoggedIn()): ?>
                    <div class="d-grid mx-2">
                        <a href="/login" class="btn btn-danger" type="button">Logout</a>
                    </div>
                    <?php else: ?>
                        <div class="d-grid mx-2">
                            <a href="/login" class="btn btn-danger" type="button">Sign In</a>
                        </div>
                    <?php endif; ?>
                </li>

                <li class="nav-item text-center mt-3">
                    <p class="text-light mb-0"><?= date('j F Y') ?></p> 
                    <p class="text-light"><?= date('G:i') ?></p> 
                </li>

            </ul>

        </div>

    </div>
    <!---->

    <script src="/js/bootstrap.js"></script>
    <script src="/js/vertical-navbar.js"></script>
    <script src="/js/index.js"></script>

</body>
</html>

