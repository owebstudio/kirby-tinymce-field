<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin('owebstudio/tinymce', [
    'fields' => [
        'tinymce' => [
            'props' => [
                /**
                 * Unset inherited props
                 */
                'after' => null,
                'before' => null,
                'height' => function (?int $height = null) {
                    return $height;
                },
                'imageAdvanceTab' => function (bool $imageAdvanceTab = true) {
                    return $imageAdvanceTab;
                },
                'menubar' => function ($menubar = null) {
                    return $menubar;
                },
                'placeholder' => function (?string $placeholder = null) {
                    return $placeholder;
                },
                'plugins' => function (?array $plugins = null) {
                    return $plugins;
                },
                'toolbar' => function (?string $toolbar = null) {
                    return $toolbar;
                },
                'value' => function (?string $value = null) {
                    return trim($value ?? '');
                }
            ],
            'computed' => [
                'apiKey' => function () {
                    return option('owebstudio.tinymce.apiKey');
                },
                'height' => function () {
                    return $this->height ?? option('owebstudio.tinymce.height', 500);
                },
                'menubar' => function () {
                    return $this->menubar ?? option('owebstudio.tinymce.menubar', 'file edit view insert format tools table tc help');
                },
                'plugins' => function () {
                    return $this->plugins ?? option('owebstudio.tinymce.plugins', [
                            'advlist autolink lists link image charmap print preview anchor',
                            'searchreplace visualblocks code fullscreen',
                            'insertdatetime media table paste code help wordcount'
                        ]);
                },
                'toolbar' => function () {
                    $default = [
                        'undo redo',
                        'formatselect',
                        'bold italic forecolor backcolor',
                        'alignleft aligncenter',
                        'alignright alignjustify',
                        'bullist numlist outdent indent',
                        'removeformat',
                        'code fullscreen',
                    ];

                    $toolbar = $this->toolbar ?? option('owebstudio.tinymce.toolbar', $default);
                    if (is_array($toolbar) === true) {
                        $toolbar = implode(' | ', $toolbar);
                    }

                    return $toolbar;
                },
            ]
        ]
    ]
]);
