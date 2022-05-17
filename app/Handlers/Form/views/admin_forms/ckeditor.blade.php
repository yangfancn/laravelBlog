<div class="form-group">
  <label>{{ $label }}</label>
  <textarea name="{{ $name }}" id="{{ $id }}" class="ckeditor-textarea">
    {{ $value ?: $placeholder }}</textarea>
  <script>
    regisEditor('{{ $id }}');
  </script>
</div>
