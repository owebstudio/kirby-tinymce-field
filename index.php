<?php

use Kirby\Cms\App as Kirby;
use Oweb\Tinymce\License\License;

require_once __DIR__ . '/lib/license/autoload.php';

load([
    'Oweb\Tinymce\TinymceField' => 'lib/TinymceField.php'
], __DIR__);

$license = new License(
    plugin: 'owebstudio/tinymce',
    products: [15546],
    name: 'TinyMCE Editor',
    url: 'https://owebstudio.lemonsqueezy.com/checkout/buy/1f3ad982-7cfa-4f53-a1c2-fc2d12d80b2f'
);

Kirby::plugin('owebstudio/tinymce', $license->extend([
    'fields' => [
        'tinymce' => Oweb\Tinymce\TinymceField::class
    ],
    'options' => include __DIR__ . '/config/options.php',
    'translations' => include __DIR__ . '/config/translations.php'
]), ...$license->args());
