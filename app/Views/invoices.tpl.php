<?php 
    $configs = require './config/app.config.php'; 
    $page = getParam('page') ? (int) getParam('page') : 1;
    $search = getParam('search', null);
    $orderBy = getParam('order-by', 'id');
    $orderDir = getParam('order-dir', 'DESC');

    $url = "search=$search&order-by=$orderBy&order-dir=$orderDir";
?>

<div class="container">

    <ul class="nav nav-underline">
        <li class="nav-item mt-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= $orderBy==='id' && $orderDir==="DESC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>&search=<?=$search?>&order-by=id&order-dir=DESC">DESCENDING ORDER</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='id' && $orderDir==="ASC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>&search=<?=$search?>&order-by=id&order-dir=ASC">ASCENDING ORDER</a>
                    </li>
                </ol>
            </nav>
        </li>
        <li class="nav-item ms-auto mt-1">
            <form action="/invoices" method="GET" id="search-form">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="order-by" value="<?= $orderBy ?>">
                <input type="hidden" name="order-dir" value="<?= $orderDir ?>">
                <input type="text" name="search" id="search" placeholder="Search Customer" required value="<?= getParam('search') ? getParam('search') : "" ?>">
                <button type="submit" class="search-button" id="search-button">SEARCH</button>
            </form>
        </li>
    </ul>

    <p class="mt-3 mb-0">
        <span class="fs-4 fw-bold">INVOICES LIST</span>
    </p>

    <p class="text-muted"><?= $count ?> <?= $count > 1 ? "Invoices" : "Invoice" ?></p>

    <!-- Pagination -->
    <?php if ($count > $configs["configs"]["recordsPerPage"]): ?>
        <?php 
            $totalPages = $search ? ceil(count($invoices) / $configs["configs"]["recordsPerPage"]) : ceil($count / getConfig("recordsPerPage"));
            $prevDisabled = ($page <= 1) ? "disabled" : "";
            $nextDisabled = ($page >= $totalPages) ? "disabled" : "";
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?= $prevDisabled ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?=$search?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>">Previous</a>
                </li>
                    <?php for ($i=1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?=$search?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $nextDisabled ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?=$search?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>">Next</a>
                    </li>
            </ul>
        </nav>
    <?php endif;?>
    <!-- Pagination -->

    <table class="table table-sm mt-3">
        <caption>Invoices List</caption>
        <thead>
            <tr>
                <th scope="col" style="width: 15%;">NUMBER</th>
                <th scope="col">CUSTOMER</th>
                <th scope="col">DATE</th>
                <th scope="col">FILE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?= $invoice->id ?></td>
                    <td><?= $invoice->customer ?></td>
                    <td><?= $invoice->date ?></td>
                    <td><a href="./invoices/<?= basename($invoice->file) ?>" download><?= basename($invoice->file) ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>