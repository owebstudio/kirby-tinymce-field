<?php

return [
    'tinymce' => [
        'props' => [
            /**
             * Unset inherited props
             */
            'after' => null,
            'before' => null,
            'height' => function (int|null $height = null) {
                return $height;
            },
            'imageAdvanceTab' => function (bool|null $imageAdvanceTab = null) {
                return $imageAdvanceTab;
            },
            'menubar' => function (string|null $menubar = null) {
                return $menubar;
            },
            'placeholder' => function (string|null $placeholder = null) {
                return $placeholder;
            },
            'plugins' => function (array|null $plugins = null) {
                return $plugins;
            },
            'toolbar' => function (string|null $toolbar = null) {
                return $toolbar;
            },
            'value' => function (string|null $value = null) {
                return trim($value ?? '');
            }
        ],
        'computed' => [
            'apiKey' => function () {
                return option('owebstudio.tinymce.apiKey');
            },
            'height' => function () {
                return $this->height ?? option('owebstudio.tinymce.height');
            },
            'imageAdvanceTab' => function () {
                return $this->imageAdvanceTab ?? option('owebstudio.tinymce.height');
            },
            'menubar' => function () {
                return $this->menubar ?? option('owebstudio.tinymce.menubar');
            },
            'plugins' => function () {
                return $this->plugins ?? option('owebstudio.tinymce.plugins');
            },
            'toolbar' => function () {
                $toolbar = $this->toolbar ?? option('owebstudio.tinymce.toolbar');

                if (is_array($toolbar) === true) {
                    $toolbar = implode(' | ', $toolbar);
                }

                return $toolbar;
            }
        ]
    ]
];
