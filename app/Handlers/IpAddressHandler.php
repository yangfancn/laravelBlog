<?php
namespace App\Handlers;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use MaxMind\Db\Reader\InvalidDatabaseException;

class IpAddressHandler
{
    protected Reader $reader;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        try {
            $this->reader = new Reader(Storage::disk('local')->path('geoip/mmdb/GeoLite2-City.mmdb'));
        } catch (InvalidDatabaseException) {
            throw new \Exception('GeoIp database file is invalidate or not exist');
        }
    }

    #[ArrayShape(['country' => "null[]", 'city' => "null[]", 'subdivisions' => "null[]"])]
    public function getCity(string $ip): array
    {
        $result = [
            'country' => [
                'zh' => null,
                'en' => null,
            ],
            'city' => [
                'zh' => null,
                'en' => null,
            ],
            'subdivisions' => [
                'zh' => null,
                'en' => null,
            ]
        ];

        try {
            $record = $this->reader->city($ip);
        } catch (AddressNotFoundException | InvalidDatabaseException $exception) {

        }

        if (!isset($record->country->names) || $ip === '127.0.0.1') {
            $result['country']['zh'] = '中国';
            $result['country']['en'] = 'China';
            $result['city']['zh'] = '北京市';
            $result['city']['en'] = 'Beijing';
            $result['subdivisions']['zh'] = '北京市';
            $result['subdivisions']['en'] = 'Beijing';
            return $result;
        }
        if ($record->country->names['zh-CN'] === '香港') {
            $result['country']['zh'] = '中国';
            $result['country']['en'] = 'China';
            $result['city']['zh'] = '';
            $result['city']['en'] = '';
            $result['subdivisions']['zh'] = '香港特别行政区';
            $result['subdivisions']['en'] = 'Hong Kong';
        } else if ($record->country->names['zh-CN'] === '中华民国') {
            $result['country']['zh'] = '中国';
            $result['country']['en'] = 'China';
            $result['city']['zh'] = '';
            $result['city']['en'] = '';
            $result['subdivisions']['zh'] = '台湾省';
            $result['subdivisions']['en'] = 'Taiwan';
        } else {
            $result['country']['zh'] = $record->country->names['zh-CN'];
            $result['country']['en'] = $record->country->name;
            $result['city']['zh'] = isset($record->city->names['zh-CN']) ? $this->repair($record->city->names['zh-CN']) : '';
            $result['city']['en'] = $record->city->name ?? '';
            $result['subdivisions']['zh'] = isset($record->subdivisions[0]->names['zh-CN']) ?
                $this->repair($record->subdivisions[0]->names['zh-CN']) : '';
            $result['subdivisions']['en'] = $record->subdivisions[0]->name ?? '';
        }
        return $result;
    }

    public function repair(string $name): string
    {
        $repair = [
            '新疆' => '新疆维吾尔自治区',
            '甘肃' => '甘肃省',
            '云南' => '云南省',
            '湖南' => '湖南省',
            '陕西' => '陕西省',
            '广东' => '广东省',
            '吉林' => '吉林省',
            '贵州' => '贵州省',
            '江西' => '江西省',
            '河南' => '河南省',
            '辽宁' => '辽宁省',
            '山西' => '山西省',
            '安徽' => '安徽省',
            '重庆' => '重庆市',
            '海南' => '海南省',
            '北京' => '北京市',
            '天津' => '天津市',
            '上海' => '上海市',
            '澳门' => '澳门特别行政区'
        ];
        return $repair[$name] ?? $name;
    }
}
