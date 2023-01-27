# Kirby TinyMCE Field

Integrates the powerful **TinyMCE 5** wysiwyg editor into Kirby 3.

All the documentation about Plates template engine is in [the official documentation](https://www.tiny.cloud/docs/). You can find information in documents about getting API KEY: [Quick start](https://www.tiny.cloud/docs/quick-start/)

![preview](https://user-images.githubusercontent.com/3393422/136708309-81f4914d-5ff5-4ec4-8af0-3154ebfdfe81.png)

## Installation

1. Download the latest release
2. Unzip downloaded file
3. Copy/paste unzipped folder in your `/site/plugins` folder

## Usage

```yaml
fields:
    text:
        type: tinymce
        label: "Content"
        placeholder: "Enter text.."
        height: 750
```

## Options

| Option          | Type           | Description                                                                                                                    |
|:----------------|:---------------|:-------------------------------------------------------------------------------------------------------------------------------|
| apiKey          | string         | API Key for TinyMCE (required)                                                                                                 |
| height          | int string     | Sets the height of the entire editor, including the menu bar, toolbars, and status bar.                                        |
| imageAdvanceTab | boolean        | Advanced tab to the image dialog allowing you to add custom styles, spacing and borders to images.                             |
| menubar         | string boolean | Allows you to specify which menus should appear and the order that they appear in the menu bar at the top of TinyMCE.          |
| placeholder     | string         | Adds placeholder content that will be shown when the editor is empty.                                                          |
| plugins         | array          | Used to include plugins for the editor. Check out more [open source plugins](https://www.tiny.cloud/docs/plugins/opensource/). |
| toolbar         | string array   | Allows you to specify the buttons and the order that they will appear on TinyMCE’s toolbar                                     |

All the values can be updated in the `config.php` file with prefixed `owebstudio.tinymce.`

## Defaults

| Option          | Default                                                                                                                                                                                                           |
|:----------------|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| apiKey          | null                                                                                                                                                                                                              |
| height          | 500                                                                                                                                                                                                               |
| imageAdvanceTab | true                                                                                                                                                                                                              |
| menubar         | file edit view insert format tools table tc help                                                                                                                                                                  |
| placeholder     | null                                                                                                                                                                                                              |
| plugins         | ['advlist autolink lists link image charmap print preview anchor', 'searchreplace visualblocks code fullscreen', 'insertdatetime media table paste code help wordcount']                                          |
| toolbar         | undo redo &#124; formatselect &#124; bold italic forecolor backcolor &#124; alignleft aligncenter &#124; alignright alignjustify &#124; bullist numlist outdent indent &#124; removeformat &#124; code fullscreen |

## Sample options

```php
<?php

// /site/config/config.php
return [
    'owebstudio.tinymce' => [
        'apiKey' => 'YOUR_API_KEY',
        'height' => 750
    ]
];
```

## Extending the plugin

If you want to extend the TinyMCE instance, you can do it by creating a sample plugin like the example below:

### Custom formatting examples

- https://www.tiny.cloud/docs/demo/format-custom/
- https://www.tiny.cloud/docs/configure/content-formatting/

### Sample plugin

```javascript
panel.plugin("owebstudio/tinymce-extend", {
    thirdParty: {
        tinymce: {
            formats: {
                // Changes the default format for h1 to have a class of heading
                h1: {block: 'h1', classes: 'heading'}
            },
            style_formats: [
                // Adds the h1 format defined above to style_formats
                {title: 'My heading', format: 'h1'}
            ]
        }
    }
});

```

