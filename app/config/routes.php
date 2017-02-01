<?php 

return array(
    'categories/([0-9]+)/edit'    => 'category/edit/$1',
    'categories/([0-9]+)/update'  => 'category/update/$1',
    'categories/([0-9]+)/destroy' => 'category/destroy/$1',
    'categories/([0-9]+)'         => 'category/show/$1',
    'categories/new'              => 'category/new',
    'categories/create'           => 'category/create',
    'categories'                  => 'category/index',

    'items/([0-9]+)/edit'         => 'item/edit/$1',
    'items/([0-9]+)/update'       => 'item/update/$1',
    'items/([0-9]+)/destroy'      => 'item/destroy/$1',
    'items/([0-9]+)'              => 'item/show/$1',
    'items/new'                   => 'item/new',
    'items/create'                => 'item/create',
    'items'                       => 'item/index',

    // Cart:
    'cart/checkout'               => 'cart/checkout', // actionAdd в CartController    
    'cart/delete/([0-9]+)'        => 'cart/delete/$1', // actionDelete в CartController    
    'cart/add/([0-9]+)'           => 'cart/add/$1', // actionAdd в CartController    
    'cart/addAjax/([0-9]+)'       => 'cart/addAjax/$1', // actionAddAjax в CartController
    'cart'                        => 'cart/index', // actionIndex в CartController

    'not-found'                   => 'error/index',
    
    '([a-zA-Z]+)'                 => 'main/catalog/$1',

    ''                            => 'main/index',
); 