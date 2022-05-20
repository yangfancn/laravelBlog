<div class="form-group">
  <label for="{{ $name }}">{{ $label }}</label>
  <select name="{{ $name }}" class="form-control select-icon">
    <option></option>
    @foreach($icons as $icon)
      <option value="{{ $icon }}" data-icon="{{ $icon }}" @if($value === $icon)selected @endif>
        {{ $icon }}
      </option>
    @endforeach
  </select>
</div>
