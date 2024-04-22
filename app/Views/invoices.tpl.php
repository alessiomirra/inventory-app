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
                <button type="submit" class="search-button px-3" id="search-button"><i class="bi bi-search"></i></button>
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
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?=$search?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>"><i class="bi bi-caret-left"></i></a>
                </li>
                    <?php for ($i=1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?=$search?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $nextDisabled ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?=$search?>&order-by=<?=$orderBy?>&order-dir=<?=$orderDir?>"><i class="bi bi-caret-right"></i></a>
                    </li>
            </ul>
        </nav>
    <?php endif;?>
    <!-- Pagination -->

    <!-- Invoices List  -->
    <div class="list-group mb-5">
        <?php if(count($invoices)): ?>
            <?php foreach ($invoices as $invoice): 
                $products = json_decode($invoice->products);
                $total = 0
            ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">#<?= $invoice->id ?> <?= $invoice->customer ?></h5>
                        <small class="text-body-secondary"><?= $invoice->date ?></small>
                    </div>
                    <ol class="list-group mt-2 mb-2">
                            <li class="list-group-item">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:70%;">NAME</th>
                                            <th scope="col">PRICE</th>
                                            <th scope="col">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($products as $product): 
                                        $total += $product->price * $product->amount;      
                                    ?>
                                        <tr>
                                            <td><?= $product->name ?>, <?= $product->code ?></td>
                                            <td>€ <?= $product->price * $product->amount ?></td>
                                            <td><?= $product->amount ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <caption>TOTAL: € <?= $total ?></caption>
                                </table>
                            </li>
                    </ol>
                    <small class="text-body-secondary">FILE: <a href="./invoices/<?= basename($invoice->file) ?>" download><?= basename($invoice->file) ?></a></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item list-group-item-action">
                <div class="text-center">
                    <p>NO INVOICES</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>