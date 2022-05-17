@extends('admin.layout')

@section('content')
  <script src="{{ mix('/admin/js/form.js') }}"></script>
  <section class="content">
    <div class="container-fluid pt-3">
      <form action="{{ $action }}" method="{{ $method }}" class="form-xhr">
        @csrf
        {!! $html !!}
        <div class="fc-button-group">
          @if($submitButton)
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
          @endif
          @if($resetButton)
            <button type="reset" class="btn btn-warning"><i class="fa fa-undo"></i></button>
          @endif
        </div>
      </form>
    </div>
  </section>
  <script>
    $(document).ready(function () {
      form_init();
    })
  </script>
@endsection
