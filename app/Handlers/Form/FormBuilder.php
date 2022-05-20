<?php

namespace App\Handlers\Form;

use App\Handlers\Form\Enums\JsonType;
use App\Models\Area;
use App\Models\City;
use App\Models\Province;
use App\Models\Street;
use App\Models\Village;
use Illuminate\Support\Facades\View;


class FormBuilder
{
    private string $html = '';
    public array $options = [];

    public function __construct(protected string $action, protected string $method, )
    {
    }

    public function addInput(string $type, string $name, string $label, int|string $value = '', int|string $placeholder =
    '', array $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs);
        $this->options[] = [
            'view' => 'admin_forms.input',
            'args' => compact(
                'label',
                'attrs',
                'name',
                'classes',
                'attrs',
                'value',
                'placeholder',
                'type'
            )
        ];
        return $this;
    }

    public function addRadio(string $name, string $label, array $values, int|string|null $checkedValue = null, array
    $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-check-input']);
        $this->options[] = [
            'view' => 'admin_forms.radio',
            'args' => compact('label',
                'values',
                'attrs',
                'checkedValue',
                'name',
                'classes'
            )
        ];
        return $this;
    }

    public function addCheckbox(string $name, string $label, array $values, array $checkedValues = [], array $attrs =
    []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-check-input']);
        if (!str_ends_with($name, '[]')) {
            $name .= '[]';
        }
        $this->options[] = [
            'view' => 'admin_forms.checkbox',
            'args' => compact('name',
                'label',
                'values',
                'checkedValues',
                'classes',
                'attrs'
            )
        ];
        return $this;
    }

    public function addSelect(string $name, string $label, array $values, mixed $selected = null, string $placeholder =
    '', $attrs = [], bool $isTags = false, string|false $ajax = false, bool $isMultiple = false): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-control', 'bootstrap4-select']);
        if ($isTags) {
            $isMultiple = true;
        }
        if ($isMultiple && !str_ends_with($name, '[]')) {
            $name = $name . '[]';
        }
        $this->options[] = [
            'view' => 'admin_forms.select',
            'args' => compact('name',
                'label',
                'values',
                'selected',
                'placeholder',
                'attrs',
                'isTags',
                'ajax',
                'isMultiple',
                'classes')
        ];
        return $this;
    }

    private function createSimpleSelect(string $name, array $values, mixed $selected = null, string $placeholder = '',
                                        array  $attrs = []): string
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-control']);
        return view('admin_forms.select_simple', compact('name', 'values', 'selected', 'placeholder', 'classes', 'attrs'))
            ->render();
    }

    public function addRegion(string $name, string $label, int $level, array $selected = []): static
    {
        $classes = ['form-control', 'bootstrap4-select'];
        $id = 'region_' . $name;
        $citySelect = $areaSelect = $streetSelect = $villageSelect = null;
        $provinces = Province::pluck('name', 'code')->toArray();
        $provinceSelect = $this->createSimpleSelect($name . '[province_code]', $provinces, $selected['province_code']
            ?? null, '请选择省份', ['data-column' => 'province_code']);
        $cities = $areas = $streets = $villages = [];
        if ($level > 1) {
            if (isset($selected['province_code']) && $selected['province_code']) {
                $cities = City::where('province_code', $selected['province_code'])->pluck('name', 'code')->toArray();
            }
            $citySelect = $this->createSimpleSelect($name . '[city_code]', $cities, $selected['province_code'] ??
                null, '请选择城市', ['data-column' => 'city_code']);
        }
        if ($level > 2) {
            if (isset($selected['city_code']) && $selected['city_code']) {
                $areas = Area::where('city_code', $selected['city_code'])->pluck('name', 'code')->toArray();
            }
            $areaSelect = $this->createSimpleSelect($name . '[area_code]', $areas, $selected['area_code']
                ?? null, '请选择区域', ['data-column' => 'area_code']);
        }
        if ($level > 3) {
            if (isset($selected['area_code']) && $selected['area_code']) {
                $streets = Street::where('area_code', $selected['area_code'])->pluck('name', 'code')->toArray();
            }
            $streetSelect = $this->createSimpleSelect($name . '[street_code]', $streets, $selected['street_code'] ??
                null, '请选择街道', ['data-column' => 'street_code']);
        }
        if ($level > 4) {
            if (isset($selected['street_code']) && $selected['street_code']) {
                $villages = Village::where('street_code', $selected['street_code'])->pluck('name', 'code')->toArray();
            }
            $villageSelect = $this->createSimpleSelect($name . '[village_code]', $villages, $selected['village_code']
                ?? null, '请选择村镇', ['data-column' => 'village_code']);
        }
        $this->options[] = [
            'view' => 'admin_forms.region',
            'args' => compact('name',
                'label',
                'level',
                'selected',
                'provinceSelect',
                'citySelect',
                'areaSelect',
                'streetSelect',
                'villageSelect',
                'id',
                'classes'
            )
        ];
        return $this;
    }

    public function addCkeditor(string $name, string $label, string|null $value = null, string $placeholder = ''):
    static
    {
        $id = 'ckeditor_' . $name;
        $this->options[] = [
            'view' => 'admin_forms.ckeditor',
            'args' => compact('name', 'label', 'placeholder', 'value', 'id')
        ];
        return $this;
    }

    public function addDatetimePicker(string $name, string $label, string|null $value = null, string $format = 'Y-m-d',
                                      bool   $enableTime = false, bool $enableRange = false): static
    {
        $this->options[] = [
            'view' => 'admin_forms.datetime_picker',
            'args' => compact('name',
                'label',
                'value',
                'format',
                'enableTime',
                'enableRange'
            )
        ];
        return $this;
    }

    public function addIconSelect(string $name, string $label, string|null $value = null): static
    {
        $icons = json_decode(file_get_contents(database_path('data') . DIRECTORY_SEPARATOR . 'icons.json'), true);
        $this->options[] = [
            'view' => 'admin_forms.icon_select',
            'args' => compact('icons', 'name', 'label', 'value')
        ];
        return $this;
    }

    public function addTextarea(string $name, string $label, string|null $value = null, string $placeholder = ''):
    static
    {
        $this->options[] = [
            'view' => 'admin_forms.textarea',
            'args' => compact('name', 'value', 'label', 'placeholder')
        ];
        return $this;
    }

    public function addUploadImage(string $name, string $label, string|array $uploaded = [], int $maxFile = 1,
                                   bool   $cropper = false, $aspectRatio = 16 / 9, array $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['dropzone']);
        $id = 'dropzone_image_' . $name;
        if (is_string($uploaded) && $uploaded) {
            $uploaded = [$uploaded];
        }
        $uploaded = array_filter($uploaded);
        if ($maxFile > 1 && !str_ends_with($name, '[]')) {
            $name = $name . '[]';
        }
        $this->options[] = [
            'view' => 'admin_forms.upload_image',
            'args' => compact(
                'name',
                'label',
                'uploaded',
                'maxFile',
                'cropper',
                'aspectRatio',
                'classes',
                'attrs',
                'id'
            )
        ];
        return $this;
    }

    public function addUploadFile(string $name, string $label, string|null $uploaded = null, array $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['dropzone']);
        $id = 'dropzone_file_' . $name;
        $this->options[] = [
            'view' => 'admin_forms.upload_file',
            'args' => compact('name',
                'label',
                'uploaded',
                'classes',
                'attrs',
                'id'
            )
        ];
        return $this;
    }

    public function addJson(FormBuilder $builder, string $name, string $label, ?array $value, JsonType $jsonType =
    JsonType::OBJECT, bool $leastOne = false, int $maxItems = 0): static
    {
        $jsonOptions = $builder->options;
        $sections = [];
        $template = '';
        if ($jsonType === JsonType::OBJECT) {
            $builder->options = $this->modifyJsonAttrs($name, $jsonOptions, $value);
            $sections[] = $builder->create('', false);
        } else {
            $count = count($value);
            for ($i = 0; $i < $count; $i++) {
                $newBuilder = new FormBuilder('', '');
                $newBuilder->options = $this->modifyJsonAttrs($name, $jsonOptions, $value[$i] ?? [], $i);
                $sections[] = $newBuilder->create('', false);
            }
            $newBuilder = new FormBuilder('', '');
            $newBuilder->options = $this->modifyJsonAttrs($name, $jsonOptions, [], 'rep_index');
            $template = $newBuilder->create('', false);
        }
        $this->options[] = [
            'view' => 'admin_forms.json',
            'args' => compact('sections', 'name', 'label', 'jsonType', 'leastOne', 'maxItems', 'template')
        ];
        return $this;
    }

    private function modifyJsonAttrs(string $name, array $option, array $value = [], int|string|null $index = null):
    array
    {
        return array_map(function ($item) use ($name, $value, $index) {
            $field = $item['args']['name'];
            if (str_ends_with($item['args']['name'], '[]')) {
                $item['args']['name'] = $name . ($index !== null ? "[$index]" : '') . '[' . rtrim($item['args']['name'], '[]') . '][]';
            } else {
                $item['args']['name'] = $name . ($index !== null ? "[$index]" : '') . "[{$item['args']['name']}]";
            }
            if ($value || $index !== null) {
                switch ($item['view']) {
                    case 'admin_forms.input':
                    case 'admin_forms.datetime_picker':
                    case 'admin_forms.icon_select':
                    case 'admin_forms.textarea':
                        $item['args']['value'] = $value[$field] ?? '';
                        break;
                    case 'admin_forms.radio':
                        $item['args']['checkedValue'] = $value[$field] ?? null;
                        break;
                    case 'admin_forms.checkbox':
                        $item['args']['checkedValues'] = $value[$field] ?? [];
                        break;
                    case 'admin_forms.select':
                        $item['args']['selected'] = $value[$field] ?? null;
                        break;
                    case 'admin_forms.region':
                        $item['args']['selected'] = $value[$field] ?? [];
                        $item['args']['id'] = $this->generateValidId('region_' . $item['args']['name']);
                        break;
                    case 'admin_forms.upload_image':
                        $item['args']['uploaded'] = $value[$field] ?? [];
                        $item['args']['id'] = $this->generateValidId('dropzone_image_' . $item['args']['name']);
                        break;
                    case 'admin_forms.upload_file':
                        $item['args']['uploaded'] = $value[$field] ?? null;
                        $item['args']['id'] = $this->generateValidId('dropzone_file_' . $item['args']['name']);
                        break;
                    case 'admin_forms.ckeditor':
                        $item['args']['value'] = $value[$field] ?? '';
                        $item['args']['id'] = $this->generateValidId('ckeditor_' . $item['args']['name']);
                        break;
                }
            }
            return $item;
        }, $option);
    }

    private function explodeClassFromAttrs(array $attrs, array $classes = ['form-control']): array
    {
        if (isset($attrs['class'])) {
            if (!is_array($attrs['class'])) {
                $attrs['class'] = explode(' ', $attrs['class']);
            }
            $classes = array_filter(array_merge($classes, $attrs['class']));
            unset($attrs['class']);
        }
        $str = '';
        foreach ($attrs as $attr => $value) {
            $str .= ($attr . '="' . $value . '" ');
        }
        return [$classes, $str];
    }

    public function create(string $title, bool $full = true):
    \Illuminate\View\View | string
    {
        foreach ($this->options as $item) {
            $this->html .= view($item['view'], $item['args'])->render();
        }
        if (!$full) {
            return $this->html;
        }
        return view('admin_forms.form', [
            'action' => $this->action,
            'method' => $this->method,
            'html' => $this->html,
            'title' => $title
        ]);
    }

    private function generateValidId(string $id): string
    {
        return str_replace(['[', ']'], '_', $id);
    }
}
