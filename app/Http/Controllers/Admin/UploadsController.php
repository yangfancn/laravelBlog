<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\UploadHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UploadsController extends BaseController
{
    public function image(Request $request): JsonResponse
    {
        $result = [
            'errno' => 0,
            'msg' => '',
            'data' => [
                [
                    'url' => null,
                    'alt' => null,
                    'href' => null
                ]
            ]
        ];
        try {
            $path = new UploadHandler($request->file('file'), 'images', 'file|image|max:10240',
                true, [
                    'file.file' => '文件完整性验证失败',
                    'file.image' => '图片格式错误',
                    'file.max' => '最大限制为10M'
                ]);
        } catch (\RuntimeException $exception) {
            $result['errno'] = 1;
            $result['msg'] = $exception->getMessage();
            return new JsonResponse($result);
        }
        $result['data'][0]['url'] = (string)$path;
        return new JsonResponse($result);
    }


    public function file(Request $request): JsonResponse
    {
        $result = [
            'errno' => 0,
            'msg' => '',
            'data' => [
                'url' => null,
                'alt' => null,
                'href' => null
            ]
        ];
        try {
            $path = new UploadHandler($request->file('file'), 'images', 'file|mimes:doc,docx,txt|max:20480',
                true, [
                    'file.file' => '文件完整性验证失败',
                    'file.mimes' => '文件格式错误',
                    'file.max' => '最大限制为20M'
                ]);
        } catch (\RuntimeException $exception) {
            $result['errno'] = 1;
            $result['msg'] = $exception->getMessage();
            return new JsonResponse($result);
        }
        $result['data']['url'] = (string)$path;
        return new JsonResponse($result);
    }

}
