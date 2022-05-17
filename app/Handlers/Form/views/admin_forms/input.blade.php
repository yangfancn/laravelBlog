<div class="form-group">
  <label class="col-form-label" for="{{ $name }}">{{ $label }}</label>
  <input
    type="{{ $type }}"
    value="{{ $value }}"
    name="{{ $name }}"
    @class($classes)
    placeholder="{{ $placeholder }}"
    {!! $attrs !!}>
</div>
