<div class="form-group">
  <label>{{ $label }}</label>
  <div class="row">
    @foreach($values as $value => $name)
      <div class="col-auto">
        <div class="form-check">
          <input @if($checkedValue === $value)checked@endif type="radio" value="{{ $value }}" class="form-check-input">
          <label class="form-check-label">{{ $name }}</label>
        </div>
      </div>
    @endforeach
  </div>
</div>
