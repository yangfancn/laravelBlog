<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\Street;
use App\Models\Village;
use Symfony\Component\HttpFoundation\Response;

class RegionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $code = $request->input('code');
        $column = $request->input('column');
        $level = [
            'province_code' => Province::class,
            'city_code' => City::class,
            'area_code' => Area::class,
            'street_code' => Street::class,
            'village_code' => Village::class
        ];
        $columns = array_keys($level);
        if (($index = array_search($column, $columns, true)) === false || !isset($columns[$index + 1])) {
            return new JsonResponse([
                'message' => 'no result'
            ], Response::HTTP_OK);
        }

        $data = (new $level[$columns[$index + 1]])->where($column, $code)->get(['code as id', 'name as text'])
            ->toArray();
        array_unshift($data, ['id' => '', 'text' => '']);
        return new JsonResponse([
            'column' => $columns[$index + 1],
            'items' => $data
        ]);
    }
}
