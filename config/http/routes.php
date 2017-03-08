<?php
use Opulence\Routing\Router;

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(['controllerNamespace' => 'Project\\Application\\Http\\Controllers'], function (Router $router) {
    $router->get('/', 'Index@showHomePage', ['name' => 'home']);

    $router->group(['path' => '/admin'], function (Router $router) {
        $router->get('', 'Admin@showLoginPage', ['name' => 'login']);
        $router->get('/logout', 'Admin@logout', ['name' => 'logout']);
        $router->get('/page', 'Admin@showPagesPage', ['name' => 'pages']);
        $router->get('/categories', 'Admin@showCategoriesPage', ['name' => 'categories']);
        $router->get('/customer', 'Admin@showCustomersPage', ['name' => 'customers']);
        $router->get('/file', 'Admin@showFilesPage', ['name' => 'files']);
        $router->get('/download', 'Admin@showDownloadsPage', ['name' => 'downloads']);
    });
});
