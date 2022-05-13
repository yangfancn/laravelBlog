<?php

namespace App\Handlers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Agent\Agent;

class VisitorHandler
{
    static public string $cache_key = 'visitors';
    static protected string $today_uv_ip_cache_key;
    static protected string $ip;
    static protected array|null $city;
    static protected Request $request;

    static public array $robot_agent_matches = [
        'Baiduspider' => 'baidu_spider',
        'Googlebot' => 'google_spider',
        '360Spider' => '360_spider',
        'Bingbot' => 'bing_spider',
        'Sogou web' => 'sougo_spider',
        'Sosospider' => 'soso_spider',
        'Bytespider' => 'byte_spider'
    ];

    static private ?VisitorHandler $instance = null;

    private function __clone()
    {
    }

    public static function getInstance(Request $request): static
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
            self::$ip = $request->getClientIp();
            self::$city = (new IpAddressHandler())->getCity(self::$ip);
            self::$request = $request;
            self::$today_uv_ip_cache_key = 'pv_ips' . (new \DateTime())->format('Ymd');
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function record(): Visitor
    {
        $this->clearExpiredCache();
        $model = self::todayModel();
        $agent = new Agent;
        $agent->setUserAgent(self::$request->header('user-agent'));
        if ($agent->isRobot()) {
            $robot = $agent->robot();
            $spider = static::$robot_agent_matches[$robot] ?? 'other_spider';
            $model->$spider++;
        } else {
            if (!$this->ipHasVisitedToday()) {
                $model->unique_view++;
            }
            $model->page_view++;
            $data = [
                'ip' => self::$ip,
                'city' => self::$city,
                'url' => self::$request->url(),
                'created_at' => time()
            ];
            Redis::rPush(self::$cache_key, [json_encode($data)]);
        }
        $model->save();
        return $model;
    }

    /**
     * 清理过期缓存，有效期 7天
     */
    protected function clearExpiredCache(): void
    {
        $time = time();
        $add_expired = 7 * 24 * 60 * 60;
        while ($item = Redis::lPop(self::$cache_key)) {
            $data = json_decode($item, true);
            if ($time < ($data['created_at'] + $add_expired)) {
                Redis::lPush(self::$cache_key, [$item]);
                break;
            }
        }
    }

    /**
     * 指定 IP 当日内是否有访问过
     * @return bool
     */
    protected function ipHasVisitedToday(): bool
    {
        if (!Redis::sismember(self::$today_uv_ip_cache_key, self::$ip)) {
            Redis::sadd(self::$today_uv_ip_cache_key, [self::$ip]);
            if (Redis::scard(self::$today_uv_ip_cache_key) === 1) {
                Redis::expireat(self::$today_uv_ip_cache_key, strtotime('tomorrow'));
            }
            return false;
        }
        return true;
    }

    public static function todayModel(): Visitor
    {
        return Visitor::whereDate('created_at', Carbon::today())->firstOr(function () {
            return Visitor::create([
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
        });
    }

    /**
     * 指定时间至今访问最多的用户
     * @param int $start_time
     * @param int $length
     * @return array
     */
    protected function topVisitors(int $start_time, int $length = 10): array
    {
        $start = -1;
        $data = [];
        while ($item = Redis::lindex(self::$cache_key, $start)) {
            $datum = json_decode($item, true);
            $data[$datum['ip']][] = $datum;
            if ($data['created_at'] < $start_time) {
                break;
            }
            --$start;
        }
        $column = array_map(static function ($item) {
            return count($item);
        }, $data);
        rsort($column);
        array_multisort($column, $data);
        return array_slice($data, 0, $length);
    }
}
