<div class="container">

    <h3 class="mt-3 mb-4">EDIT PRODUCT</h3>

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

    <form action="#" method="POST">

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
                value="<?= $product["name"] ?>"
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
                value="<?= $product["brand"] ?>"
                required
            >
        </div>

        <div class="input-group mb-3">
            <label class="input-group-text" for="inputGroupSelect01">Product Category</label>
            <select name="status" class="form-select" id="inputGroupSelect01" required>
                <option value="in_stock" <?= $product["status"] === "in_stock" ? "selected" : "" ?>>IN STOCK</option>
                <option value="out_stock" <?= $product["status"] === "out_stock" ? "selected" : "" ?>>OUT OF STOCK</option>
                <option value="arriving" <?= $product["status"] === "arriving" ? "selected" : "" ?>>ARRIVING</option>
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
                        value="<?= $product["number"] ?>"
                        required
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-upc"></i>
                    </span>
                    <input 
                        type="number" 
                        name="code"
                        class="form-control" 
                        placeholder="Product Code" 
                        aria-label="Product Code" 
                        aria-describedby="basic-addon1"
                        value="<?= $product["code"] ?>"
                        required
                    >
                </div>
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
                value="<?= $product["price"] ?>"
                required
            >
        </div>

        <div class="input-group mb-3">
            <label class="input-group-text" for="categorySelect">CATEGORY</label>
            <select name="category" class="form-select" id="categorySelect" value="<?= $product["category"] ?>" required>
                <option value=""></option>
                <?php if (count($categories)): ?>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category->name ?>" <?php $product["category"] === $category->name ? "selected" : "" ?>><?= $category->name ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No Categories. First create a new one</option>
                <?php endif; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-sm btn-primary">SAVE</button>

    </form>

</div>