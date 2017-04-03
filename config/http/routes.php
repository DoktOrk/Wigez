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
            $entities = [
                'pages'      => 'Page',
                'categories' => 'Category',
                'customers'  => 'Customer',
                'files'      => 'File',
                'downloads'  => 'Download',
            ];

            $router->get(PATH_DASHBOARD, 'Admin@showDashboardPage', [OPTIONS_NAME => ROUTE_DASHBOARD]);
            $router->get(PATH_RANDOM, 'Admin@showRandomPage', [OPTIONS_NAME => ROUTE_RANDOM]);

            foreach ($entities as $route => $controllerName) {
                $path = strtolower($controllerName);

                $router->get("/${path}", "${controllerName}@show", [OPTIONS_NAME => "${route}"]);
                $router->get("/${path}/new", "${controllerName}@new", [OPTIONS_NAME => "${route}-new"]);
                $router->post("/${path}/new", "${controllerName}@create", [OPTIONS_NAME => "${route}-create"]);
                $router->get("/${path}/:id/edit", "${controllerName}@edit", [OPTIONS_NAME => "${route}-edit"]);
                $router->put("/${path}/:id/edit", "${controllerName}@update", [OPTIONS_NAME => "${route}-update"]);
                $router->get("/${path}/:id/delete", "${controllerName}@delete", [OPTIONS_NAME => "${route}-delete"]);
            }
        });
});
