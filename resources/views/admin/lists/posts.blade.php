@extends('admin.layout')

@section('content')
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">文章列表</h3>
              <div class="card-tools">
                <a type="button" class="btn btn-tool open-iframe-modal" href="{{ route('admin.posts.create') }}">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
            <!-- ./card-header -->
            <div class="card-body p-0">
              <div class="search-container my-3 px-3">
                <div class="row justify-content-end">
                  <div class="col-md-3">
                    <form action="{{ route('admin.posts.index') }}" method="GET">
                      <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="标题" value="{{ $search }}">
                        <span class="input-group-append">
                          <button type="submit" class="btn btn-info btn-flat">查找</button>
                        </span>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <table class="table table-hover">
                <tr>
                  <th>ID</th>
                  <th>标题</th>
                  <th>用户</th>
                  <th>发布时间</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
                @foreach($data as $key => $item)
                  <tr data-id="{$item.id}">
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item['created_at'] }}</td>
                    <td data-field="status"><small>{{ $item['status'] ? '正常' : '禁用' }}</small></td>
                    <td>
                      <a href="{{ route('admin.posts.edit', $item['id']) }}" class="btn btn-sm" title="编辑"><i
                          class="fa fa-pencil"></i></a>
                      <a href="javascript:;"
                         data-href="{{ route('admin.posts.status', $item['id']) }}"
                         data-status="{{ $item['status'] }}"
                         class="btn btn-sm status" title="更改状态">
                        <i class="fa {{ $item['status'] ? 'fa-check' : 'fa-times' }} text-info"></i></a>
                      <a href="javascript:;" class="btn btn-sm delete"
                         data-href=" {{ route('admin.posts.destroy', $item['id']) }}" title="删除"><i
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
    $(document).ready(function () {
      list_init();
    })
  </script>
@endsection
