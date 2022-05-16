<div class="form-group">
  <label>{{ $label }}</label>
  <textarea name="{{ $name }}" id="{{ $id }}" class="ckeditor-textarea">{$this->value}</textarea>
  <script>
    regisEditor({{ $id }});
  </script>
</div>
