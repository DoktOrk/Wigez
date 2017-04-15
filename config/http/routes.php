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
    /** @see \Project\Application\Http\Controllers\Index::homePage() */
    $router->get(PATH_HOME, 'Index@homePage', [OPTIONS_NAME => ROUTE_HOME]);
    /** @see \Project\Application\Http\Controllers\Index::nope() */
    $router->get(PATH_NOPE, 'Index@nope', [OPTIONS_NAME => ROUTE_NOPE]);

    /** @see \Project\Application\Http\Controllers\User::loginForm() */
    $router->get(PATH_LOGIN, 'User@loginForm', [OPTIONS_NAME => ROUTE_LOGIN]);
    /** @see \Project\Application\Http\Controllers\User::login() */
    $router->post(PATH_LOGIN, 'User@login', [OPTIONS_NAME => ROUTE_LOGIN_POST]);
    /** @see \Project\Application\Http\Controllers\User::logout() */
    $router->get(PATH_LOGOUT, 'User@logout', [OPTIONS_NAME => ROUTE_LOGOUT]);


    $router->group(
        [
            'path' => PATH_API,
            'middleware' => [
                'Project\\Application\\Http\\Middleware\\Api',
            ]
        ],
        function (Router $router) {
            /** @see \Project\Application\Http\Controllers\File::csv() */
            $router->get(PATH_API_CSV, 'File@csv', [OPTIONS_NAME => ROUTE_API_CSV]);
            /** @see \Project\Application\Http\Controllers\File::download() */
            $router->get(PATH_API_DOWNLOAD, 'File@download', [OPTIONS_NAME => ROUTE_API_DOWNLOAD]);
        }
    );

    $router->group(
        [
            'path' => PATH_ADMIN,
            'middleware' => [
                'Project\\Application\\Http\\Middleware\\Authentication',
                'Project\\Application\\Http\\Middleware\\Authorization',
            ]
        ],
        function (Router $router) {
            $entities = [
                'pages'      => 'Page',
                'categories' => 'Category',
                'customers'  => 'Customer',
                'files'      => 'File',
                'downloads'  => 'Download',
                'users'      => 'User',
            ];

            $router->get(PATH_DASHBOARD, 'Admin@showDashboard', [OPTIONS_NAME => ROUTE_DASHBOARD]);

            foreach ($entities as $route => $controllerName) {
                $path = strtolower($controllerName);

                $router->get("/${path}", "${controllerName}@show", [OPTIONS_NAME => "${route}"]);
                $router->get("/${path}/new", "${controllerName}@new", [OPTIONS_NAME => "${route}-new"]);
                $router->post("/${path}/new", "${controllerName}@create", [OPTIONS_NAME => "${route}-create"]);
                $router->get("/${path}/:id/edit", "${controllerName}@edit", [OPTIONS_NAME => "${route}-edit"]);
                $router->put("/${path}/:id/edit", "${controllerName}@update", [OPTIONS_NAME => "${route}-update"]);
                $router->get("/${path}/:id/delete", "${controllerName}@delete", [OPTIONS_NAME => "${route}-delete"]);
            }

            $router->get("/files/:id/download", "File@download", [OPTIONS_NAME => "files-download"]);
        });
});
