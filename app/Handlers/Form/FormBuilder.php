<?php

namespace App\Handlers\Form;

use App\Models\Area;
use App\Models\City;
use App\Models\Province;
use App\Models\Street;
use App\Models\Village;
use Illuminate\Support\Facades\View;

class FormBuilder
{
    private string $html = '';
    private int $region_used_times = 0;
    private int $ckeditor_used_times = 0;
    private int $upload_used_times = 0;

    public function __construct(protected string $action, protected string $method)
    {
    }

    public function addInput(string $type, string $name, string $label, int|string $value = '', int|string $placeholder = '', array $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs);
        $this->html .= view('admin_forms.input', compact(
            'label',
            'attrs',
            'name',
            'classes',
            'attrs',
            'value',
            'placeholder',
            'type'
        ))->render();
        return $this;
    }

    public function addRadio(string $name, string $label, array $values, int|string $checkedValue = null, array $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-check-input']);
        $this->html .= view('admin_forms.radio', compact('label',
            'values',
            'attrs',
            'checkedValue',
            'name',
            'classes'
        ))->render();
        return $this;
    }

    public function addCheckbox(string $name, string $label, array $values, array $checkedValues = [], array $attrs =
    []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-check-input']);
        $this->html .= view('admin_forms.checkbox', compact('name',
            'label',
            'values',
            'checkedValues',
            'classes',
            'attrs'
        ))->render();
        return $this;
    }

    public function addSelect(string $name, string $label, array $values, mixed $selected = null, string $placeholder = '',
                                     $attrs = [], bool $isTags = false, bool $isAjax = false, bool $isMultiple = false): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['form-control', 'bootstrap4-select']);
        $this->html .= view('admin_forms.select', compact('name',
            'label',
            'values',
            'selected',
            'placeholder',
            'attrs',
            'isTags',
            'isAjax',
            'isMultiple',
            'classes'
        ))->render();
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
        $id = 'region_' . $this->region_used_times;
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
        $this->html .= view('admin_forms.region', compact('name',
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
        ))->render();
        ++$this->region_used_times;
        return $this;
    }

    public function addCkeditor(string $name, string $label, string $value = '', string $placeholder = ''): static
    {
        $id = 'ckeditor_' . $name . '_' . $this->ckeditor_used_times;
        $this->html .= view('admin_forms.ckeditor', compact('name', 'label', 'placeholder', 'value', 'id'))
            ->render();
        ++$this->ckeditor_used_times;
        return $this;
    }

    public function addDatetimePicker(string $name, string $label, string $value, string $format = 'Y-m-d',
                                      bool $enableTime = false, bool $enableRange = false): static
    {
        $this->html .= \view('admin_forms.datetime_picker', compact('name',
            'label',
        'value',
        'format',
        'enableTime',
        'enableRange'
        ))->render();
        return $this;
    }

    public function addIconSelect(string $name, string $label, string $value = ''): static
    {
        $icons = json_decode(file_get_contents(database_path('data') . DIRECTORY_SEPARATOR . 'icons.json'), true);
        $this->html .= view('admin_forms.icon_select', compact('icons', 'name', 'label', 'value'))->render();
        return $this;
    }

    public function addTextarea(string $name, string $label, string $value = '', string $placeholder = ''): static
    {
        $this->html .= view('admin_forms.textarea', compact('name', 'value', 'label', 'placeholder'))->render();
        return $this;
    }

    public function addUploadImage(string $name, string $label, string|array $uploaded, int $maxFile = 1, bool
    $cropper = false, $aspectRatio = 16 / 9, array $attrs = []): static
    {
        [$classes, $attrs] = $this->explodeClassFromAttrs($attrs, ['dropzone']);
        $id = 'dropzone_' . $name . '_' . $this->upload_used_times;
        $this->html .= view('admin_forms/upload_image', compact(
            'name',
            'label',
            'uploaded',
            'maxFile',
            'cropper',
            'aspectRatio',
            'classes',
            'attrs',
            'id'
        ))->render();
        ++$this->upload_used_times;
        return $this;
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

    public function create(bool $submitButton = true, bool $resetButton = true): \Illuminate\View\View
    {
        return view('admin_forms.form', [
            'action' => $this->action,
            'method' => $this->method,
            'html' => $this->html,
            'submitButton' => $submitButton,
            'resetButton' => $resetButton
        ]);
    }
}
