<?php

// Service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// App repositories
$app['repository.office'] = $app->share(function ($app) {
    return new OfficeSearch\Repository\OfficeRepository($app['db']);
});

// App services
$app['service.office'] = $app->share(function ($app) {
    return new OfficeSearch\Service\OfficeService($app);
});