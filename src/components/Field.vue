<template>
  <k-field
      :input="_uid"
      v-bind="$props"
      class="k-tinymce-field">
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
    <editor
        ref="input"
        :api-key="apiKey"
        :disabled="disabled || isLocked"
        :id="_uid"
        :init="init"
        :value="value"
        @input="onInput"
    />
  </k-field>
</template>

<script>
import "regenerator-runtime/runtime";
import Editor from "@tinymce/tinymce-vue"

export default {
  inheritAttrs: false,
  components: {
    editor: Editor
  },
  props: {
    autofocus: Boolean,
    apiKey: String,
    disabled: Boolean,
    endpoints: Object,
    height: [Number, String],
    id: [Number, String],
    imageAdvanceTab: Boolean,
    label: String,
    license: Object,
    menubar: [Boolean, String],
    name: [Number, String],
    placeholder: String,
    plugins: Array,
    required: Boolean,
    toolbar: String,
    value: String
  },
  methods: {
    onInput(value) {
      this.$emit("input", value);
    },
    async imageList() {
      let list = [];

      await this.$api
          .get(this.endpoints.model + '/files', [])
          .then(response => {
            response.data.forEach(item => {
              list.push({
                title: item.filename,
                value: item.url
              });
            });
          });

      return list;
    }
  },
  computed: {
    init() {
      let app = this;
      const extend = panel.plugins.thirdParty.tinymce || {};

      return {
        height: app.height,
        menubar: app.menubar,
        image_advtab: app.imageAdvanceTab,
        placeholder: app.placeholder,
        plugins: app.plugins,
        selector: 'textarea',
        toolbar: app.toolbar,
        relative_urls: false,
        toolbar_mode: 'sliding',
        ui_mode: 'split',
        setup: function (editor) {
          // meta is Cmd on Mac and Ctrl on other systems
          editor.addShortcut('meta+s', 'Save', function () {
            app.$emit("submit");
          });

          // fix menubar of dropdown items for K4
          editor.on('PostRender', function () {
            let container = editor.getContainer();
            let uiContainer = document.querySelector('body > .tox.tox-tinymce-aux');

            if (uiContainer) {
              container.parentNode.appendChild(uiContainer);
            }
          });
        },
        image_list: async function (success) {
          let list = await app.imageList();
          success(list);
        },
        ...extend
      };
    },
    isLocked() {
      return this.license?.locked === true;
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

/* scroll areas of drawers clip the fullscreen editor and dialogs:
   the fade mask in Kirby 5 and translateZ in Kirby 4 */
.tox-fullscreen :is(.k-scrollable, .scroll-y-auto),
.tox-dialog__disable-scroll :is(.k-scrollable, .scroll-y-auto) {
  -webkit-mask-image: none !important;
  mask-image: none !important;
  transform: none !important;
}
</style>
