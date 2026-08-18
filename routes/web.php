<?php

$router->get('/', ['HomeController', 'index']);

$router->get('/login', ['AuthController', 'login']);
$router->post('/login', ['AuthController', 'authenticate']);

$router->get('/logout', ['AuthController', 'logout']);

$router->get('/dashboard', ['HomeController', 'dashboard']);

$router->get('users', ['UsersController', 'index']);

$router->get('users/create', ['UsersController', 'create']);

$router->post('users/store', ['UsersController', 'store']);

$router->get('users/edit', ['UsersController', 'edit']);

$router->post('users/update', ['UsersController', 'update']);

$router->get('users/delete', ['UsersController', 'delete']);

$router->get('clients', ['ClientsController', 'index']);

$router->get('clients/create', ['ClientsController', 'create']);

$router->post('clients/store', ['ClientsController', 'store']);

$router->get('clients/edit', ['ClientsController', 'edit']);

$router->post('clients/update', ['ClientsController', 'update']);

$router->get('clients/delete', ['ClientsController', 'delete']);

$router->get('clients/show', ['ClientsController', 'show']);

$router->get('departments', ['DepartmentsController', 'index']);

$router->get('departments/create', ['DepartmentsController', 'create']);
$router->post('departments/store', ['DepartmentsController', 'store']);

$router->get('departments/edit', ['DepartmentsController', 'edit']);
$router->post('departments/update', ['DepartmentsController', 'update']);

$router->get('departments/delete', ['DepartmentsController', 'delete']);
