<div class="container">
    
    <div>
        <p class="mt-3 mb-3">
            <span class="fs-4 fw-bold me-auto">ITEMS IN THE CART</span>
        </p>

        <?php if ($_SESSION["cart"]): ?>

            <a href="/cart-clean" class="btn btn-sm btn-outline-danger">Cart clean</a>
            <a href="/checkout" class="btn btn-sm btn-primary">CHECKOUT</a>

            <table class="table table-sm mt-3">
                <caption>Products in cart</caption>
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
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION["cart"] as $product): ?>
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
                            <td><a href="/cart-remove/<?= $product["id"] ?>" class="btn btn-sm btn-secondary">REMOVE</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="alert alert-secondary text-center">
                <p>NO ITEMS ADDED TO THE CART</p>
            </div>
            
        <?php endif; ?>

    </div>

</div>