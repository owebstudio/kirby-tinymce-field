<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin('clicktonext/tinymce', [
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
                'maxlength' => function (?int $maxlength = null) {
                    return $maxlength;
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
                    return trim($value);
                }
            ],
            'computed' => [
                'apiKey' => function () {
                    return option('clicktonext.tinymce.apiKey');
                },
                'height' => function () {
                    return $this->height ?? option('clicktonext.tinymce.height', 500);
                },
                'menubar' => function () {
                    return $this->menubar ?? option('clicktonext.tinymce.menubar', 'file edit view insert format tools table tc help');
                },
                'plugins' => function () {
                    return $this->plugins ?? option('clicktonext.tinymce.plugins', [
                            'advlist autolink lists link image charmap print preview anchor',
                            'searchreplace visualblocks code fullscreen',
                            'insertdatetime media table paste code help wordcount'
                        ]);
                },
                'toolbar' => function () {
                    return $this->toolbar ?? option('clicktonext.tinymce.toolbar', implode(' | ', [
                            'undo redo',
                            'formatselect',
                            'bold italic forecolor backcolor',
                            'alignleft aligncenter',
                            'alignright alignjustify',
                            'bullist numlist outdent indent',
                            'removeformat',
                            'code fullscreen',
                        ]));
                },
            ]
        ]
    ]
]);
