<?php 
    $configs = require './config/app.config.php'; 
    $page = getParam('page') ? (int) getParam('page') : 1;

    $orderBy = getParam('order-by', 'id');
    $orderDir = getParam('order-dir', 'DESC');
?>

<div class="container">

    <ul class="nav nav-underline">
        <li class="nav-item mt-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= $orderBy==='id' && $orderDir==="DESC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page ?>&order-by=id&order-dir=DESC">DESCENDING ORDER</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='id' && $orderDir==="ASC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page ?>&order-by=id&order-dir=ASC">ASCENDING ORDER</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='price' && $orderDir==="DESC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page ?>&order-by=price&order-dir=DESC">PRICE DESCENDENT</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='price' && $orderDir==="ASC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page ?>&order-by=price&order-dir=ASC">PRICE ASCENDENT</a>
                    </li>
                </ol>
            </nav>
        </li>
    </ul>

    <div>
        <p class="mt-3 mb-5">
            <span class="fs-4 fw-bold me-auto"><?= $count ?> PRODUCTS IN "<?= $category ?>"</span>
        </p>

        <!-- Pagination -->
        <?php if ($count > $configs["configs"]["recordsPerPage"]): ?>
            <?php 
                $totalPages = ceil($count / getConfig("recordsPerPage"));
                $prevDisabled = ($page <= 1) ? "disabled" : "";
                $nextDisabled = ($page >= $totalPages) ? "disabled" : "";
            ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?= $prevDisabled ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>">Previous</a>
                    </li>
                    <?php for ($i=1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $nextDisabled ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif;?>
        <!-- Pagination -->

        <table class="table table-sm mt-3">
            <caption>Products List</caption>
            <thead>
                <tr>
                    <th scope="col" style="width: 25%;">NAME</th>
                    <th scope="col">BRAND</th>
                    <th scope="col">PRICE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">CODE</th>
                    <th scope="col">REMAINING</th>
                    <th scope="col">CATEGORY</th>
                    <th scope="col">DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td><a href="/<?= $product["id"] ?>"><?= $product["name"] ?></a></td>
                        <td><?= $product["brand"] ?></td>
                        <td>€ <?= $product["price"] ?></td>
                        <td class="<?= $product["status"] === "out_stock" ? "text-danger" : "" ?> <?= $product["status"] === "arriving" ? "text-success" : "" ?>">
                            <?php echo showStatus($product["status"]) ?>
                        </td>
                        <td><?= $product["code"] ?></td>
                        <td><?= $product["number"] ?></td>
                        <td><?= $product["category"] ?></td>
                        <td><?= $product["added"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>