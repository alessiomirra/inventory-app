<div class="container">

    <div class="d-flex justify-content-between mt-3 mb-4">
        <h3>ADD PRODUCT</h3>
        <button class="btn btn-sm btn-primary" id="modal-button">LOAD FROM FILE</button>
    </div>

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

    <form action="/save" method="POST">

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
                <i class="bi bi-archive"></i>
            </span>
            <input 
                type="text" 
                name="name"
                class="form-control" 
                placeholder="Product Name" 
                aria-label="Product Name" 
                aria-describedby="basic-addon1"
                required
            >
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
                <i class="bi bi-bag-dash"></i>
            </span>
            <input 
                type="text" 
                name="brand"
                class="form-control" 
                placeholder="Product Brand" 
                aria-label="Product Brand" 
                aria-describedby="basic-addon1"
                required
            >
        </div>

        <div class="input-group mb-3">
            <label class="input-group-text" for="inputGroupSelect01">STATUS</label>
            <select name="status" class="form-select" id="inputGroupSelect01" required>
                <option value="in_stock" selected>IN STOCK</option>
                <option value="out_stock">OUT OF STOCK</option>
                <option value="arriving">ARRIVING</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-bag-dash"></i>
                    </span>
                    <input 
                        type="number" 
                        name="number"
                        class="form-control" 
                        placeholder="Number in stock" 
                        aria-label="Number in stock" 
                        aria-describedby="basic-addon1"
                        required
                    >
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-upc"></i>
                    </span>
                    <input 
                        type="number" 
                        name="code"
                        id="code"
                        class="form-control" 
                        placeholder="Product Code" 
                        aria-label="Product Code" 
                        aria-describedby="basic-addon1"
                        required
                    >
                </div>
                <div class="form-text text-danger hidden" id="code-error">Code must be 12</div>
            </div>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
                €
            </span>
            <input 
                type="text" 
                name="price"
                class="form-control" 
                placeholder="Product Price" 
                aria-label="Product Price" 
                aria-describedby="basic-addon1"
                pattern="\d+(\.\d+)?"
                required
            >
        </div>

        <div class="input-group mb-3">
            <label class="input-group-text" for="category">CATEGORY</label>
            <input name="category" class="form-control" id="category" list="categories" required>
            <datalist id="categories">
                <?php if (count($categories)): ?>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category->name ?>"></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="No Categories"></option>
                <?php endif; ?>
            </datalist>
        </div>

        <button type="submit" class="btn btn-sm btn-primary" id="submit-button">SAVE</button>

    </form>

</div>


<!-- Load File Modal -->
<div class="modal" tabindex="-1" id="modal-form">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-primary bg-gradient text-light">
            <h5 class="modal-title">LOAD PRODUCTS FROM FILE</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body mb-3">
            <div class="form-text mb-2" id="form-info">
                Only CSV extension supported. Data must have the following order:
                <ul>
                    <li>Product Name</li>
                    <li>Product Brand</li>
                    <li>Product Status</li>
                    <li>Number in stock</li>
                    <li>Product Code</li>
                    <li>Product Price</li>
                    <li>Product Category</li>
                </ul>
            </div>
            <div class="form-text text-success mb-2 hidden" id="form-success">
                PRODUCTS ADDED!
            </div>
            <form action="#" method="POST" class="row align-items-center" id="add-from-file" enctype="multipart/form-data">
                <div class="col-9">
                    <label class="visually-hidden" for="file">FILE</label>
                    <div class="input-group">
                        <div class="input-group-text"><i class="bi bi-card-text"></i></div>
                        <input type="file" name="document" class="form-control" id="file" placeholder="File" accepted=".csv" required>
                    </div>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary" id="request-button">
                        <div class="spinner-border spinner-border-sm hidden" role="status" id="response-loading">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>SEND</span>
                    </button>
                </div>
            </form>
            <div class="form-text mb-2 text-danger hidden" id="form-error">Something went wrong</div>
        </div>
    </div>
  </div>
</div>

<!-- Script -->
<script src="/js/new.js"></script>