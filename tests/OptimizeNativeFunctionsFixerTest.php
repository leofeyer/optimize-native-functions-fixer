<?php

declare(strict_types=1);

/*
 * This file is part of the "optimize native functions" fixer.
 *
 * Copyright (c) 2018 Leo Feyer
 *
 * @license MIT
 */

namespace LeoFeyer\PhpCsFixer\Tests;

use LeoFeyer\PhpCsFixer\OptimizeNativeFunctionsFixer;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

final class OptimizeNativeFunctionsFixerTest extends AbstractFixerTestCase
{
    /**
     * @param string $expected
     * @param string $input
     *
     * @dataProvider getTestData
     */
    public function testAppliesTheFix(string $expected, string $input): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            ['
<?php

\array_slice();
\assert();
\boolval();
\call_user_func();
\call_user_func_array();
\chr();
\constant();
\count();
\define();
\defined();
\dirname();
\doubleval();
\extension_loaded();
\floatval();
\func_get_args();
\func_num_args();
\function_exists();
\get_called_class();
\get_class();
\gettype();
\in_array();
\intval();
\is_array();
\is_bool();
\is_callable();
\is_double();
\is_float();
\is_int();
\is_integer();
\is_long();
\is_null();
\is_object();
\is_real();
\is_resource();
\is_string();
\ord();
\strlen();
\strval();

// These methods should not get prefixed
json_encode();
substr();
            ',
            '
<?php

array_slice();
assert();
boolval();
call_user_func();
call_user_func_array();
chr();
constant();
count();
define();
defined();
dirname();
doubleval();
extension_loaded();
floatval();
func_get_args();
func_num_args();
function_exists();
get_called_class();
get_class();
gettype();
in_array();
intval();
is_array();
is_bool();
is_callable();
is_double();
is_float();
is_int();
is_integer();
is_long();
is_null();
is_object();
is_real();
is_resource();
is_string();
ord();
strlen();
strval();

// These methods should not get prefixed
json_encode();
substr();
            '],
        ];
    }

    /**
     * Returns the fixer name.
     *
     * @return string
     */
    protected function getFixerName(): string
    {
        return 'LeoFeyer/optimize_native_functions';
    }

    /**
     * Registers the custom fixer.
     *
     * @return FixerFactory
     */
    protected function createFixerFactory(): FixerFactory
    {
        return FixerFactory::create()
            ->registerCustomFixers([
                new OptimizeNativeFunctionsFixer()
            ])
        ;
    }
}
