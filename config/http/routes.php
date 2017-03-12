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
    /** @see \Project\Application\Http\Controllers\Index::showHomePage() */
    $router->get('/', 'Index@showHomePage', ['name' => 'home']);

    /** @see \Project\Application\Http\Controllers\User::showLoginPage() */
    $router->get('/login', 'User@showLoginPage', ['name' => 'login']);
    /** @see \Project\Application\Http\Controllers\User::logoutAction() */
    $router->get('/logout', 'User@logoutAction', ['name' => 'logout']);

    $router->group(['path' => '/admin', 'middleware' => 'Project\\Application\\Http\\Middleware\\Authentication'], function (Router $router) {
        /** @see \Project\Application\Http\Controllers\Admin::showDashboardPage() */
        $router->get('/', 'Admin@showDashboardPage', ['name' => 'dashboard']);
        /** @see \Project\Application\Http\Controllers\Admin::showRandomPage() */
        $router->get('/random', 'Admin@showRandomPage', ['name' => 'random']);
        /** @see \Project\Application\Http\Controllers\Website::showPagesPage() */
        $router->get('/page', 'Website@showPagesPage', ['name' => 'pages']);
        /** @see \Project\Application\Http\Controllers\File::showCategoriesPage() */
        $router->get('/categories', 'File@showCategoriesPage', ['name' => 'categories']);
        /** @see \Project\Application\Http\Controllers\File::showCustomersPage() */
        $router->get('/customer', 'File@showCustomersPage', ['name' => 'customers']);
        /** @see \Project\Application\Http\Controllers\File::showFilesPage() */
        $router->get('/file', 'File@showFilesPage', ['name' => 'files']);
        /** @see \Project\Application\Http\Controllers\File::showDownloadsPage() */
        $router->get('/download', 'File@showDownloadsPage', ['name' => 'downloads']);
    });
});
