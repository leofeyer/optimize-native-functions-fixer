Deprecated
----------

This package is no longer needed as the feature is now available in
[PHP-CS-Fixer][3]. Use the `native_function_invocation` fixer with the
`@compiler_optimized` option.

---

Optimize Native Functions Fixer
===============================

About
-----

This custom PHP-CS-Fixer fixer prefixes native PHP functions which can be
replaced with opcodes by the OPcache.

```php
class MyClass
{
    public function isArray($var): bool
    {
        return \is_array($var);
    }
}
```

See [this pull request][2] to learn how prefixing an optimizable PHP function
made the Symfony DI container 783ms faster. And see [this pull request][3] if
you want to learn more about how the optimization works.

Installation
------------

Add the package via Composer:

```bash
php composer.phar require leofeyer/optimize-native-functions-fixer --dev
```

Configuration
-------------

Modify your `.php_cs` or `.php_cs.dist` file as follows:

```php
return PhpCsFixer\Config::create()
    ->setRules([
        // …
        'LeoFeyer/optimize_native_functions' => true,
    ])
    ->registerCustomFixers([
        new LeoFeyer\PhpCsFixer\OptimizeNativeFunctionsFixer()
    ])
    ->setRiskyAllowed(true)
```

Other options
-------------

If you do not like prefixing native functions, you can also import them with a
`use` statement (PHP 7 only).

```php
use function is_array;

class MyClass
{
    public function isArray($var): bool
    {
        return is_array($var);
    }
}
```

[1]: https://github.com/FriendsOfPHP/PHP-CS-Fixer
[2]: https://github.com/symfony/symfony/pull/25854
[3]: https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/3048
