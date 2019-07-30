<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf29a6195b0a6d553983392a199d62eca
{
    public static $files = array (
        '00e9ae4a734c54025423d746ffd96646' => __DIR__ . '/../..' . '/function/helpers.php',
    );

    public static $classMap = array (
        'Auth' => __DIR__ . '/../..' . '/classes/Auth.php',
        'Brand' => __DIR__ . '/../..' . '/classes/Brand.php',
        'Category' => __DIR__ . '/../..' . '/classes/Category.php',
        'Customers' => __DIR__ . '/../..' . '/classes/Customers.php',
        'DB' => __DIR__ . '/../..' . '/function/DB.php',
        'Dashboard' => __DIR__ . '/../..' . '/classes/Dashboard.php',
        'Order' => __DIR__ . '/../..' . '/classes/Order.php',
        'ProductAttributes' => __DIR__ . '/../..' . '/classes/ProductAttributes.php',
        'Products' => __DIR__ . '/../..' . '/classes/Products.php',
        'Purchase' => __DIR__ . '/../..' . '/classes/Purchase.php',
        'PurchaseReturn' => __DIR__ . '/../..' . '/classes/PurchaseReturn.php',
        'Report' => __DIR__ . '/../..' . '/classes/Report.php',
        'SalesReturn' => __DIR__ . '/../..' . '/classes/SalesReturn.php',
        'Session' => __DIR__ . '/../..' . '/function/Session.php',
        'Suppliers' => __DIR__ . '/../..' . '/classes/Suppliers.php',
        'Validation' => __DIR__ . '/../..' . '/function/Validation.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitf29a6195b0a6d553983392a199d62eca::$classMap;

        }, null, ClassLoader::class);
    }
}
