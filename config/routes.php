<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/articles', ['controller' => 'Articles'], function($routes) {
  $routes->connect('/tagged/*', ['action' => 'tags']);
  }
);

Router::scope('/', function (RouteBuilder $routes) {
  // Register scoped middleware for in scopes.
  $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
    'httpOnly' => true,
  ]));

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
    $routes->applyMiddleware('csrf');

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /*
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);

    // home:_action   /:action   {"action":"index","controller":"Home","plugin":null}
    $routes->connect('/:action', ['controller' => 'Home']);
});

// routes.php
Router::scope('/', function ($routes) {
    $routes->connect(
        '/blog/:id-:slug', // 例えば /blog/3-CakePHP_Rocks
        ['controller' => 'Blogs', 'action' => 'view']
    )
    // 関数に引数を渡すためのルーティングテンプレートの中で、ルート要素を定義します。
    // テンプレートの中で、ルート要素を定義します。
    // ":id" をアクション内の $articleId にマップします。
    ->setPass(['id', 'slug'])
    // `id` が一致するパターンを定義します。
    ->setPatterns([
        'id' => '[0-9]+',
    ]);
});

// 名前付きでルートを接続
$routes->connect(
    '/login',
    ['controller' => 'Users', 'action' => 'login'],
    ['_name' => 'login']
);

// HTTP メソッド指定でルートを命名 (3.5.0 以降)
$routes->post(
    '/logout',
    ['controller' => 'Users', 'action' => 'logout'],
    'logout'
);

// 名前付きルートで URL の生成
$url = Router::url(['_name' => 'logout']);

// クエリー文字列引数付きの
// 名前付きルートで URL の生成
$url = Router::url(['_name' => 'login', 'username' => 'jimmy']);

Router::scope('/api', ['_namePrefix' => 'api:'], function ($routes) {
    // このルートの名前は `api:ping` になります。
    $routes->get('/ping', ['controller' => 'Pings'], 'ping');
});
// ping ルートのための URL を生成
Router::url(['_name' => 'api:ping']);

// plugin() で namePrefix を使用
Router::plugin('Contacts', ['_namePrefix' => 'contacts:'], function ($routes) {
    // ルートを接続。
});

// または、 prefix() で
Router::prefix('Admin', ['_namePrefix' => 'admin:'], function ($routes) {
    // ルートを接続。
});

Router::scope('/', function ($routes) {
    // 3.5.0 より前は `extensions()` を使用
    $routes->setExtensions(['json']);
    $routes->resources('Recipes');
});

Router::scope('/api', function ($routes) {
    $routes->resources('Articles', function ($routes) {
        $routes->resources('Comments');
    });
});

$routes->resources('Articles', [
    'only' => ['index', 'view']
]);

$routes->resources('Articles', [
    'actions' => ['update' => 'put', 'create' => 'add']
]);

$routes->resources('Articles', [
    'map' => [
        'deleteAll' => [
            'action' => 'deleteAll',
            'method' => 'DELETE'
        ]
    ]
]);

$routes->resources('Articles', [
    'map' => [
        'updateAll' => [
            'action' => 'updateAll',
            'method' => 'DELETE',
            'path' => '/update_many'
        ],
    ]
]);
// これは /articles/update_many に接続します。

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
