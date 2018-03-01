<?php

/*
 * This file is part of the "optimize native functions" fixer.
 *
 * Copyright (c) 2018 Leo Feyer
 *
 * @license MIT
 */

namespace LeoFeyer\PhpCsFixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class OptimizeNativeFunctionsFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Prefix native PHP functions which can be replaced with opcodes by the OPcache.',
            [
                new CodeSample(
'<?php

function foo($options)
{
    if (!is_array($options)) {
        throw new \InvalidArgumentException();
    }
}
'
                ),
            ],
            null,
            'Risky when any of the functions are overridden.'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function isRisky()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'LeoFeyer/'.parent::getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        $indexes = [];
        $functionNames = $this->getOptimizableFunctions();

        for ($index = 0, $count = $tokens->count(); $index < $count; ++$index) {
            $token = $tokens[$index];
            $tokenContent = $token->getContent();

            // Test if we are at a function call
            if (!$token->isGivenKind(T_STRING)) {
                continue;
            }

            $next = $tokens->getNextMeaningfulToken($index);

            if (!$tokens[$next]->equals('(')) {
                continue;
            }

            $functionNamePrefix = $tokens->getPrevMeaningfulToken($index);

            if ($tokens[$functionNamePrefix]->isGivenKind([T_DOUBLE_COLON, T_NEW, T_OBJECT_OPERATOR, T_FUNCTION])) {
                continue;
            }

            if ($tokens[$functionNamePrefix]->isGivenKind(T_NS_SEPARATOR)) {
                $prev = $tokens->getPrevMeaningfulToken($functionNamePrefix);

                // Skip if the call is to a constructor or to a function in a namespace other than the default
                if ($tokens[$prev]->isGivenKind([T_STRING, T_NEW])) {
                    continue;
                }
            }

            $lowerFunctionName = strtolower($tokenContent);

            if (!\in_array($lowerFunctionName, $functionNames, true)) {
                continue;
            }

            // Do not bother if the previous token is already a namespace separator
            if ($tokens[$tokens->getPrevMeaningfulToken($index)]->isGivenKind(T_NS_SEPARATOR)) {
                continue;
            }

            $indexes[] = $index;
        }

        $indexes = array_reverse($indexes);

        foreach ($indexes as $index) {
            $tokens->insertAt($index, new Token([T_NS_SEPARATOR, '\\']));
        }
    }

    /**
     * Returns the optimizable functions as array.
     *
     * @return string[]
     */
    private function getOptimizableFunctions()
    {
        return [
            'array_slice',
            'assert',
            'boolval',
            'call_user_func',
            'call_user_func_array',
            'chr',
            'constant',
            'count',
            'define',
            'defined',
            'dirname',
            'doubleval',
            'extension_loaded',
            'floatval',
            'func_get_args',
            'func_num_args',
            'function_exists',
            'get_called_class',
            'get_class',
            'gettype',
            'in_array',
            'intval',
            'is_array',
            'is_bool',
            'is_callable',
            'is_double',
            'is_float',
            'is_int',
            'is_integer',
            'is_long',
            'is_null',
            'is_object',
            'is_real',
            'is_resource',
            'is_string',
            'ord',
            'strlen',
            'strval',
        ];
    }
}
