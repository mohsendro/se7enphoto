<?php

namespace DevOwl\RealPhysicalMedia\Vendor\Composer;

use DevOwl\RealPhysicalMedia\Vendor\Composer\Semver\VersionParser;
class InstalledVersions {
    private static $installed = [
        'root' => [
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'aliases' => [],
            'reference' => 'f43445d26b00d4fd4734ff3f80a129c36a7c26af',
            'name' => '__root__'
        ],
        'versions' => [
            '__root__' => [
                'pretty_version' => 'dev-master',
                'version' => 'dev-master',
                'aliases' => [],
                'reference' => 'f43445d26b00d4fd4734ff3f80a129c36a7c26af'
            ],
            'devowl-wp/real-utils' => [
                'pretty_version' => 'dev-feat/real-utils',
                'version' => 'dev-feat/real-utils',
                'aliases' => [],
                'reference' => 'cbb1dc60ff12db3200f68d8817434bd587157863'
            ],
            'devowl-wp/utils' => [
                'pretty_version' => 'dev-feat/multipackage',
                'version' => 'dev-feat/multipackage',
                'aliases' => [],
                'reference' => 'bb1d92ba33ae3925685c4cc5701938b71e37627b'
            ],
            'matthiasweb/wordpress-plugin-updater' => [
                'pretty_version' => 'dev-master',
                'version' => 'dev-master',
                'aliases' => [],
                'reference' => 'c801fd86c4cf97f3b0c59d653c5e7bce99cebb73'
            ],
            'matthiasweb/wpdb-batch' => [
                'pretty_version' => 'dev-master',
                'version' => 'dev-master',
                'aliases' => [],
                'reference' => '8558c8c07763cd01d2c89744f65da4880b4e38a0'
            ],
            'symfony/polyfill-intl-normalizer' => [
                'pretty_version' => 'dev-master',
                'version' => 'dev-master',
                'aliases' => [0 => '1.15.x-dev'],
                'reference' => 'e62715f03f90dd8d2f3eb5daa21b4d19d71aebde'
            ]
        ]
    ];
    public static function getInstalledPackages() {
        return \array_keys(self::$installed['versions']);
    }
    public static function isInstalled($packageName) {
        return isset(self::$installed['versions'][$packageName]);
    }
    public static function satisfies(
        \DevOwl\RealPhysicalMedia\Vendor\Composer\Semver\VersionParser $parser,
        $packageName,
        $constraint
    ) {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));
        return $provided->matches($constraint);
    }
    public static function getVersionRanges($packageName) {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        $ranges = [];
        if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
            $ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
        }
        if (\array_key_exists('aliases', self::$installed['versions'][$packageName])) {
            $ranges = \array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
        }
        if (\array_key_exists('replaced', self::$installed['versions'][$packageName])) {
            $ranges = \array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
        }
        if (\array_key_exists('provided', self::$installed['versions'][$packageName])) {
            $ranges = \array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
        }
        return \implode(' || ', $ranges);
    }
    public static function getVersion($packageName) {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        if (!isset(self::$installed['versions'][$packageName]['version'])) {
            return null;
        }
        return self::$installed['versions'][$packageName]['version'];
    }
    public static function getPrettyVersion($packageName) {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
            return null;
        }
        return self::$installed['versions'][$packageName]['pretty_version'];
    }
    public static function getReference($packageName) {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        if (!isset(self::$installed['versions'][$packageName]['reference'])) {
            return null;
        }
        return self::$installed['versions'][$packageName]['reference'];
    }
    public static function getRootPackage() {
        return self::$installed['root'];
    }
    public static function getRawData() {
        return self::$installed;
    }
    public static function reload($data) {
        self::$installed = $data;
    }
}
