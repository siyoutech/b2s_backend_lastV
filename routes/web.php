<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/login', [
    'uses' => 'AuthController@login'
]);


// $router->group(['prefix' => 'infos', "roles" => ["SalesManager"]], function () use ($router) {
//     $router->get('/', ["uses" => "AuthController@infos", "as" => "infos"]);
// });

$router->get('/infos', [
    "uses" => "AuthController@infos",
    "as" => "infos",
    "roles" => ["salesmanager"]
]);

$router->post('/addSalesManager', ['uses' => 'User\UsersController@addSalesManager']);
$router->post('/addSupplier', ['uses' => 'User\UsersController@addSupplier']);
$router->post('/addShop_Owner', ['uses' => 'User\UsersController@addShop_Owner']);
$router->get('/getInvalidUsers', ['uses' => 'User\UsersController@getInvalidUsers']);
$router->put('/validateUser/{userId}', ['uses' => 'User\UsersController@validateUser']);

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->delete('/{id}', ['uses' => 'User\UsersController@deleteUser']);
    $router->get('/{id}', ['uses' => 'User\UsersController@ShowUser']);
    $router->get('/', ['uses' => 'User\UsersController@UsersList']);
    $router->get('/GetUsersByRole/{id}', ['uses' => 'User\UsersController@GetUsersByRole']);
    $router->put('/{id}', ['uses' => 'User\UsersController@UpdateUser']);
    $router->get('/GetUserByRole/{id}', ['uses' => 'User\UsersController@GetUserByRole']);
    // $router->post('/addSalesManager', ['uses' => 'User\UsersController@affectSalesManagerToShop']);
});
$router->group(['prefix' => 'products'], function () use ($router) {
    $router->get('/by_suppliers', ['uses' => 'Product\ProductsController@GetAllSupplierWithProducts']);
    $router->get('/by_category', ['uses' => 'Product\ProductsController@GetAllCategoryWithProducts']);
    $router->get('/{id}', ['uses' => 'Product\ProductsController@showProduct']);
    $router->get('/', ['uses' => 'Product\ProductsController@productList']);
    $router->get('/category/{category_id}', ['uses' => 'Product\ProductsController@getProductsByCategory']);
    $router->get('/supplier/{supplier_id}', ['uses' => 'Product\ProductsController@getProductsBySupplier']);
    $router->post('/upload', ['uses' => 'Product\ProductsController@addProductsWithExcel']);  
});

// route for supplier function
$router->group(['prefix' => 'supplier', "roles" => ["Supplier"]], function () use ($router) {
    $router->get('/orders/shops', ['uses' => 'User\UsersController@getSupplierOrderShop']);
    $router->get('/salesmanagers/shops', ['uses' => 'User\UsersController@getSupplierSalesmanagerShop']);
    $router->post('/shops', ['uses' => 'User\UsersController@linkSalesManagerToShop']);
    $router->post('/salesmanager', ['uses' => 'User\UsersController@addSalesManagerToSupplier']);
    $router->delete('/salesmanager/{id}', ['uses' => 'User\UsersController@deleteSalesManager']);
    $router->put('/salesmanager/{id}', ['uses' => 'User\UsersController@updateSMCommesion']);
    $router->get('/salesmanager', ['uses' => 'User\UsersController@getSalesManagerList']);
    $router->get('/accounSupplier', ['uses' => 'User\UsersController@accounSupplier']);
    $router->post('/addShopToSupplier', ['uses' => 'User\UsersController@addShopToSupplier']);
});


// $router->group(['prefix' => 'supplier/categories', "roles" => ["Supplier"]], function () use ($router) {
//     $router->post('/{categoryId}', ['uses' => 'supplier\categoriesController@addCategoryToSupplier']);
//     $router->get('/', ['uses' => 'supplier\categoriesController@getSupplierCategory']);
//     $router->delete('/{categoryId}', ['uses' => 'supplier\categoriesController@deleteCategory']);

// });




// $router->group(['prefix' => 'categories'], function () use ($router) {
//     $router->get('/{id}', ['uses' => 'Category\CategoriesController@getCategoryList']);
// });

// $router->group(['prefix' => 'supplier/categories'], function () use ($router) {
//     $router->get('/', ['uses' => 'Category\CategoriesController@getCategories']);
//     $router->delete('/{id}', ['uses' => 'Category\CategoriesController@deleteCategory']);
//     $router->put('/{id}', ['uses' => 'Category\CategoriesController@updateCategory']);
//     $router->get('/getCategoryParent/{id}', ['uses' => 'Category\CategoriesController@getCategoryParent']);
//     $router->get('/getCategoryChild/{id}', ['uses' => 'Category\CategoriesController@getCategoryChild']);
//     $router->get('/{id}', ['uses' => 'Category\CategoriesController@ShowCategory']);
//     $router->post('/addCategory', ['uses' => 'Category\CategoriesController@addCategory']);
// });


$router->group(['prefix' => 'orders'], function () use ($router) {
    $router->post('/', ['uses' => 'OrdersController@addOrder']);
    // $router->put('/{id}', ['uses' => 'OrdersController@UpdateOrder']);
    // $router->delete('/{id}', ['uses' => 'OrdersController@DeleteOrder']);
    // $router->get('/getOrderlistByShop/{shopId}', ['uses' => 'OrdersController@getOrderlistByShop']);
    // $router->get('/getOrderlistBySupplier/{supplierId}', ['uses' => 'OrdersController@getOrderlistBySupplier']);
    // $router->get('/{orderId}', ['uses' => 'OrdersController@getOrder']);
    // $router->post('addToBasket', ['uses' => 'OrdersController@addToBasket']);
    // $router->put('editeOrder/{orderId}', ['uses' => 'OrdersController@editeOrder']);
    // $router->get('getOrderInBasket/{shopOwnerId}', ['uses' => 'OrdersController@getOrderInBasket']);
    $router->put('ValidateOrderByShop/{order_id}', ['uses' => 'OrdersController@ValidateOrderByShop']);
    // $router->get('getOrderNoValidated/{supplierId}', ['uses' => 'OrdersController@getOrderNoValidated']);
    $router->put('ValidateOrderBySupplier/{orderId}', ['uses' => 'OrdersController@ValidateOrderBySupplier']);
    // $router->get('getInvoice/{orderId}', ['uses' => 'OrdersController@getInvoice']);
    $router->put('/GetSupplierorderlist', ['uses' => 'OrdersController@GetSupplierorderlist']);
    $router->get('/Getpaidorderlist',['uses'=>'OrdersController@Getpaidorderlist']);
    $router->get('/tobevalidatedorders',['uses'=>'OrdersController@tobevalidatedorders']);
    

});


/**
 * Routes for resource statut
 */
$router->group(['prefix' => 'statuts'], function () use ($router) {
    $router->post('/', ['uses' => 'Statut\StatutsController@addStatut']);
    $router->get('/', ['uses' => 'Statut\StatutsController@statutlist']);
    $router->get('/{id}', ['uses' => 'Statut\StatutsController@ShowStatut']);
    $router->delete('/{id}', ['uses' => 'Statut\StatutsController@DeleteStatut']);
    $router->put('/{id}', ['uses' => 'Statut\StatutsController@UpdateStatut']);
});

/**
 * Routes for resource products
 */
// $app->get('products', 'ProductsController@all');
// $app->get('products/{id}', 'ProductsController@get');
// $app->post('products', 'ProductsController@add');
// $app->put('products/{id}', 'ProductsController@put');
// $app->delete('products/{id}', 'ProductsController@remove');


/**
 * Routes for resource siyoucommission
 */
$router->group(['prefix' => 'siyoucommissions'], function () use ($router) {
    $router->post('/', ['uses' => 'SiyoucommissionsController@addCommission']);
    $router->put('/{id}', ['uses' => 'SiyoucommissionsController@updateCommission']);
    $router->delete('/{id}', ['uses' => 'SiyoucommissionsController@DeleteCommission']);
    $router->get('/', ['uses' => 'SiyoucommissionsController@getcommision']);
});

/**
 * Routes for resource catalog
 */
$router->group(['prefix' => 'catalogs'], function () use ($router){
    $router->post('/',['uses'=>'Catalog\CatalogsController@AddCatalog']);
    $router->post('/AddProductstocatalog/{id}',['uses'=>'Catalog\CatalogsController@AddProductstocatalog']);
    $router->get('/TopProductslist',['uses'=>'Catalog\CatalogsController@TopProductslist']);
    $router->get('/supplier_Cataloglist',['uses'=>'Catalog\CatalogsController@supplier_Cataloglist']);
    $router->get('/Supplier_showCatalog/{id}',['uses'=>'Catalog\CatalogsController@Supplier_showCatalog']);
    $router->put('/{id}',['uses'=>'Catalog\CatalogsController@UpdateCatalog']);
    $router->delete('/{id}',['uses'=>'Catalog\CatalogsController@DeleteCatalog']);
    });

$router->group(['prefix'=>'companies'], function () use ($router){
    $router->post('/',['uses'=>'Logistics\CompaniesController@AddCompany']);
    $router->put('/UpdateCompany/{id}',['uses'=>'Logistics\CompaniesController@UpdateCompany']);
$router->delete('/DeleteCompany/{id}',['uses'=>'Logistics\CompaniesController@DeleteCompany']);
$router->get('/GetCompany/{id}',['uses'=>'Logistics\CompaniesController@GetCompany']);
$router->get('/GetCompaniesList',['uses'=>'Logistics\CompaniesController@GetCompaniesList']);
});

/**
 * Routes for resource tarif
 */
$router->post('/addTarif',['uses'=>'TarifsController@addTarif']);
$router->put('/updateTarif/{id}',['uses'=>'TarifsController@updateTarif']);
$router->get('/Gettarif/{id}',['uses'=>'TarifsController@Gettarif']);
$router->get('/gettarifs_list',['uses'=>'TarifsController@gettarifs_list']);
$router->delete('/DeleteTarif/{id}',['uses'=>'TarifsController@DeleteTarif']);
$router->delete('/DeleteTarifbycompany/{id}',['uses'=>'TarifsController@DeleteTarifbycompany']);
$router->get('/getTarifbycompany/{id}',['uses'=>'TarifsController@getTarifbycompany']);
$router->get('/getTarifbyweightrange/{id}/{min_kg}/{max_kg}',['uses'=>'TarifsController@getTarifbyweightrange']);


$router->get('/suppliercategories',['uses'=>'User\UsersController@suppliercategories']);


$router->group(['prefix' => 'supplier/products'], function () use ($router) {
    $router->post('/', ['uses' => 'Product\ProductsController@addProduct']);
    $router->put('/{id}', ['uses' => 'Product\ProductsController@updateProduct']);
    $router->delete('/{id}', ['uses' => 'Product\ProductsController@deleteProduct']);
    $router->get('/getproductslist',['uses'=>'Product\ProductsController@SupplierproductList']);
});


/**
 * Routes for resource funds
 */
$router->group(['prefix'=>'funds'], function () use ($router){
    $router->get('/',['uses'=>'Fund\OfflineFundsController@fundslist']);
    $router->put('/',['uses'=>'Fund\OfflineFundsController@reload']);
});

$router->group(['prefix'=>'deposite'], function () use ($router){
    $router->post('/',['uses'=>'DepositesiyousController@addDeposite']);
});
