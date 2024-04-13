<?php 
    $configs = require './config/app.config.php'; 
    $page = getParam('page') ? (int) getParam('page') : 1;
    $search = getParam('search', null);
    $searchBy = getParam('search-by', "name");
    $searchByArr = $configs["configs"]["searchProductBy"];
    $orderBy = getParam('order-by', 'id');
    $orderDir = getParam('order-dir', 'DESC');

    $url = "search=$search&search-by=$searchBy&order-by=$orderBy&order-dir=$orderDir";
?>

<div class="container">

    <?php
        if(!empty($_SESSION['success'])) :?>
            <div class="alert alert-success mb-3">
                <?php
                    echo htmlentities($_SESSION['success']);
                    $_SESSION['success'] = '';
                ?>
            </div>
        <?php
        endif;

        if(!empty($_SESSION['message'])) :?>
            <div class="alert alert-danger mb-3">
                <?php
                    echo htmlentities($_SESSION['message']);
                    $_SESSION['message'] = '';
                ?>
            </div>
            <?php
        endif;
    ?>

    <ul class="nav nav-underline">
        <li class="nav-item mt-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= $orderBy==='id' && $orderDir==="DESC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>&search=<?=$search?>&search-by=<?=$searchBy?>&order-by=id&order-dir=DESC">DESCENDING ORDER</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='id' && $orderDir==="ASC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>&search=<?=$search?>&search-by=<?=$searchBy?>&order-by=id&order-dir=ASC">ASCENDING ORDER</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='price' && $orderDir==="DESC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>&search=<?=$search?>&search-by=<?=$searchBy?>&order-by=price&order-dir=DESC">PRICE DESCENDENT</a>
                    </li>
                    <li class="breadcrumb-item <?= $orderBy==='price' && $orderDir==="ASC" ? 'fw-bold' : '' ?>">
                        <a href="?page=<?= $page - 1 ?>&search=<?=$search?>&search-by=<?=$searchBy?>&order-by=price&order-dir=ASC">PRICE ASCENDENT</a>
                    </li>
                </ol>
            </nav>
        </li>
        <li class="nav-item ms-auto mt-1">
            <form action="/" method="GET" id="search-form">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="order-by" value="<?= $orderBy ?>">
                <input type="hidden" name="order-dir" value="<?= $orderDir ?>">
                <select name="search-by" id="search-by" class="search-select">
                    <option value="">SEARCH BY</option>
                    <?php foreach($searchByArr as $searchby): ?>
                        <option value="<?= $searchby ?>" <?= getParam('search-by') === $searchby ? "selected" : "" ?> ><?= strtoupper($searchby) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search" id="search" placeholder="Search" required value="<?= getParam('search') ? getParam('search') : "" ?>">
                <datalist id="statusList">
                    <option value="IN STOCK"></option>
                    <option value="OUT OF STOCK"></option>
                    <option value="ARRIVING"></option>
                </datalist>
                <button type="submit" class="search-button" id="search-button">SEARCH</button>
            </form>
        </li>
    </ul>
    
    <div class="mb-4 mt-3 home-header">

        <p class="fs-5"><?= $count ?> PRODUCTS</p>

    </div>
    
    <!-- Pagination -->
    <?php if ($count > $configs["configs"]["recordsPerPage"]): ?>
        <?php 
            $totalPages = $search ? ceil(count($products) / $configs["configs"]["recordsPerPage"]) : ceil($count / getConfig("recordsPerPage"));
            $prevDisabled = ($page <= 1) ? "disabled" : "";
            $nextDisabled = ($page >= $totalPages) ? "disabled" : "";
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?= $prevDisabled ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&<?= $url ?>">Previous</a>
                </li>
                <?php for ($i=1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&<?= $url ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $nextDisabled ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&<?= $url ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif;?>
    <!-- Pagination -->

    <table class="table table-sm">
        <caption><?= $search !== null ? count($products)." "."Products" : "Products List" ?></caption>
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
                    <td><a href="/<?= $product->id ?>"><?= $product->name ?></a></td>
                    <td><?= $product->brand ?></td>
                    <td>€ <?= $product->price ?></td>
                    <td class="<?= $product->status === "out_stock" ? "text-danger" : "" ?> <?= $product->status === "arriving" ? "text-success" : "" ?>">
                        <?php echo showStatus($product->status) ?>
                    </td>
                    <td><?= $product->code ?></td>
                    <td class="<?= remainingColor($product->number) ?>"><?= $product->number ?></td>
                    <td><?= $product->category ?></td>
                    <td><?= $product->added ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>



<!-- Script JS -->
<script src="/js/home.js"></script>