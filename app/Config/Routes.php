<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
$routes->get('/stores', 'Stores::index');
$routes->get('/stores/create', 'Stores::create');
$routes->get('/stores/edit/(:num)', 'Stores::edit/$1');
$routes->post('/stores/store', 'Stores::store');
$routes->put('/stores/update/(:num)', 'Stores::update/$1');
$routes->post('/stores/delete/(:num)', 'Stores::delete/$1');

$routes->get('/products', 'Products::index');
$routes->get('/products/create', 'Products::create');
$routes->get('/products/edit/(:num)', 'Products::edit/$1');
$routes->post('/products/store', 'Products::store');
$routes->put('/products/update/(:num)', 'Products::update/$1');
$routes->post('/products/delete/(:num)', 'Products::delete/$1');

$routes->get('/staffs', 'Staffs::index');
$routes->get('/staffs/create', 'Staffs::create');
$routes->get('/staffs/edit/(:num)', 'Staffs::edit/$1');
$routes->post('/staffs/store', 'Staffs::store');
$routes->put('/staffs/update/(:num)', 'Staffs::update/$1');
$routes->post('/staffs/delete/(:num)', 'Staffs::delete/$1');

$routes->get('/registers', 'Registers::index');
$routes->get('/registers/create', 'Registers::create');
$routes->get('/registers/edit/(:num)', 'Registers::edit/$1');
$routes->post('/registers/store', 'Registers::store');
$routes->put('/registers/update/(:num)', 'Registers::update/$1');
$routes->post('/registers/delete/(:num)', 'Registers::delete/$1');

$routes->get('/promos', 'Promos::index');
$routes->get('/promos/create', 'Promos::create');
$routes->get('/promos/edit/(:num)', 'Promos::edit/$1');
$routes->post('/promos/store', 'Promos::store');
$routes->put('/promos/update/(:num)', 'Promos::update/$1');
$routes->post('/promos/delete/(:num)', 'Promos::delete/$1');

$routes->get('/promoitems', 'Promoitems::index');
$routes->get('/promoitems/create', 'Promoitems::create');
$routes->get('/promoitems/edit/(:num)', 'Promoitems::edit/$1');
$routes->post('/promoitems/store', 'Promoitems::store');
$routes->put('/promoitems/update/(:num)', 'Promoitems::update/$1');
$routes->post('/promoitems/delete/(:num)', 'Promoitems::delete/$1');

$routes->get('/promostores', 'Promostores::index');
$routes->get('/promostores/create', 'Promostores::create');
$routes->get('/promostores/edit/(:num)', 'Promostores::edit/$1');
$routes->post('/promostores/store', 'Promostores::store');
$routes->put('/promostores/update/(:num)', 'Promostores::update/$1');
$routes->post('/promostores/delete/(:num)', 'Promostores::delete/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
