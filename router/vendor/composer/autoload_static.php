<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3f95bb2f1a41ffedb536e652773df278
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pecee\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pecee\\' => 
        array (
            0 => __DIR__ . '/..' . '/pecee/simple-router/src/Pecee',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3f95bb2f1a41ffedb536e652773df278::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3f95bb2f1a41ffedb536e652773df278::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3f95bb2f1a41ffedb536e652773df278::$classMap;

        }, null, ClassLoader::class);
    }
}