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
            "suggestions" => [ProductController::class, "searchFormSuggestions"],
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
            "checkout" => [InvoiceController::class, 'checkout'],
            "invoice" => [InvoiceController::class, "createInvoice"], 
            "delete-selected" => [ProductController::class, "deleteSelected"]
        ]
    ], 
    "configs" => [
        "recordsPerPage" => 10, 
        "searchProductBy" => ["name", "brand", "status", "code", "price up to"], 
    ], 
    "api" => [
        "key" => "3Rv7Tb1KwXn9sP5y6Aq0", 
        "localhost" => "http://127.0.0.1:",
        "port" => "5000"
    ]
];