<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@Symfony' => true,
        'phpdoc_param_order' => true,
        'yoda_style' => false,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => ['style' => 'post'],
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\EmptyFunctionBodyFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\MultilineCommentOpeningClosingAloneFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocTypesCommaSpacesFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer::name() => true,
    ])->setFinder($finder);
