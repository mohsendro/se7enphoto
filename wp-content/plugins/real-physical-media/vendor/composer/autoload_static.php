<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7417c97f9dd3ca12a316481a33c288fe {
    public static $files = [
        'e69f7f6ee287b969198c3c9d6777bd38' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/bootstrap.php'
    ];

    public static $prefixLengthsPsr4 = [
        'S' => [
            'Symfony\\Polyfill\\Intl\\Normalizer\\' => 33
        ],
        'M' => [
            'MatthiasWeb\\WPU\\' => 16
        ],
        'D' => [
            'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\WpdbBatch\\' => 54,
            'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\' => 50,
            'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Test\\' => 54,
            'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\' => 49,
            'DevOwl\\RealPhysicalMedia\\Test\\' => 30,
            'DevOwl\\RealPhysicalMedia\\' => 25
        ]
    ];

    public static $prefixDirsPsr4 = [
        'Symfony\\Polyfill\\Intl\\Normalizer\\' => [
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer'
        ],
        'MatthiasWeb\\WPU\\' => [
            0 => __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src'
        ],
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\WpdbBatch\\' => [
            0 => __DIR__ . '/..' . '/matthiasweb/wpdb-batch/src'
        ],
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\' => [
            0 => __DIR__ . '/..' . '/devowl-wp/utils/src'
        ],
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Test\\' => [
            0 => __DIR__ . '/..' . '/devowl-wp/real-utils/test/phpunit'
        ],
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\' => [
            0 => __DIR__ . '/..' . '/devowl-wp/real-utils/src'
        ],
        'DevOwl\\RealPhysicalMedia\\Test\\' => [
            0 => __DIR__ . '/../..' . '/test/phpunit'
        ],
        'DevOwl\\RealPhysicalMedia\\' => [
            0 => __DIR__ . '/../..' . '/inc',
            1 => __DIR__ . '/../..' . '/src/inc'
        ]
    ];

    public static $classMap = [
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DevOwl\\RealPhysicalMedia\\Activator' => __DIR__ . '/../..' . '/inc/Activator.php',
        'DevOwl\\RealPhysicalMedia\\AdInitiator' => __DIR__ . '/../..' . '/inc/AdInitiator.php',
        'DevOwl\\RealPhysicalMedia\\Assets' => __DIR__ . '/../..' . '/inc/Assets.php',
        'DevOwl\\RealPhysicalMedia\\Core' => __DIR__ . '/../..' . '/inc/Core.php',
        'DevOwl\\RealPhysicalMedia\\Localization' => __DIR__ . '/../..' . '/inc/Localization.php',
        'DevOwl\\RealPhysicalMedia\\Util' => __DIR__ . '/../..' . '/inc/Util.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\AbstractInitiator' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/AbstractInitiator.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Assets' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/Assets.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Core' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/Core.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Localization' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/Localization.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\RatingHandler' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/RatingHandler.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\Service' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/Service.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\TransientHandler' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/TransientHandler.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\UtilsProvider' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/UtilsProvider.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\WelcomePage' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/WelcomePage.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\cross\\AbstractCrossSelling' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/cross/AbstractCrossSelling.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\cross\\CrossRealCategoryLibrary' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/cross/CrossRealCategoryLibrary.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\cross\\CrossRealCookieBanner' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/cross/CrossRealCookieBanner.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\cross\\CrossRealMediaLibrary' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/cross/CrossRealMediaLibrary.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\cross\\CrossRealPhysicalMedia' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/cross/CrossRealPhysicalMedia.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\cross\\CrossSellingHandler' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/cross/CrossSellingHandler.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\DevOwl\\RealUtils\\view\\Options' =>
            __DIR__ . '/..' . '/devowl-wp/real-utils/src/view/Options.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\Activator' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/Activator.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\Assets' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/Assets.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\Base' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/Base.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\Core' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/Core.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\ExpireOption' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/ExpireOption.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\Localization' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/Localization.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\PackageLocalization' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/PackageLocalization.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\PluginReceiver' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/PluginReceiver.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\Service' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/Service.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\Utils\\ServiceNoStore' =>
            __DIR__ . '/..' . '/devowl-wp/utils/src/ServiceNoStore.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\WpdbBatch\\AbstractBatch' =>
            __DIR__ . '/..' . '/matthiasweb/wpdb-batch/src/AbstractBatch.php',
        'DevOwl\\RealPhysicalMedia\\Vendor\\MatthiasWeb\\WpdbBatch\\Update' =>
            __DIR__ . '/..' . '/matthiasweb/wpdb-batch/src/Update.php',
        'DevOwl\\RealPhysicalMedia\\base\\Core' => __DIR__ . '/../..' . '/inc/base/Core.php',
        'DevOwl\\RealPhysicalMedia\\base\\UtilsProvider' => __DIR__ . '/../..' . '/inc/base/UtilsProvider.php',
        'DevOwl\\RealPhysicalMedia\\configuration\\ExcludeFolder' =>
            __DIR__ . '/../..' . '/inc/configuration/ExcludeFolder.php',
        'DevOwl\\RealPhysicalMedia\\configuration\\Lockfile' => __DIR__ . '/../..' . '/inc/configuration/Lockfile.php',
        'DevOwl\\RealPhysicalMedia\\configuration\\MetaSupText' =>
            __DIR__ . '/../..' . '/inc/configuration/MetaSupText.php',
        'DevOwl\\RealPhysicalMedia\\configuration\\Options' => __DIR__ . '/../..' . '/inc/configuration/Options.php',
        'DevOwl\\RealPhysicalMedia\\configuration\\SkipToFirstShortcut' =>
            __DIR__ . '/../..' . '/inc/configuration/SkipToFirstShortcut.php',
        'DevOwl\\RealPhysicalMedia\\handler\\AbstractHandler' =>
            __DIR__ . '/../..' . '/inc/handler/AbstractHandler.php',
        'DevOwl\\RealPhysicalMedia\\handler\\Handler' => __DIR__ . '/../..' . '/inc/handler/Handler.php',
        'DevOwl\\RealPhysicalMedia\\handler\\MediaFileRenamer' =>
            __DIR__ . '/../..' . '/inc/handler/MediaFileRenamer.php',
        'DevOwl\\RealPhysicalMedia\\listener\\FolderListener' =>
            __DIR__ . '/../..' . '/inc/listener/FolderListener.php',
        'DevOwl\\RealPhysicalMedia\\listener\\Listener' => __DIR__ . '/../..' . '/inc/listener/Listener.php',
        'DevOwl\\RealPhysicalMedia\\listener\\Lockfile' => __DIR__ . '/../..' . '/inc/listener/Lockfile.php',
        'DevOwl\\RealPhysicalMedia\\misc\\Seo' => __DIR__ . '/../..' . '/inc/misc/Seo.php',
        'DevOwl\\RealPhysicalMedia\\misc\\SpecialCharacters' => __DIR__ . '/../..' . '/inc/misc/SpecialCharacters.php',
        'DevOwl\\RealPhysicalMedia\\misc\\UploadDir' => __DIR__ . '/../..' . '/inc/misc/UploadDir.php',
        'DevOwl\\RealPhysicalMedia\\misc\\WpPosts' => __DIR__ . '/../..' . '/inc/misc/WpPosts.php',
        'DevOwl\\RealPhysicalMedia\\queue\\Queue' => __DIR__ . '/../..' . '/inc/queue/Queue.php',
        'DevOwl\\RealPhysicalMedia\\queue\\Row' => __DIR__ . '/../..' . '/inc/queue/Row.php',
        'DevOwl\\RealPhysicalMedia\\rest\\Handler' => __DIR__ . '/../..' . '/inc/rest/Handler.php',
        'DevOwl\\RealPhysicalMedia\\rest\\Queue' => __DIR__ . '/../..' . '/inc/rest/Queue.php',
        'DevOwl\\RealPhysicalMedia\\rest\\Seo' => __DIR__ . '/../..' . '/inc/rest/Seo.php',
        'DevOwl\\RealPhysicalMedia\\rest\\Service' => __DIR__ . '/../..' . '/inc/rest/Service.php',
        'DevOwl\\RealPhysicalMedia\\view\\AdminBar' => __DIR__ . '/../..' . '/inc/view/AdminBar.php',
        'DevOwl\\RealPhysicalMedia\\view\\CustomField' => __DIR__ . '/../..' . '/inc/view/CustomField.php',
        'MatthiasWeb\\WPU\\V4\\AjaxController' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/AjaxController.php',
        'MatthiasWeb\\WPU\\V4\\ClientConfig' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/ClientConfig.php',
        'MatthiasWeb\\WPU\\V4\\LicenseManager' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/LicenseManager.php',
        'MatthiasWeb\\WPU\\V4\\LicenseUIController' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/LicenseUIController.php',
        'MatthiasWeb\\WPU\\V4\\Parsedown\\Parsedown' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/Parsedown/Parsedown.php',
        'MatthiasWeb\\WPU\\V4\\Translations' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/Translations.php',
        'MatthiasWeb\\WPU\\V4\\WPLSApi' => __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/WPLSApi.php',
        'MatthiasWeb\\WPU\\V4\\WPLSClient' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/WPLSClient.php',
        'MatthiasWeb\\WPU\\V4\\WPLSController' =>
            __DIR__ . '/..' . '/matthiasweb/wordpress-plugin-updater/src/V4/WPLSController.php',
        'Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
        'Symfony\\Polyfill\\Intl\\Normalizer\\Normalizer' =>
            __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Normalizer.php'
    ];

    public static function getInitializer(ClassLoader $loader) {
        return \Closure::bind(
            function () use ($loader) {
                $loader->prefixLengthsPsr4 = ComposerStaticInit7417c97f9dd3ca12a316481a33c288fe::$prefixLengthsPsr4;
                $loader->prefixDirsPsr4 = ComposerStaticInit7417c97f9dd3ca12a316481a33c288fe::$prefixDirsPsr4;
                $loader->classMap = ComposerStaticInit7417c97f9dd3ca12a316481a33c288fe::$classMap;
            },
            null,
            ClassLoader::class
        );
    }
}
