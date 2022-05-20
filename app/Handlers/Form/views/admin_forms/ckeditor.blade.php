<div class="form-group">
  <label>{{ $label }}</label>
  <textarea name="{{ $name }}" id="{{ $id }}" class="ckeditor-textarea">
    {{ $value ?: $placeholder }}</textarea>
  <script>
    $(document).ready(function () {
      regisEditor('{{ $id }}');
    })
  </script>
</div>
