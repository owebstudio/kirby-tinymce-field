<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin('owebstudio/tinymce', [
    'fields' => include __DIR__ . '/config/fields.php',
    'options' => include __DIR__ . '/config/options.php'
]);
