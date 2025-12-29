<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Route Prefix
    |--------------------------------------------------------------------------
    |
    | The URL prefix for all admin panel routes
    |
    */
    'route_prefix' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to admin panel routes
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Enable Frontend Routes
    |--------------------------------------------------------------------------
    |
    | Whether to enable the default frontend routes (/, /news, /pages)
    | Set to false if you want to handle routes in your own application
    |
    */
    'enable_frontend_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Admin Guard
    |--------------------------------------------------------------------------
    |
    | The authentication guard to use for admin users
    |
    */
    'guard' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Title
    |--------------------------------------------------------------------------
    |
    | The title displayed in the admin panel
    |
    */
    'title' => 'Admin Panel',

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Number of items per page in list views
    |
    */
    'per_page' => 15,

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | Default date format for displaying dates
    |
    */
    'date_format' => 'Y-m-d H:i:s',
];
