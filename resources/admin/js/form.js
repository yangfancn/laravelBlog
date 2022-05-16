import jQuery from "jquery";
const { ClassicEditor } = require("./plugins/ckeditor5/ckeditor");


window.regisEditor = function(id) {
  ClassicEditor.create(document.querySelector('#' + id), {
    language: 'zh-cn',
    simpleUpload: {
      uploadUrl: '/manager/upload/image'
    },
    minHeight: '300px',
    withCredentials: true,
  })
    .then(function (editor) {
      let notification = editor.plugins.get('Notification');
      notification.on('show:warning', function (evt, data) {
        msg(data.message, 'error', 2200, 'toast-bottom-right', data.title);
        evt.stop()
      })
      editor.model.document.on('change:data', () => {
        jQuery('#' + id).val(editor.getData())
      });
    })
    .catch(function (error) {
      msg('create ckedior faild', 'error');
      console.log(error);
    })
}
