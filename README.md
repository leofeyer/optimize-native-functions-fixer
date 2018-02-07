Optimize Native Functions Fixer
===============================

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

See [this pull request][1] to learn how prefixing an optimizable PHP function
made the Symfony DI container 783ms faster. And see [this pull request][2] if
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

Native fixer
------------

There is a [pull request][3] to enhance the `native_function_invocation` fixer
with this optimization, however, it will only be merged into the next major
version of PHP-CS-Fixer. This package backports the feature for PHP-CS-Fixer 2.

[1]: https://github.com/symfony/symfony/pull/25854
[2]: https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/3048
[3]: https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/3222
