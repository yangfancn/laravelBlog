<div class="form-group region-select" id="{{ $id }}">
  <label>{{ $label }}</label>
  <div class="row">
    <div class="col">
      {!! $provinceSelect !!}
    </div>
    @if($level > 1)
      <div class="col">
        {!! $citySelect !!}
      </div>
    @endif
    @if($level > 2)
      <div class="col">
        {!! $areaSelect !!}
      </div>
    @endif
    @if($level > 3)
      <div class="col">
        {!! $streetSelect !!}
      </div>
    @endif
    @if($level > 4)
      <div class="col">
        {!! $villageSelect !!}
      </div>
    @endif
  </div>
</div>
