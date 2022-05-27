<div class="form-group">
  <label>{{ $label }}</label>
  <div class="row">
    @foreach($values as $value => $_name)
      <div class="col-auto">
        <div class="form-check">
          <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @class($classes)
          @if(in_array($value, $disabled))disabled @endif
                 @if(in_array($value, $checkedValues))checked @endif>
          <label class="form-check-label">{{ $_name }}</label>
        </div>
      </div>
    @endforeach
  </div>
</div>
