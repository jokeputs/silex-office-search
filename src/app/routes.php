<?php
// App routes
$app->get('/', 'OfficeSearch\Controller\SearchController::indexAction')->bind('home');
$app->post('/search', 'OfficeSearch\Controller\SearchController::searchAction')->bind('search');