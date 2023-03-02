<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit20dd696601859578824bbc02506d6df9 {
    public static $prefixLengthsPsr4 = [
        'D' => [
            'DevOwl\\RealUtils\\Test\\' => 22,
            'DevOwl\\RealUtils\\' => 17
        ]
    ];

    public static $prefixDirsPsr4 = [
        'DevOwl\\RealUtils\\Test\\' => [
            0 => __DIR__ . '/../..' . '/test/phpunit'
        ],
        'DevOwl\\RealUtils\\' => [
            0 => __DIR__ . '/../..' . '/src'
        ]
    ];

    public static $classMap = [
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php'
    ];

    public static function getInitializer(ClassLoader $loader) {
        return \Closure::bind(
            function () use ($loader) {
                $loader->prefixLengthsPsr4 = ComposerStaticInit20dd696601859578824bbc02506d6df9::$prefixLengthsPsr4;
                $loader->prefixDirsPsr4 = ComposerStaticInit20dd696601859578824bbc02506d6df9::$prefixDirsPsr4;
                $loader->classMap = ComposerStaticInit20dd696601859578824bbc02506d6df9::$classMap;
            },
            null,
            ClassLoader::class
        );
    }
}