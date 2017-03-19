<?php

use Opulence\Routing\Router;

require_once __DIR__ . '/../../config/http/constants.php';

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(['controllerNamespace' => 'Project\\Application\\Http\\Controllers'], function (Router $router) {
    /** @see \Project\Application\Http\Controllers\Index::showHomePage() */
    $router->get(PATH_HOME, 'Index@showHomePage', [OPTIONS_NAME => ROUTE_HOME]);

    /** @see \Project\Application\Http\Controllers\User::showLoginPage() */
    $router->get(PATH_LOGIN, 'User@showLoginPage', [OPTIONS_NAME => ROUTE_LOGIN]);
    /** @see \Project\Application\Http\Controllers\User::loginAction() */
    $router->post(PATH_LOGIN, 'User@loginAction', [OPTIONS_NAME => ROUTE_LOGIN_POST]);
    /** @see \Project\Application\Http\Controllers\User::logoutAction() */
    $router->get(PATH_LOGOUT, 'User@logoutAction', [OPTIONS_NAME => ROUTE_LOGOUT]);

    $router->group(['path' => PATH_ADMIN, 'middleware' => 'Project\\Application\\Http\\Middleware\\Authentication'],
        function (Router $router) {
            /** @see \Project\Application\Http\Controllers\Admin::showDashboardPage() */
            $router->get(PATH_DASHBOARD, 'Admin@showDashboardPage', [OPTIONS_NAME => ROUTE_DASHBOARD]);
            /** @see \Project\Application\Http\Controllers\Admin::showRandomPage() */
            $router->get(PATH_RANDOM, 'Admin@showRandomPage', [OPTIONS_NAME => ROUTE_RANDOM]);
            /** @see \Project\Application\Http\Controllers\Website::showPagesPage() */
            $router->get(PATH_PAGES, 'Website@showPagesPage', [OPTIONS_NAME => ROUTE_PAGES]);
            /** @see \Project\Application\Http\Controllers\File::showCategoriesPage() */
            $router->get(PATH_CATEGORIES, 'File@showCategoriesPage', [OPTIONS_NAME => ROUTE_CATEGORIES]);
            /** @see \Project\Application\Http\Controllers\File::showCustomersPage() */
            $router->get(PATH_CUSTOMERS, 'File@showCustomersPage', [OPTIONS_NAME => ROUTE_CUSTOMERS]);
            /** @see \Project\Application\Http\Controllers\File::showFilesPage() */
            $router->get(PATH_FILES, 'File@showFilesPage', [OPTIONS_NAME => ROUTE_FILES]);
            /** @see \Project\Application\Http\Controllers\File::showDownloadsPage() */
            $router->get(PATH_DOWNLOADS, 'File@showDownloadsPage', [OPTIONS_NAME => ROUTE_DOWNLOADS]);
        });
});
