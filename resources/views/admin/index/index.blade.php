@extends('admin.layout')

@section('content')
  <section class="content">
    <div class="container-fluid pt-3">
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-6 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><small>今日: </small>{{ $post_count['today_create'] }}</h3>
                  <p><span>总: </span>{{ $post_count['total'] }}</p>
                </div>
                <div class="icon">
                  <i class="fa fa-file-text-o"></i>
                </div>
                <p class="small-box-footer">文章</p>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><small>今日: </small>{{ $user_count['today_create'] }}</h3>
                  <p><span>总: </span>{{ $user_count['total'] }}</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
                <p class="small-box-footer">用户</p>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><small>剩余: </small>{{ $sysInfos['memory']['free'] }} {{ $sysInfos['memory']['unit'] }}</h3>
                  <p><span>总: </span>{{ $sysInfos['memory']['total'] }} {{ $sysInfos['memory']['unit'] }}</p>
                </div>
                <div class="icon">
                  <i class="fa fa-pie-chart"></i>
                </div>
                <p class="small-box-footer">内存</p>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><small>剩余: </small>{{ $sysInfos['disk']['avail'] }}</h3>
                  <p><span>总: </span>{{ $sysInfos['disk']['size'] }}</p>
                </div>
                <div class="icon">
                  <i class="fa fa-pie-chart"></i>
                </div>
                <p class="small-box-footer">硬盘</p>
              </div>
            </div>
          </div>
          <div class="card mb-0 bg-gradient-lightblue">
            <div class="card-header">
              <h3 class="card-title">七日内访问量分布</h3>
            </div>
            <div id="charts-week-visitor-map" style="height: 510px" class="w-100 p-2">

            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card bg-gradient-white">
            <div class="card-header">
              <h3 class="card-title">访问量</h3>
            </div>
            <div id="charts-month-visitor" style="height: 370px" class="w-100 p-2">

            </div>
          </div>
          <div class="card mb-0 bg-white">
            <div class="card-header">
              <h3 class="card-title">Robots</h3>
            </div>
            <div id="charts-month-spider" style="height: 380px" class="w-100 p-2">

            </div>
          </div>
        </div>
        <div class="col-lg-12"></div>
      </div>
      <!-- /.row (main row) -->
    </div>
  </section>
  <script>
    var visitors = {{ Js::from($visitors_distributed) }};
    var month_spiders = {{ Js::from($month_visitors->makeHidden(['page_view', 'unique_view', 'id', 'created_at',
    'updated_at'])) }};
    var month_visitors = {{ Js::from($month_visitors->map(function ($item) {
        return $item->only(['page_view', 'unique_view']);
    })) }};
    var month_dates = {{ Js::from($month_visitors->pluck('created_at')->toArray()) }}
  </script>
  <script src="{{ mix('/admin/js/dashboard.js') }}"></script>
  <script>
    jQuery(document).ready(function () {
      dashboard();
    })
  </script>
@endsection

@section('scripts')

@endsection
