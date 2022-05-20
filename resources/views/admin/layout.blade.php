<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="x-csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="x-pjax-version" content="{{ mix('/admin/js/form.js') }}">
  <title>Manager</title>
  <link rel="stylesheet" href="{{ mix('admin/css/app.css') }}">
  @yield('styles')
  <script src="{{ mix('admin/js/manifest.js') }}"></script>
  <script src="{{ mix('admin/js/vendor.js') }}"></script>
  <script src="{{ mix('admin/js/app.js') }}"></script>
  <script src="{{ mix('/admin/js/list.js') }}"></script>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
  @if(class_basename(request()->route()->getAction('controller')) === 'IndexController@index')
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake"
           src="{{ asset('/admin/pictures/AdminLTELogo.png') }}"
           alt="AdminLTELogo"
           height="60"
           width="60">
    </div>
  @endif
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.index') }}" data-pjax class="nav-link home">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.index') }}">
          <form action="{{ route('admin.logout') }}" method="POST">
            @method('DELETE')
            @csrf
            <button class="btn-no-style" type="submit" title="sign out">
              <i class="fa fa-arrow-left"></i>
            </button>
          </form>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" onclick="window.location.reload()" role="button" title="sign out">
          <i class="fa fa-refresh"></i>
        </a>
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4 ">
    <!-- Brand Logo -->
    <a href="{{ route('admin.index') }}" data-pjax class="brand-link home">
      <img src="{{ asset('/admin/pictures/AdminLTELogo.png') }}" alt=""
           class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>Manager</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ auth('admin')->user()->photo }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block text-uppercase">{{ auth('admin')->user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!--      <div class="form-inline">-->
      <!--        <div class="input-group" data-widget="sidebar-search">-->
      <!--          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">-->
      <!--          <div class="input-group-append">-->
      <!--            <button class="btn btn-sidebar">-->
      <!--              <i class="fa fa-search fa-fw"></i>-->
      <!--            </button>-->
      <!--          </div>-->
      <!--        </div>-->
      <!--      </div>-->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent"
            data-widget="treeview"
            role="menu"
            data-accordion="false">
          @foreach($menu as $channel)
            @if($channel['show'])
              @if(isset($channel['children']) && in_array(1, array_column($channel['children'], 'show')))
                <li @class(['nav-item', 'menu-is-opening menu-open' => $activeMenuId === $channel['id']])>
                  <a href="#" data-pjax class="nav-link">
                    <i class="nav-icon {{ $channel['icon'] }}"></i>
                    <p>
                      {{ $channel['description'] }}
                      <i class="right fa fa-angle-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    @foreach($channel['children'] as $child)
                      @if($child['show'])
                        <li class="nav-item">
                          <a href="{{ route($child['name']) }}" data-pjax
                            @class(['nav-link', 'active' => $activeSubMenuId === $child['id']])>
                            <i class="{{ $child['icon'] ?: 'fa-regular fa-circle' }}
                                                        nav-icon"></i>
                            <p>{{ $child['description'] }}</p>
                          </a>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                </li>
              @else
                <li class="nav-item">
                  <a href="{{ route($channel['name']) }}" class="nav-link">
                    <i class="nav-icon {{ $channel['icon'] }}"></i>
                    <p>
                      {{ $channel['description'] }}
                    </p>
                  </a>
                </li>
              @endif
            @endif
          @endforeach
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- content -->
  <div id="pjax-container">
    <div class="content-wrapper">
      <div class="content">
        @yield('content')
      </div>
    </div>
  </div>
</div>
<div class="modal" data-modal="cropper">
  <div class="cropper">
    <div class="cropper-container">
      <div style="max-height: 500px">
        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">
      </div>
    </div>
    <div class="buttons mt-3">
      <div class="btn-group">
        <button type="button" class="btn btn-info rotate-left">
          <i class="fa fa-rotate-left"></i>
        </button>
        <button type="button" class="btn btn-info rotate-right">
          <i class="fa fa-rotate-right"></i>
        </button>
        <button type="button" class="btn btn-primary check">
          <i class="fa fa-check"></i>
        </button>
      </div>
    </div>
  </div>
</div>
<script src="{{ mix('/admin/js/form.js') }}"></script>
<script src="{{ mix('/admin/js/dashboard.js') }}"></script>
@yield('scripts')
</body>
</html>
