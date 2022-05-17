import jQuery from "jquery";
import axios from "axios";

require('select2');
require('flatpickr');
require('./plugins/ckeditor5/ckeditor');
window.regisEditor = function (id) {
  ClassicEditor.create(document.querySelector('#' + id), {
    language: 'zh-cn',
    simpleUpload: {
      uploadUrl: '/manager/upload/image'
    },
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
      msg('create ckeditor failed', 'error');
      console.log(error);
    })
}

window.createUploadImage = function () {

}

window.form_init = function () {
  //region select
  const columns = ['province_code', 'city_code', 'area_code', 'street_code', 'village_code'];
  jQuery('.region-select select').select2({
    theme: 'bootstrap4',
    width: '100%'
  }).on('change', function () {
    let column = jQuery(this).data('column'),
      id = jQuery(this).closest('.region-select').attr('id'),
      index = columns.indexOf(column);
    axios({
      url: "/api/region",
      method: 'GET',
      params: {column, code: jQuery(this).val()}
    }).then(response => {
      console.log(response)
      jQuery.each(columns.slice(index + 1), function (i, ele) {
        jQuery(`#${id} select[data-column=${ele}]`).empty();
      })
      jQuery(`#${id} select[data-column=${response.data.column}]`).select2({
        theme: 'bootstrap4',
        width: '100%',
        data: response.data.items
      })
    })
  })

  //icon select
  $('.select-icon').select2({
    theme: 'bootstrap4',
    escapeMarkup: function (markup) {
      return markup;
    },
    width: '100%',
    dropdownCssClass: 'select-icon-container',
    templateSelection: icon_format,
    templateResult: icon_format,
  })

  //select2
  $('select.bootstrap4-select').select2({
    theme: 'bootstrap4',
    width: '100%'
  }).on('select2:select', function (e) {
    $(this).trigger('blur');
  });

  $('.button[type="reset"]').unbind('click').click(function () {
    $('select.select-by').select2('val', '')
  })

  //datepicker
  $('.flatpickr').flatpickr({
    locale: 'zh',
    time_24hr: true
  })
}

function icon_format(icon) {
  let originalOpt = icon.element;
  return jQuery(`<span><i class="${jQuery(originalOpt).data('icon')}"></i></span>`)
}

