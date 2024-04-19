<div class="container">
    
    <div>
        <p class="mt-3 mb-3 d-flex justify-content-between">
            <span class="fs-4 fw-bold me-auto">ITEMS IN THE CART</span> 
            <a href="/cart-clean" class="btn btn-sm btn-outline-danger">Cart clean</a>
        </p>

        <?php if ($_SESSION["cart"]): ?>

            <form action="/checkout" method="POST" id="cart-form">
                <button type="submit" class="btn btn-sm btn-primary">CHECKOUT</button>

                <table class="table table-sm mt-3">
                    <caption>Products in cart</caption>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 20%;">NAME</th>
                            <th scope="col">BRAND</th>
                            <th scope="col">PRICE</th>
                            <th scope="col">CODE</th>
                            <th scope="col">REMAINING</th>
                            <th scope="col">AMOUNT</th>
                            <th scope="col">CATEGORY</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($_SESSION["cart"] as $product): ?>
                            <tr>
                                <td><a href="/<?= $product["id"] ?>"><?= $product["name"] ?></a></td>
                                <td><?= $product["brand"] ?></td>
                                <td>€ <?= $product["price"] ?></td>
                                <td><?= $product["code"] ?></td>
                                <td><?= $product["number"] ?></td>
                                <td>
                                    <input type="number" class="form-control amount-field" id="amount" name="amount<?= $product["id"] ?>" value="1" min="1" max="<?= $product["number"] ?>" autocomplete="off">
                                </td>
                                <td><?= $product["category"] ?></td>
                                <td><a href="/cart-remove/<?= $product["id"] ?>" class="btn btn-sm btn-secondary">REMOVE</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

        <?php else: ?>

            <div class="alert alert-secondary text-center">
                <p>NO ITEMS ADDED TO THE CART</p>
            </div>
            
        <?php endif; ?>

    </div>

</div>

<!-- Cart page js
<script src="/js/cart.js"></script>
-->