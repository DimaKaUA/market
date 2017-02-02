<?php 

return array(

    // Categories CRUD:
    'categories/([0-9]+)/edit'    => 'category/edit/$1',
    'categories/([0-9]+)/update'  => 'category/update/$1',
    'categories/([0-9]+)/destroy' => 'category/destroy/$1',
    'categories/([0-9]+)'         => 'category/show/$1',
    'categories/new'              => 'category/new',
    'categories/create'           => 'category/create',
    'categories'                  => 'category/index',

    // Items CRUD:
    'items/([0-9]+)/edit'         => 'item/edit/$1',
    'items/([0-9]+)/update'       => 'item/update/$1',
    'items/([0-9]+)/destroy'      => 'item/destroy/$1',
    'items/([0-9]+)'              => 'item/show/$1',
    'items/new'                   => 'item/new',
    'items/create'                => 'item/create',
    'items'                       => 'item/index',

    // Orders CRUD:
    'orders/([0-9]+)/edit'         => 'order/edit/$1',
    'orders/([0-9]+)/update'       => 'order/update/$1',
    'orders/([0-9]+)/destroy'      => 'order/destroy/$1',
    'orders/([0-9]+)'              => 'order/show/$1',
    'orders/new'                   => 'order/new',
    'orders/create'                => 'order/create',
    'orders'                       => 'order/index',

    // Cart:
    'cart/checkout'               => 'cart/checkout', // checkoutAction to CartController    
    'cart/delete/([0-9]+)'        => 'cart/delete/$1', // deleteAction to CartController    
    'cart/add/([0-9]+)'           => 'cart/add/$1', // addAction to CartController    
    'cart'                        => 'cart/index', // indexAction to CartController

    'not-found'                   => 'error/index', // indexAction to ErrorController
    
    // Catalog. Shopping
    '([a-zA-Z]+)'                 => 'main/catalog/$1',

    ''                            => 'main/index',
); 