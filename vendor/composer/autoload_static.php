<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7a5fbd8b5a8da14cb9093785c03b15e4
{
    public static $files = array (
        'e20dc96720d010fd270ac492c6732f70' => __DIR__ . '/../..' . '/src/env/app.php',
        'bff91a780c719327e5ca6dc2232bfb4b' => __DIR__ . '/../..' . '/src/Utils/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7a5fbd8b5a8da14cb9093785c03b15e4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7a5fbd8b5a8da14cb9093785c03b15e4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7a5fbd8b5a8da14cb9093785c03b15e4::$classMap;

        }, null, ClassLoader::class);
    }
}
