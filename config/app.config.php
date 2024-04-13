<?php 

use App\Controllers\ProductController;
use App\Controllers\InvoiceController;
use App\Controllers\LoginController;

return [
    "routes" => [
        "GET" => [
            "/" => [ProductController::class, 'home'], 
            ":id" => [ProductController::class, 'show'], 
            ":id/edit" => [ProductController::class, 'edit'], 
            "cart" => [ProductController::class, 'cart'],
            "add" => [ProductController::class, 'addProduct'], 
            "categories/:categoryName" => [ProductController::class, 'categoryPage'], 
            "cart-add/:id" => [ProductController::class, 'addToCart'],
            "cart-remove/:id" => [ProductController::class, 'removeFromCart'],
            "cart-clean" => [ProductController::class, 'cleanCart'],
            "category" => [ProductController::class, 'showCategoryForm'],
            "login" => [LoginController::class, 'showLogin'],
            "categories" => [ProductController::class, "getCategories"],
            "invoices" => [InvoiceController::class, 'invoices'],
            "checkout" => [InvoiceController::class, 'checkout'],
        ], 
        "POST" => [
            "login" => [LoginController::class, 'login'], 
            "logout" => [LoginController::class, 'logout'], 
            "save" => [ProductController::class, 'saveProduct'],
            "save-request" => [ProductController::class, 'saveFromFile'],
            ":id/edit" => [ProductController::class, 'saveProduct'],
            ":id/delete" => [ProductController::class, 'deleteProduct'],
            "new-category" => [ProductController::class, 'addCategory'], 
            "remove-category" => [ProductController::class, 'deleteCategory'], 
            "invoice" => [InvoiceController::class, "createInvoice"]
        ]
    ], 
    "configs" => [
        "recordsPerPage" => 10, 
        "searchProductBy" => ["name", "brand", "status", "code", "price up to"], 
    ]
];