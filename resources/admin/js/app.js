import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;

import 'admin-lte/dist/js/adminlte.min'
import 'admin-lte/plugins/select2/js/select2.full.min';
import toastr from 'admin-lte/plugins/toastr/toastr.min';
import 'admin-lte/plugins/dropzone/dropzone';
import Swal from 'admin-lte/plugins/sweetalert2/sweetalert2.min'
import axios from "axios";
import qs from "qs";

require('jquery-pjax');
import nProgress from "nprogress/nprogress";

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast
window.Swal = Swal

const httpStatusCode = {
  400: {
    message: '请求错误'
  },
  401: {
    message: '未登录',
    callback: function () {

    }
  },
  403: {
    message: '访问被拒绝'
  },
  404: {
    message: '未找到该资源'
  },
  405: {
    message: '未经允许的请求方法'
  },
  408: {
    message: '请求超时'
  },
  429: {
    message: '请求次数过多,请稍后再试'
  },
  500: {
    message: '服务器错误',
  },
  501: {
    message: '网络未实现'
  },
  502: {
    message: '网络错误'
  },
  503: {
    message: '服务不可用'
  },
  504: {
    message: '网络请求超时'
  },
  505: {
    message: 'HTTP版本不支持的请求'
  }
}
//axios 默认错误提示
axios.interceptors.response.use(response => {
  shiftPool(response)
  return response
}, error => {
  let status = error.response.status
  let _message
  if (error.response.data.message) {
    _message = error.response.data.message
  } else if (status in httpStatusCode) {
    _message = httpStatusCode[status].message
    if (httpStatusCode[status].hasOwnProperty('callback')) {
      httpStatusCode[status].callback(error.response.data)
    }
  } else {
    _message = '未知错误'
  }
  Toast.fire({
    icon: 'error',
    title: _message
  })
  return Promise.reject(error)
})
axios.defaults.timeout = 20000
// axios 取消重复请求
const axiosPool = new Map()
const appendPool = (config) => {
  const url = [
    config.method,
    config.url,
    qs.stringify(config.params),
    qs.stringify(config.data)
  ].join('&')
  config.cancelToken = config.cancelToken || new axios.CancelToken(cancel => {
    if (!axiosPool.has(url)) {
      axiosPool.set(url, cancel)
    }
  })
}
axios.interceptors.request.use(config => {
  shiftPool(config)
  appendPool(config)
  return config
}, error => {
  return Promise.reject(error)
})

const shiftPool = (config) => {
  const url = [
    config.method,
    config.url,
    qs.stringify(config.params),
    qs.stringify(config.data)
  ].join('&')
  if (axiosPool.has(url)) {
    const cancel = axiosPool.get(url)
    cancel(url)
    axiosPool.delete(url)
  }
}

function msg(msg, type = 'info', timeout = 2200, position = 'toast-top-center', title = '') {
  const option = {
    positionClass: position,
    timeOut: timeout,
    closeBtn: true,
    progressBar: true,
  };
  switch (type) {
    case 'success':
      toastr.success(msg, title, option);
      break;
    case 'warning':
      toastr.warning(msg, title, option);
      break;
    case 'error':
      toastr.error(msg, title, option);
      break;
    default:
      toastr.info(msg, title, option);
      break;
  }
}

window.msg = msg;
window.axios = axios;


//pjax
jQuery(document).pjax('a', '#pjax-container', {
  maxCacheLength: false,
  timeout: 10000
}).on('pjax:start', () => {
  nProgress.start();
}).on('pjax:end', () => {
  nProgress.done();
})

$('.nav-pills.nav-sidebar li a.nav-link:not([href="#"]), a.home').click(function () {
    $('.nav-pills.nav-sidebar li a.nav-link').removeClass('active');
    if ($(this).parents('.nav-pills.nav-sidebar').length) {
        $(this).addClass('active');
    }
})
