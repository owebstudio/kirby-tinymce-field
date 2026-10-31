<template>
  <k-field
      v-bind="$props"
      :class="['k-tinymce-field', $attrs.class]"
      :input="id"
      :style="$attrs.style">
    <k-box
        v-if="isLocked"
        :theme="license.theme"
        class="k-tinymce-field-license">
      <k-text>{{ license.message }}</k-text>
      <k-button
          v-if="license.dialog"
          :dialog="license.dialog"
          icon="key"
          size="sm">
        {{ license.button }}
      </k-button>
    </k-box>
    <k-box
        v-if="error"
        :text="error"
        icon="alert"
        theme="negative"
    />
    <div v-else class="k-tinymce-field-editor">
      <textarea
          :id="id"
          ref="input"
          :value="value"
          hidden
      />
    </div>
  </k-field>
</template>

<script>
import { useId } from "vue";
import { load } from "../utils/tinymce.js";

export default {
  inheritAttrs: false,
  props: {
    counter: [Boolean, Object],
    disabled: Boolean,
    endpoints: Object,
    hasDiff: Boolean,
    height: [Number, String],
    help: String,
    id: {
      type: [Number, String],
      default: () => useId()
    },
    imageAdvanceTab: Boolean,
    label: String,
    license: Object,
    link: String,
    menubar: [Boolean, String],
    name: [Number, String],
    placeholder: String,
    plugins: Array,
    required: Boolean,
    script: Object,
    toolbar: [Boolean, String],
    translate: Boolean,
    type: String,
    value: String
  },
  emits: ["input", "submit"],
  data() {
    return {
      error: null
    };
  },
  computed: {
    isLocked() {
      return this.license?.locked === true;
    },
    isReadonly() {
      return this.disabled || this.isLocked;
    }
  },
  watch: {
    isReadonly(readonly) {
      this.editor?.mode.set(readonly ? "readonly" : "design");
    },
    value(value) {
      if (this.editor && value !== this.html) {
        this.html = value;
        this.editor.setContent(value ?? "");
      }
    }
  },
  created() {
    // TinyMCE's editor must not become reactive
    this.editor = null;
    this.html = this.value;
  },
  async mounted() {
    if (!this.script) {
      this.error = this.$t("owebstudio.tinymce.error.setup");
      return;
    }

    try {
      const tinymce = await load(this.script);

      if (this.isUnmounted) {
        return;
      }

      const editors = await tinymce.init(this.options());

      if (this.isUnmounted) {
        editors.forEach((editor) => editor.remove());
      }
    } catch (error) {
      console.error(error);
      this.error = this.$t("owebstudio.tinymce.error.load", {
        src: this.script.src
      });
    }
  },
  beforeUnmount() {
    this.isUnmounted = true;
    this.editor?.remove();
    this.editor = null;
  },
  methods: {
    async images() {
      if (!this.endpoints?.model) {
        return [];
      }

      const response = await this.$api.get(this.endpoints.model + "/files");

      return response.data.map((file) => ({
        title: file.filename,
        value: file.url
      }));
    },
    onChange(editor) {
      const html = editor.getContent();

      if (html !== this.html) {
        this.html = html;
        this.$emit("input", html);
      }
    },
    options() {
      const extend = this.$panel.plugins.thirdParty.tinymce || {};

      return {
        target: this.$refs.input,
        height: this.height,
        menubar: this.menubar,
        image_advtab: this.imageAdvanceTab,
        image_list: (success) => this.images().then(success),
        license_key: this.script.licenseKey ?? undefined,
        placeholder: this.placeholder,
        plugins: this.plugins,
        promotion: false,
        readonly: this.isReadonly,
        relative_urls: false,
        toolbar: this.toolbar,
        toolbar_mode: "sliding",
        ui_mode: "split",
        setup: (editor) => this.setup(editor),
        ...extend
      };
    },
    setup(editor) {
      // meta is Cmd on Mac and Ctrl on other systems
      editor.addShortcut("meta+s", "Save", () => {
        this.$emit("submit");
      });

      // menus and dialogs must be inside the Panel's drawers
      // and dialogs, otherwise they show up behind them
      editor.on("PostRender", () => {
        const sink = document.querySelector("body > .tox.tox-tinymce-aux");

        if (sink) {
          editor.getContainer().parentNode.appendChild(sink);
        }
      });

      editor.on("init", () => {
        this.editor = editor;
        this.html = editor.getContent();
      });

      editor.on("input change undo redo ExecCommand", () => this.onChange(editor));
    }
  }
}
</script>

<style>
.k-tinymce-field-license {
  margin-bottom: var(--spacing-2);
}

.tox-fullscreen .k-grid:where([data-variant=columns],
.tox-fullscreen [data-variant=fields]) > * {
  container: none;
}

.tox.tox-tinymce.tox-fullscreen {
  width: 100% !important;
}

/* the fade mask of scroll areas clips the fullscreen editor
   and dialogs inside drawers */
.tox-fullscreen .k-scrollable,
.tox-dialog__disable-scroll .k-scrollable {
  -webkit-mask-image: none !important;
  mask-image: none !important;
}
</style>
