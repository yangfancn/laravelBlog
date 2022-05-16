<div class="form-group">
  <label>{{ $label }}</label>
  <select
    name="{{ $name }}"
    class="form-control bootstrap4-select"
    @if($tags)data-tags="true" data-allow-clear="true"@endif
    @if($ajax)data-ajax-url="{{ route('admin.tags.list') }}"@endif
    data-placeholder="{{ $placeholder }}"
    @if($multiple)multiple="multiple"@endif
  >
    <option></option>
    @foreach($values as $value => $_name)
      <option
        value="{{ $value }}"
        @if($value === $selectedValue || (is_array($selectedValue) && in_array($value, $selectedValue)))selected@endif
      >
        {{ $_name }}</option>
    @endforeach
  </select>
</div>
