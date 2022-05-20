@extends('admin.layout')

@section('content')
  <section class="content">
    <div class="container-fluid pt-3">
      <div class="card card-info">
        <div class="card-header">
          <div class="card-title">{{ $title }}</div>
        </div>
        <div class="card-body">
          <form action="{{ $action }}" method="{{ $method }}" class="form-xhr" onsubmit="return false">
            @csrf
            {!! $html !!}
            <div class="fc-button-group">
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
              <button type="reset" class="btn btn-warning"><i class="fa fa-undo"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <script>
    $(document).ready(function () {
      form_init();
    })
  </script>
@endsection
