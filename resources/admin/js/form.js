import jQuery from "jquery";
import axios from "axios";
import Cropper from "cropperjs";
import flatpcikr from "flatpickr"
import {Mandarin} from "flatpickr/dist/l10n/zh";

require('select2');
require('flatpickr');
require('./plugins/ckeditor5/ckeditor');
require('jquery-validation');
require('jquery-pjax');

Dropzone.autoDiscover = false;

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

window.createUploadImage = function (elementId, maxFile, cropper, aspectRatio, uploaded) {
  new Dropzone('#' + elementId, {
    url: '/manager/upload/image',
    addRemoveLinks: true,
    method: 'POST',
    filesizeBase: 1024,
    uploadMultiple: false,
    thumbnailWidth: null,
    thumbnailHeight: 140,
    acceptedFiles: 'image/*',
    maxFiles: maxFile,
    _cropper: cropper,
    headers: {'X-CSRF-TOKEN': jQuery('meta[name=x-csrf-token]').attr('content')},
    success: function (file, response, e) {
      if (response.errno === 0) {
        let element = jQuery(this.element),
          _this = this;
        jQuery.each(response.data, (index, item) => {
          file.dataURL = item.url;
          let exist_input = element.closest('.form-group').find('input[name="' + element.data('input') + '"]');
          if (exist_input.length) {
            if (maxFile === 1 || (maxFile > 1 && _this.files.length === 1)) {
              exist_input.attr('value', item.url)
            } else {
              element.closest('.form-group').append(`<input type="text" class="input-none" name="${element.data('input')}" value="${item.url}">`);
            }
            exist_input.trigger('blur');
          } else {
            element.closest('.form-group').append(`<input type="text" class="input-none" name="${element.data('input')}" value="${item.url}">`);
          }
        })
      } else {
        msg(response.msg, 'error', 2000);
        this.removeFile(file);
      }
    },
    init: function () {
      if (uploaded.length) {
        let _this = this;
        jQuery.each(uploaded, (index, value) => {
          let mockFile = {
              name: value,
              size: null,
              url: value,
              cropped: true,
              accepted: true
            },
            response = {
              errno: 0,
              msg: '',
              data: [{url: value}]
            };
          _this.files.push(mockFile);
          _this.emit('addedfile', mockFile);
          _this.emit('success', mockFile, response);
          _this.emit('thumbnail', mockFile, mockFile.url);
          _this.emit('complete', mockFile);
        })
      }
      this.on('addedfile', function (file) {
        if ((typeof file.cropped === 'undefined' || !file.cropped) && this.options._cropper) {
          let _this = this,
            cropperImg = jQuery('div.modal[data-modal=cropper] .cropper-container img'),
            _cropper;
          this.removeFile(file);
          cropperImg.attr('src', getSrcImageFromBlob(file));
          Swal.fire({
            title: 'Cropper Image',
            icon: null,
            html: jQuery('div.modal[data-modal=cropper]').html(),
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            width: jQuery(window).width() > 968 ? 800 : jQuery(window).width(),
            didOpen: function (ele) {
              _cropper = new Cropper(jQuery(ele).find('.cropper-container img')[0], {
                autoCropArea: 1,
                aspectRatio: aspectRatio,
                movable: true,
                rotatable: true,
                scalable: true,
                // viewMode: 3,
                zoomEnable: true,
                zoomOnWheel: true,
                responsive: true
              })
              jQuery(ele).find('.rotate-left').click(function () {
                _cropper.rotate(-90);
              })
              jQuery(ele).find('.rotate-right').click(function () {
                _cropper.rotate(90);
              })
              jQuery(ele).find('.check').click(function () {
                _cropper.replace(_cropper.getCroppedCanvas().toDataURL(), false)
              })
            },
            preConfirm: function (ele) {
              let newFile = blobToFile(dataURItoBlob(_cropper.getCroppedCanvas().toDataURL()), file.name);
              newFile.cropped = true;
              _this.addFile(newFile)
            },
            didClose: function (ele) {
              _cropper.destroy();
            }
          })
        }
      })

      this.on('maxfilesexceeded', function (file) {
        if (this.options.maxFiles === 1) {
          this.removeAllFiles();
          this.addFile(file);
        } else {
          msg('最多允许' + this.options.maxFiles + '张图片，请删除后再添加', 'error');
          this.removeFile(file);
        }
      })

      this.on('removedfile', function (file) {
        let fileInput = jQuery(this.element).parents('.form-group').find('input[value="' + file.dataURL + '"]');
        if (this.files.length === 0) {
          fileInput.val(null);
        } else {
          fileInput.remove();
        }
        // _input.val(_input.val().replace(new RegExp(';?' + file.dataURL, 'gi'), ''))
        // console.log(_input.val())
      })
    }
  })
}


window.createUploadFile = function (elementId, uploaded) {
  new Dropzone('#' + elementId, {
    url: '/manager/upload/file',
    addRemoveLinks: true,
    method: 'POST',
    filesizeBase: 1024,
    uploadMultiple: false,
    paramName: 'file',
    createImageThumbnails: false,
    // disablePreviews: true,
    maxFiles: 1,
    headers: {'X-CSRF-TOKEN': jQuery('meta[name=x-csrf-token]').attr('content')},
    success: function (file, response, e) {
      if (response.errno === 0) {
        let element = jQuery(this.element),
          exist_input = element.closest('.form-group').find('input[name="' + element.data('input') + '"]');
        if (exist_input.length) {
          exist_input.attr('value', response.data.url);
        } else {
          element.closest('.form-group').append(`<input type="text" class="input-none" name="${element.data('input')}" value="${response.data.url}">`);
        }
      } else {
        msg(response.msg, 'error', 2000);
        this.removeFile(file);
      }
    },
    error: function (file, response) {
      if (typeof response.message !== 'undefined') {
        msg(response.message, 'error');
      }
    },
    init: function () {
      if (uploaded) {
        let mockFile = {
            name: uploaded,
            size: null,
            url: uploaded,
          },
          response = {
            errno: 0,
            msg: '',
            data: [{url: uploaded}]
          };
        this.files.push(mockFile);
        this.emit('addedfile', mockFile);
        this.emit('success', mockFile, response);
        this.emit('thumbnail', mockFile, mockFile.url);
        this.emit('complete', mockFile);
      }

      this.on('removedfile', function (file) {
        jQuery(this.element).parents('.form-group').find('input').remove()
        // _input.val(_input.val().replace(new RegExp(';?' + file.dataURL, 'gi'), ''))
        // console.log(_input.val())
      })

      this.on('maxfilesexceeded', function (file) {
        this.removeAllFiles();
        this.addFile(file);
      })
    }
  })
}

window.form_init = function () {
  _ready();

  //form validate
  const validate = $('.form-xhr').validate({
    rules: [],
    errorElement: 'span',
    allowSubmit: true,
    errorPlacement: (error, element) => {
      error.addClass('invalid-feedback');
      element.parents('.form-group').append(error);
    },
    highlight: (element, errorClass, validClass) => {
      $(element).addClass('is-invalid');
    },
    unhighlight: (element, errorClass, validClass) => {
      $(element).removeClass('is-invalid');
    },
    onkeyup: false,
    submitHandler: function (form) {
      const _this = this;
      if (!_this.settings.allowSubmit) {
        return false;
      }
      _this.settings.allowSubmit = false;
      let $submitForm = $(form);
      axios({
        url: $submitForm.attr('action'),
        method: $submitForm.attr('method'),
        data: $submitForm.serialize()
      }).then(response => {
        msg(response.data.message, 'success');
        if (typeof response.data.redirect !== 'undefined') {
          $.pjax({
            url: response.data.redirect,
            container: '#pjax-container'
          })
        } else {
          $.pjax.reload('#pjax-container');
        }
      }).catch(error => {
        if (error.response.status === 422) {
          let errors = [];
          $.each(error.response.data.errors, (name, message) => {
            message = message.join('; ');
            if ($('.form-xhr').find('input[name="' + name + '"],select[name="' + name + '"],textarea[name="' + name + '"]').length) {
              errors[name] = message;
            } else {
              msg(message, 'error');
            }
          })
          validate.showErrors(errors);
        }
        _this.settings.allowSubmit = true;
      })
    }
  })
  //json add & delete
  $('.jsonArray .add-item').click(function () {
    let _parent = $(this).parents('.jsonArray'),
      maxItems = _parent.data('max-items');
    if (typeof maxItems !== 'undefined' && maxItems !== 0 && _parent.find('.jsonItem').length >= maxItems) {
      msg(`该条目最多${maxItems}条数据`, 'warning');
      return;
    }
    let _html = html_special_chars_decode(_parent.find('script[type="text/html"]').html()),
      lastItem = _parent.find('.jsonItem').last(),
      _index = lastItem.length ? ($(lastItem).data('index') + 1) : 0;
    console.log(_html);
    _html = _html.replace(/rep_index/g, _index);
    $(this).before(_html);
    _ready();
  })
}

//functions

function _ready() {
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
  flatpickr('.flatpickr', {
    locale: Mandarin,
    time_24hr: true
  })

  $('.jsonArray .reduce-item').unbind('click').click(function () {
    let _parent = $(this).parents('.jsonArray'),
      leastOne = _parent.data('least-ont') === 1;
    if (leastOne && _parent.find('.jsonItem').length <= 1) {
      msg(`此条目至少一条数据`, 'warning');
      return;
    }
    $(this).parents('.jsonItem').remove();
  })
}

function icon_format(icon) {
  let originalOpt = icon.element;
  return jQuery(`<span><i class="${jQuery(originalOpt).data('icon')}"></i></span>`)
}

function dataURItoBlob(dataURI) {
  let byteString = atob(dataURI.split(',')[1]),
    ab = new ArrayBuffer(byteString.length),
    ia = new Uint8Array(ab);
  for (let i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }
  return new Blob([ab], {type: 'image/jpeg'});
}

function getSrcImageFromBlob(blob) {
  let urlCreator = window.URL || window.webkitURL;
  return urlCreator.createObjectURL(blob);
}

function blobToFile(theBlob, fileName) {
  theBlob.lastModifiedDate = new Date();
  theBlob.name = fileName;
  return theBlob;
}

function html_special_chars_decode(str) {
  str = str.replace(/&amp;/g, '&');
  str = str.replace(/&lt;/g, '<');
  str = str.replace(/&gt;/g, '>');
  str = str.replace(/&quot;/g, '"');
  str = str.replace(/&#039;/g, "'");
  return str;
}
