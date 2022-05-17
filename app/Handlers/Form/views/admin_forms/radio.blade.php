<div class="form-group">
  <label>{{ $label }}</label>
  <div class="row">
    @foreach($values as $value => $_name)
      <div class="col-auto">
        <div class="form-check">
          <input name="{{ $name }}" @if($checkedValue === $value)checked @endif type="radio" value="{{ $value }}"
                 @class($classes) {!! $attrs !!}>
          <label class="form-check-label">{{ $_name }}</label>
        </div>
      </div>
    @endforeach
  </div>
</div>
