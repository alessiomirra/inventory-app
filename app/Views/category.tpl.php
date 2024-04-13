<div class="container">

    <h4 class="mt-3 mb-4">ADD NEW CATEGORY</h4>

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

    <form action="/new-category" method="POST" id="new-category">
        <div class="mb-3">
            <label for="category" class="form-label">Category Name:</label>
            <input type="text" name="category" id="category" class="form-control" placeholder="Category Name">
            <small class="text-danger hidden" id="name-error">Category Name cannot contain space. Enter a single name</small>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary">Create</button>
        </div>
    </form>

    <hr>

    <h4 class="mt-3">CATEGORIES LIST</h4>
    <p class="mb-4 text-muted">Deleting a category, all products with that category will be deleted.</p>

    <?php if (count($categories)): ?>
        <table class="table table-sm">
            <caption>Categories List</caption>
            <thead>
                <tr>
                    <th scope="col" style="width: 80%;">NAME</th>
                    <th scope="col">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $category): ?>
                    <tr>
                        <td><?= $category->name ?></td>
                        <td>
                            <form action="/remove-category" method="POST">
                                <input type="hidden" name="name" value="<?= $category->name ?>">
                                <input type="hidden" name="id" value="<?= $category->id ?>">
                                <button type="submit" class="btn btn-sm btn-danger">REMOVE</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="mt-3 mb-3">
            <p>No categories created at the moment</p>
        </div>
    <?php endif; ?>

</div>


<!-- Script JS -->
<script src="/js/category.js"></script>