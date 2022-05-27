import jQuery from "jquery";
import axios from "axios";
import Swal from 'admin-lte/plugins/sweetalert2/sweetalert2.min'
require('jquery-pjax');

window.list_init = function (){
  jQuery('a.status').click(function () {
    let _this = this,
      _url = jQuery(this).data('href'),
      _status = parseInt(jQuery(this).data('status'));
    clickWaitAxiosResponse(_this, {
      url: _url,
      method: 'PUT',
      data: {status: _status === 1 ? 0 : 1}
    }, (response) => {
      msg(response.data.message ? response.data.message : "修改状态成功", 'success');
      jQuery(_this).html(response.data.status ? '<i class="fa fa-check text-info"></i>'
        : '<i class="fa fa-times text-info"></i>');
      jQuery(_this).closest('tr').find('td[data-field="status"] small')
        .text(response.data.status ? '启用' : '禁用')
        .data('status', response.data.data.status);
    })
  })

  jQuery('a.delete').click(function () {
    let _this = this,
      _url = jQuery(this).data('href');
    Swal.fire({
      title: '确定删除吗?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '确定',
      cancelButtonText: '取消'
    }).then((result) => {
      if (result.isConfirmed) {
        clickWaitAxiosResponse(_this, {
          url: _url,
          method: "DELETE"
        }, (response) => {
          msg(response.data.message ? response.data.message : '删除成功', 'warning');
          jQuery.pjax.reload('#pjax-container');
        })
      }
    })
  })

  jQuery('td input.list-rank').on('change', function () {
    let value = jQuery(this).val();
    if (!Number.isInteger(parseInt(value))) {
      msg('请输入一个整数', 'error');
      return false;
    }
    axios({
      url: rankUrl,
      method: 'PATCH',
      data: jQuery(this).closest('tr').data('id')
    }).then(response => {
      msg(response.data.message ? response.data.message : '排序成功');
    })
  })
}


function clickWaitAxiosResponse(element, axiosOpt, success, error = null, onlyOnce = false) {
  if (jQuery(element).attr('disabled') === 'disabled') {
    return false;
  }
  jQuery(element).attr('disabled', 'disabled');
  axios(axiosOpt).then((response) => {
    success(response);
    if (!onlyOnce) {
      jQuery(element).removeAttr('disabled');
    }
  }).catch((_error) => {
    if (error && typeof error === 'function') {
      error(_error);
    }
    jQuery(element).removeAttr('disabled');
  })
}
