<template>
  <k-field
      :input="_uid"
      v-bind="$props"
      class="k-tinymce-field">
    <editor
        ref="input"
        :api-key="apiKey"
        :id="_uid"
        :init="init"
        :value="value"
        v-on="$listeners"
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
        setup: function (editor) {
          editor.addShortcut(
              'ctrl+s', 'Custom save for WIN', function ($event) {
                app.$emit("submit", $event);
              },
              'cmd+s', 'Custom save for MAC', function ($event) {
                app.$emit("submit", $event);
              }
          );
        },
        image_list: async function (success) {
          let list = await app.imageList();
          success(list);
        },
        ...extend
      };
    }
  }
}
</script>
