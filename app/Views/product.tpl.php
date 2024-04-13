<ul class="nav container mt-2">
  <li class="nav-item">
    <?php if (in_array($product, $_SESSION["cart"])): ?>
        <a class="btn btn-sm btn-secondary" href="/cart-remove/<?= $product["id"] ?>">REMOVE FROM CART</a>
    <?php else: ?>
        <a class="btn btn-sm btn-primary" href="/cart-add/<?= $product["id"] ?>">ADD TO CART</a>
    <?php endif; ?>
  </li>
  <li class="nav-item ms-auto me-2">
    <a class="btn btn-sm btn-success" href="/<?= $product["id"] ?>/edit">EDIT</a>
  </li>
  <li class="nav-item">
    <form action="/<?= $product["id"] ?>/delete" method="POST">
        <button type="submit" class="btn btn-sm btn-danger">DELETE</button>
    </form>
  </li>
</ul>

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

    <table class="table table-sm mt-3">
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
            <tr>
                <td><?= $product["name"] ?></td>
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
        </tbody>
    </table>

</div>