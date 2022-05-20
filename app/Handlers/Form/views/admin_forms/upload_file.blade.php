<div class="form-group">
  <label for="{{ $name }}">{{ $label }}</label>
  <div @class($classes) id="{{ $id }}" data-input="{{ $name }}" {!! $attrs !!}>
    <div class="am-text-success dz-message">
      将文件拖拽到此处<br>或点此打开文件管理器选择文件
    </div>
    @if($uploaded)
      <input type="text" name="{{ $name }}" class="input-none" value="{{ $uploaded }}">
    @endif
  </div>
  <script>
    $(document).ready(function () {
      createUploadFile('{{ $id }}', '{{ $uploaded }}');
    })
  </script>
</div>
