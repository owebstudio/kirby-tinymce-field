<?php

namespace Oweb\Tinymce;

use Kirby\Form\Field\InputField;
use Kirby\Form\Mixin;
use Kirby\Http\Url;
use Kirby\Reflection\Attributes\Prop;
use Oweb\Tinymce\License\License;

/**
 * The TinyMCE editor as a field
 */
class TinymceField extends InputField
{
    use Mixin\Placeholder;

    /**
     * Plugins that are part of the TinyMCE core since version 6
     * or don't exist anymore. Loading them would fail.
     */
    public const CORE_PLUGINS = [
        'colorpicker',
        'contextmenu',
        'hr',
        'noneditable',
        'paste',
        'print',
        'tabfocus',
        'textcolor'
    ];

    /**
     * Toolbar buttons that were renamed in TinyMCE 6
     */
    public const TOOLBAR_ALIASES = [
        'fontselect'     => 'fontfamily',
        'fontsizeselect' => 'fontsize',
        'formatselect'   => 'blocks',
        'styleselect'    => 'styles'
    ];

    /**
     * Height of the entire editor, including the menu bar, toolbars and status bar
     */
    #[Prop]
    protected int|string $height = 500;

    /**
     * Adds the advanced tab to the image dialog (styles, spacing and borders)
     */
    #[Prop]
    protected bool $imageAdvanceTab = true;

    /**
     * Menus of the menu bar in their order, or `false` to hide the menu bar
     */
    #[Prop]
    protected bool|string $menubar = 'file edit view insert format tools table help';

    /**
     * TinyMCE plugins to load
     */
    #[Prop]
    protected array|string $plugins = [
        'advlist autolink lists link image charmap preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table help wordcount'
    ];

    /**
     * Toolbar buttons in their order, or `false` to hide the toolbar.
     * Array items are joined into groups.
     */
    #[Prop]
    protected array|bool|string $toolbar = [
        'undo redo',
        'blocks',
        'bold italic forecolor backcolor',
        'alignleft aligncenter',
        'alignright alignjustify',
        'bullist numlist outdent indent',
        'removeformat',
        'code fullscreen'
    ];

    protected string $value = '';

    /**
     * Plugin options are the defaults, blueprint options win
     */
    public function config(): array
    {
        $options = [];

        foreach (['height', 'imageAdvanceTab', 'menubar', 'placeholder', 'plugins', 'toolbar'] as $key) {
            $options[$key] = $this->kirby()->option('owebstudio.tinymce.' . $key);
        }

        return $options;
    }

    public function fill(mixed $value): static
    {
        return parent::fill(trim((string)($value ?? '')));
    }

    public function license(): License|null
    {
        return License::for('owebstudio/tinymce');
    }

    /**
     * Returns the plugin names one by one
     * and skips the ones TinyMCE 6+ can't load
     */
    public function plugins(): array
    {
        $plugins = is_array($this->plugins) === true ? implode(' ', $this->plugins) : $this->plugins;
        $plugins = preg_split('/[\s,]+/', $plugins, -1, PREG_SPLIT_NO_EMPTY);
        $plugins = array_diff($plugins, static::CORE_PLUGINS);

        return array_values(array_unique($plugins));
    }

    public function props(): array
    {
        return [
            ...parent::props(),
            'license' => $this->license()?->panel(),
            'script'  => $this->script()
        ];
    }

    /**
     * A locked editor can't be filled in, so it must not block saving the page
     */
    public function required(): bool
    {
        return $this->required === true && $this->license()?->isLocked() !== true;
    }

    /**
     * Returns where TinyMCE is loaded from: a self-hosted
     * copy or Tiny Cloud with the user's own API key
     */
    public function script(): array|null
    {
        $kirby = $this->kirby();

        if ($src = $kirby->option('owebstudio.tinymce.scriptSrc')) {
            return [
                'cloud'      => false,
                'licenseKey' => $kirby->option('owebstudio.tinymce.licenseKey'),
                'src'        => Url::to($src)
            ];
        }

        if ($apiKey = $kirby->option('owebstudio.tinymce.apiKey')) {
            $channel = $kirby->option('owebstudio.tinymce.cloudChannel', '8');

            return [
                'cloud' => true,
                'src'   => 'https://cdn.tiny.cloud/1/' . rawurlencode($apiKey) . '/tinymce/' . rawurlencode($channel) . '/tinymce.min.js'
            ];
        }

        return null;
    }

    /**
     * Joins toolbar groups and renames the
     * buttons that were renamed in TinyMCE 6
     */
    public function toolbar(): bool|string
    {
        $toolbar = $this->toolbar;

        if (is_bool($toolbar) === true) {
            return $toolbar;
        }

        if (is_array($toolbar) === true) {
            $toolbar = implode(' | ', $toolbar);
        }

        return preg_replace_callback(
            '/(?<![\w-])(' . implode('|', array_keys(static::TOOLBAR_ALIASES)) . ')(?![\w-])/',
            fn ($match) => static::TOOLBAR_ALIASES[$match[1]],
            $toolbar
        );
    }
}
