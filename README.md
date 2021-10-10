# Kirby TinyMCE Field

Integrates the powerful TinyMCE editor into Kirby 3.

## Installation

### Standalone

1. Download the latest release
2. Unzip downloaded file
3. Copy/paste unzipped folder in your `/site/plugins` folder

### Installation with composer

```ssh
composer require clicktonext/kirby-tinymce-field
```

### Add as git submodule

```ssh
git submodule add https://github.com/clicktonext/kirby-tinymce-field.git site/plugins/kirby-tinymce-field
```

## Usage

```yaml
fields:
  text:
    label: Content
    type: tinymce
```

## Options

| Option | Type | Default | Description |
|:---|:--- |:---|:---|
| apiKey | string | null | API Key for TinyMCE (required) |
| height | int | 500 | Customize the payment preferences. |
| menubar | string | null | This option allows you to specify which menus should appear and the order that they appear in the menu bar at the top of TinyMCE. |
| plugins | array | [] | Used to include plugins for the editor. |
| toolbar | string | null | Modify toolbar items |

All the values can be updated in the `config.php` file with prefixed `clicktonext.tinymce.`.

