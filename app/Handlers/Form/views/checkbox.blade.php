<div class="form-group">
  <label>{{ $label }}</label>
  <div class="row">
    @foreach($values as $value => $name)
      <div class="col-auto">
        <div class="form-check">
          <input type="checkbox" value="{{ $value }}" class="form-check-input" @if($checkedValue === $value)checked@endif>
          <label class="form-check-label">{{ $name }}</label>
        </div>
      </div>
    @endforeach
  </div>
</div>
