<?php
    $data = json_encode($products);
?>

<div class="container">

    <p class="mt-3 mb-3 d-flex justify-content-between">
        <span class="fs-4 fw-bold">CHECKOUT</span>
        <span class="me-3"><a href="/cart" class="text-decoration-none"><i class="bi bi-backspace"></i> Back to Cart</a></span>
    </p>

    <div class="container">

        <div class="row gx-5 mb-3">

            <div class="col-md-4">

                <p class="fw-bold"><?= count($products) ?> PRODUCTS</p>

                <!-- Products in cart list -->
                <div class="mb-2" id="cart-container">

                    <ol class="list-group list-group-numbered">
                        <?php foreach($products as $product): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold"><?= $product["name"] ?></div>
                                    <?= $product["brand"] ?>
                                    <p class="text-muted">AMOUNT: <?= $product["amount"] ?></p>
                                </div>
                                <span class="badge text-bg-primary rounded-pill">€ <?= $product["price"] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>

                </div>
                
                <!-- Total items price -->
                <div class="border mb-3 d-flex justify-content-between px-2 pt-2 border rounded">
                    <p class="fw-bold">TOTAL PRICE</p>
                    <p class="badge text-bg-primary rounded-pill">€ <?= $price ?></p>
                </div>

            </div>

            <div class="col-md-8">

                <form action="#" method="POST" id="checkout-form">
                    <!-- Products & Total price hidden fields -->
                    <input type="hidden" id="products" name="products" value="<?= htmlspecialchars(json_encode($products),  ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="price" value="<?= $price ?>">

                    <p class="fw-bold">CUSTOMER DETAILS</p>

                    <!-- Errors --> 
                    <div class="alert alert-danger text-center hidden mb-2" id="request-error">
                        <p>Something went wrong in request. Try Again</p>
                    </div>
                    <div class="alert alert-danger text-center hidden mb-2" id="error">
                        <p>Missing Fields</p>
                    </div>
                    <div class="alert alert-danger text-center hidden" id="address-error">
                        <p>Enter a valid address</p>
                    </div>
                    <div class="alert alert-danger text-center hidden" id="generic-error">
                        <p>There're errors in your form fill-in</p>
                    </div>
                    <!---->
                    
                    <!-- Customer informations form -->
                    <div class="mb-2">

                        <div class="d-flex mb-3">
                            <div class="form-check me-3">
                                <input type="radio" class="form-check-input" name="buyer" id="person" value="person" checked>
                                <label for="person" class="form-check-label">Person</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="buyer" id="company" value="customer">
                                <label for="company" class="form-check-label">Company</label>
                            </div>
                        </div>

                        <!-- Person informations -->
                        <div id="person-form">

                            <div class="row" id="person-name-container">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">First Name</span>
                                        <input name="first-name" type="text" id="first-name" class="form-control" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Last Name</span>
                                        <input name="last-name" id="last-name" type="text" class="form-control" placeholder="Last Name">
                                    </div>
                                </div>
                            </div>

                            <div class="row hidden" id="company-name-container">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Name</span>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Company Name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Nation</span>
                                        <input type="text" name="nation" id="nation" class="form-control" placeholder="Nation">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">City</span>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="City">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">ZIP</span>
                                        <input type="text" name="zip" id="zip" class="form-control" placeholder="ZIP">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Address</span>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Address">
                                    </div>
                                </div>
                            </div>

                            <div class="row hidden" id="vat-container">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">VAT</span>
                                        <input type="text" class="form-control" name="vat" id="vat" placeholder="VAT">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="phone"><i class="bi bi-phone"></i></span>
                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone Number">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="email"><i class="bi bi-envelope"></i></span>
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    
                    <hr>

                    <p class="fw-bold">PAYMENT METHOD</p>

                    <div class="alert alert-danger text-center hidden" id="payment-error">
                        <p>Enter a payment method</p>
                    </div>

                    <div>
                        <div class="d-flex mb-3">
                            <div class="form-check me-3">
                                <input type="radio" class="form-check-input" name="payment" id="cash" value="CASH">
                                <label for="cash" class="form-check-label">CASH</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="payment" id="card" value="CARD">
                                <label for="card" class="form-check-label">CREDIT CARD</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <p class="fw-bold">SHIPPING ADDRESS</p>

                    <div class="alert alert-danger text-center hidden" id="shipping-error">
                        <p>Enter a valid shipping address</p>
                    </div>

                    <div class="mb-3">
                        <div class="form-check me-2">
                            <input type="checkbox" class="form-check-input" id="in_store" name="in_store" value="true">
                            <label for="in_store" class="form-check-label">In Store Pick Up</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="same_address" name="same_address" value="true">
                            <label for="same_address" class="form-check-label">Shipping address is the same as personal address</label>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="shipping-box">
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Nation</span>
                                <input type="text" name="ship_nation" id="ship_nation" class="form-control" placeholder="Nation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text">City</span>
                                <input type="text" name="ship_city" id="ship_city" class="form-control" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text">ZIP</span>
                                <input type="text" name="ship_zip" id="ship_zip" class="form-control" placeholder="ZIP">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Address</span>
                                <input type="text" name="ship_address" id="ship_address" class="form-control" placeholder="Address">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <textarea name="shipping-notes" id="shipping-notes" class="form-control" cols="30" rows="2" placeholder="Shipping Notes"></textarea>
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button class="btn btn-primary" type="submit">
                            <div class="spinner-border hidden" role="status" id="loading">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span id="button-text">CHECKOUT</span>
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

    

</div>


<!-- MODAL -->

<div class="modal" tabindex="-1" id="success-modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">INVOICE CREATED</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Invoice successfully created! Check Invoices page</p>
      </div>
      <div class="modal-footer">
        <a href="/invoices" class="btn btn-primary">INVOICES PAGE</a>
      </div>
    </div>
  </div>
</div>

<!-- Checkout page js -->
<script type="text/javascript">
    let products = <?php echo $data; ?>;
</script>

<script src="/js/APIrequest.js"></script>
<script src="/js/checkout.js"></script>