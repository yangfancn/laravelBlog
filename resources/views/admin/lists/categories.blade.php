@extends('admin.layout')

@section('content')
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">导航列表</h3>
              <div class="card-tools">
                <a type="button" class="btn btn-tool open-iframe-modal" href="{{ route('admin.categories.create') }}">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
            <!-- ./card-header -->
            <div class="card-body p-0">
              <table class="table table-hover">
                <tr>
                  <th style="width: 100px">排序</th>
                  <th>ID</th>
                  <th>名称</th>
                  <th>目录</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
                @foreach($data as $key => $item)
                  <tr data-id="{$item.id}">
                    <td>
                      <input type="number" value="{{ $item['rank'] }}" class="form-control list-rank">
                    </td>
                    <td>{{ $item['id'] }}</td>
                    <td>
                      {!! $item['level_name'] !!}
                      @if($item['type'] === 0)
                        <span class="badge badge-danger">单页面</span>
                      @endif
                      @if($item['show'] === 0)
                        <span class="badge badge-danger">隐</span>
                      @endif
                    </td>
                    <td>{{ $item['directory'] }}</td>
                    <td data-field="status"><small>{{ $item['status'] ? '正常' : '禁用' }}</small></td>
                    <td>
                      <a href="{{ route('admin.categories.edit', $item['id']) }}" class="btn btn-sm" title="编辑"><i
                          class="fa fa-pencil"></i></a>
                      <a href="javascript:;"
                         data-href="{{ route('admin.categories.status', $item['id']) }}"
                         data-status="{{ $item['status'] }}"
                         class="btn btn-sm status" title="更改状态">
                        <i class="fa {{ $item['status'] ? 'fa-check' : 'fa-times' }} text-info"></i></a>
                      <a href="javascript:;" class="btn btn-sm delete"
                         data-href=" {{ route('admin.categories.destroy', $item['id']) }}" title="删除"><i
                          class="fa fa-trash text-danger"></i></a>
                    </td>
                  </tr>
                @endforeach
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <script>
    var rankUrl = '{{ route('admin.categories.rank') }}';

    $(document).ready(function () {
      list_init();
    })
  </script>
@endsection
