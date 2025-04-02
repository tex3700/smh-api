<?php

use database\DbHandler;
use Services\Product;

require 'ApiClient.php';
require 'database/DbHandler.php';
require 'Services/Entity.php';
require 'Services/Product.php';

// Конфигурация
$dbConfig = require 'config/database.php';

// Инициализация
$apiClient = new ApiClient('https://dummyjson.com');
$dbHandler = new DbHandler(...array_values($dbConfig));
$productService = new Product($apiClient, $dbHandler);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['service'])) {
        switch ($_GET['service']) {
            case 'products':
                if (isset($_GET['product'])) {
                    // Получение и сохранение iPhone
                    $product = $_GET['product'];
                    try {
                        $productService->fetchAndSaveProducts($product);
                        echo "Товары успешно сохранены!\n";
                    } catch (Exception $e) {
                        echo "Ошибка: " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "Need to add method to get all products\n";
                }
                break;
            case 'users':
                echo "Need to add method to get users\n";
                break;
            case 'posts':
                echo "Need to add method to get posts\n";
                break;
            case 'recipes':
                echo "Need to add method to get recipes\n";
                break;
            default:
                echo "No method to get this product\n";
        }
    } else {
        echo "Service not set\n";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['service'])) {
        switch ($data['service']) {
            case 'products':
                if (isset($data['data'])) {
                    // Добавление нового продукта
                    /*
                     * JSON:
                     * {
                     *  "service":"products",
                     *  "data": {
                            "title": "iPhone 15 Pro",
                            "price" : 999,
                            "description": "New flagship smartphone",
                            "brand": "Apple"
                           }
                        }
                     */
                    try {
                        $newProduct = $productService->addNew($data['data']);
                        echo "Новый товар добавлен: " . $newProduct['title'] . "\n";
                    } catch (Exception $e) {
                        echo "Ошибка при добавлении товара: " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "Need to receive data\n";
                }
                break;
            case 'users':
                echo "Need to add method to get users\n";
                break;
            case 'posts':
                echo "Need to add method to get posts\n";
                break;
            case 'recipes':
                echo "Need to add method to get recipes\n";
                break;
        }
    } else {
        echo "Service not set\n";
    }
} else {
    echo "Invalid request method.";
}
?>
