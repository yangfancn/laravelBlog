<div class="card">
  <div class="card-header">{{ $label }}</div>
  <div class="card-body">
    @if($jsonType === \App\Handlers\Form\Enums\JsonType::OBJECT)
      {!! $sections[0] !!}
    @else
      <div class="jsonArray" @if($leastOne)data-least-one @endif data-max-items="{{ $maxItems }}">
        <script type="text/html" id="json-template-{{ $name }}">
          <div class="card jsonItem" data-index="rep_index">
            <div class="card-body">
              {{ $template }}
            </div>
          </div>
        </script>
        @foreach($sections as $index => $section)
          <div class="card jsonItem" data-index="{{ $index }}">
            <div class="card-body">
              {!! $section !!}
              @if(!$leastOne || $index !== 0)
                <button class="btn btn-danger reduce-item" type="button"><i class="fa fa-trash"></i></button>
              @endif
            </div>
          </div>
        @endforeach
        <button class="btn btn-primary add-item" type="button"><i class="fa fa-plus"></i></button>
      </div>
    @endif
  </div>
</div>

