<div class="form-group">
  <label>{{ $label }}</label>
  <select
    name="{{ $name }}"
    @class($classes)
    @if($isTags)data-tags="true" data-allow-clear="true" @endif
    @if($isAjax)data-ajax-url="{{ route('admin.tags.list') }}" @endif
    data-placeholder="{{ $placeholder }}"
    @if($isMultiple)multiple="multiple"@endif
  >
    <option></option>
    @foreach($values as $value => $_name)
      <option
        value="{{ $value }}"
        @if($value === $selected || (is_array($selected) && in_array($value, $selected)))selected @endif
      >{{ $_name }}</option>
    @endforeach
  </select>
</div>
