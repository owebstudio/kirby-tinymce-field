<?php

use Kirby\Cms\App as Kirby;
use Oweb\Tinymce\License\License;

require_once __DIR__ . '/lib/license/autoload.php';

$license = new License(
    plugin: 'owebstudio/tinymce',
    products: [15546],
    name: 'TinyMCE Editor',
    url: 'https://owebstudio.com/kirby/plugins/tinymce-editor'
);

Kirby::plugin('owebstudio/tinymce', $license->extend([
    'fields' => include __DIR__ . '/config/fields.php',
    'options' => include __DIR__ . '/config/options.php'
]), ...$license->args());
