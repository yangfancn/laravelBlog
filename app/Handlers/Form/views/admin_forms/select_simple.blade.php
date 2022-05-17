<select name="{{ $name }}" data-placeholder="{{ $placeholder }}" @class($classes) {!! $attrs !!}>
  <option></option>
  @foreach($values as $value => $label)
    <option value="{{ $value }}" @if($selected === $value)selected @endif>{{ $label }}</option>
  @endforeach
</select>
