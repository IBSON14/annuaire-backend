<?php

$router = new AltoRouter();
$router->setBasePath(APP_NAME);

$router->map('GET|POST', '/', array('c' => 'ControleurHome', 'a' => 'actionIndex'), 'home_index');

$router->map('GET', '/products/[i:id]/category', array('c' => 'ProductController', 'a' => 'productsByCategory'), 'product_category');
$router->map('GET', '/products/[i:id]', array('c' => 'ProductController', 'a' => 'productDetails'), 'product_details');
$router->map('GET', '/products', array('c' => 'ProductController', 'a' => 'allProducts'), 'product_all');

$router->map('POST','/services', array('c' => 'ServiceController', 'a' => 'addServices'), 'service_add');
$router->map('GET', '/services', array('c' => 'ServiceController', 'a' => 'allServices'), 'service_all');
$router->map('GET', '/services/[i:id]/category', array('c' => 'ServiceController', 'a' => 'servicesByCategory'), 'service_category');
$router->map('GET', '/services/[i:id]', array('c' => 'ServiceController', 'a' => 'serviceDetails'), 'service_details');

$router->map('GET', '/categories', array('c' => 'ServiceCategoryController', 'a' => 'allCategories'), 'category_all');

$router->map('GET|POST', '/login', array('c' => 'ControleurUser', 'a' => 'login'), 'user_login');
$router->map('GET|POST', '/logout', array('c' => 'ControleurUser', 'a' => 'logout'), 'user_logout');
$router->map('GET|POST', '/subscribe', array('c' => 'ControleurUser', 'a' => 'subscribe'), 'user_subscribe');
$router->map('GET|POST', '/confirm', array('c' => 'ControleurUser', 'a' => 'confirm'), 'user_confirm');

