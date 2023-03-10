<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return [
    'Symfony\\Polyfill\\Intl\\Normalizer\\' => [$vendorDir . '/symfony/polyfill-intl-normalizer'],
    'MatthiasWeb\\WPU\\' => [$vendorDir . '/matthiasweb/wordpress-plugin-updater/src'],
    'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\WpdbBatch\\' => [$vendorDir . '/matthiasweb/wpdb-batch/src'],
    'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\' => [$vendorDir . '/devowl-wp/utils/src'],
    'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Test\\' => [
        $vendorDir . '/devowl-wp/real-utils/test/phpunit'
    ],
    'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\' => [$vendorDir . '/devowl-wp/real-utils/src'],
    'DevOwl\\RealPhysicalMedia\\Test\\' => [$baseDir . '/test/phpunit'],
    'DevOwl\\RealPhysicalMedia\\' => [$baseDir . '/inc', $baseDir . '/src/inc']
];
