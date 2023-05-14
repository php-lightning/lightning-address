<?php

declare(strict_types=1);

use Gacela\Framework\Bootstrap\GacelaConfig;
use Gacela\Framework\Gacela;
use Gacela\Router\Config\RouterGacelaConfig;
use Gacela\Router\Router;
use PhpLightning\Invoice\Infrastructure\Plugin\InvoiceRoutesPlugin;

$cwd = (string)getcwd();

require_once $cwd . '/vendor/autoload.php';

Gacela::bootstrap($cwd, static function (GacelaConfig $config): void {
    $config->setFileCache(true);
    $config->addAppConfig('lightning-config.dist.php', 'lightning-config.php');
    $config->addExtendConfig(RouterGacelaConfig::class);
    $config->addPlugin(InvoiceRoutesPlugin::class);
});

Gacela::get(Router::class)?->run();
