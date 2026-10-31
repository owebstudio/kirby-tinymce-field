let loading = null;

/**
 * Loads TinyMCE once for all editors,
 * unless the page already has it
 */
export function load(script) {
  if (window.tinymce) {
    return Promise.resolve(window.tinymce);
  }

  loading ??= new Promise((resolve, reject) => {
    const element = document.createElement("script");

    element.src = script.src;
    element.referrerPolicy = "origin";

    // Tiny Cloud wants it, a self-hosted copy may not send CORS headers
    if (script.cloud === true) {
      element.crossOrigin = "anonymous";
    }

    element.onload = () => {
      if (window.tinymce) {
        resolve(window.tinymce);
      } else {
        element.onerror();
      }
    };

    element.onerror = () => {
      loading = null;
      element.remove();
      reject(new Error("TinyMCE could not be loaded from " + script.src));
    };

    document.head.append(element);
  });

  return loading;
}
