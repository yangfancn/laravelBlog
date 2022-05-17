<div class="form-group">
  <label for="{{ $name }}">{{ $label }}</label>
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
    </div>
    <input name="{{ $name }}" value="{{ $value }}" class="flatpickr form-control" @if($enableRange)data-mode="range" @endif
           data-enable-time="{{ $enableTime }}" data-date-format="{{ $format }}" type="text">
  </div>
</div>
