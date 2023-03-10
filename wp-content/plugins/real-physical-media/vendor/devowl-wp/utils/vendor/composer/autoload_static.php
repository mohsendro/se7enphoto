<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba69add3a28bd8c141186be7d8c0bbed {
    public static $prefixLengthsPsr4 = [
        'M' => [
            'MatthiasWeb\\Utils\\Test\\' => 23,
            'MatthiasWeb\\Utils\\' => 18
        ]
    ];

    public static $prefixDirsPsr4 = [
        'MatthiasWeb\\Utils\\Test\\' => [
            0 => __DIR__ . '/../..' . '/test/phpunit'
        ],
        'MatthiasWeb\\Utils\\' => [
            0 => __DIR__ . '/../..' . '/src'
        ]
    ];

    public static $classMap = [
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php'
    ];

    public static function getInitializer(ClassLoader $loader) {
        return \Closure::bind(
            function () use ($loader) {
                $loader->prefixLengthsPsr4 = ComposerStaticInitba69add3a28bd8c141186be7d8c0bbed::$prefixLengthsPsr4;
                $loader->prefixDirsPsr4 = ComposerStaticInitba69add3a28bd8c141186be7d8c0bbed::$prefixDirsPsr4;
                $loader->classMap = ComposerStaticInitba69add3a28bd8c141186be7d8c0bbed::$classMap;
            },
            null,
            ClassLoader::class
        );
    }
}
