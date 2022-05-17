<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\VisitorHandler;
use App\Models\Post;
use App\Models\Province;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\View;
use Probe\ProviderFactory;

class IndexController extends BaseController
{

    public function index(): View
    {
        $sysInfos = $this->dashboard();
        $today_visitors = VisitorHandler::todayModel();
        $month_visitors = Visitor::whereBetween('created_at', [new Carbon('-30 days'), Carbon::tomorrow()])->get();
        //访问分布
        $visitors = Redis::lRange(VisitorHandler::$cache_key, 0, -1);
        $visitors_distributed = $_visitors_distributed = [];
        foreach ($visitors as $item) {
            $item = json_decode($item, true);
            $province = $item['city']['subdivisions']['zh'] ?: $item['city']['city']['zh'];
            if (isset($_visitors_distributed[$province])) {
                ++ $_visitors_distributed[$province];
            } else {
                $_visitors_distributed[$province] = 0;
            }
        }
        foreach ($_visitors_distributed as $province => $count) {
            $visitors_distributed[] = ['name' => $province, 'value' => $count];
        }

        $user_count = [
            'total' => User::count(),
            'today_create' => User::whereDate('created_at', 'today')->count()
        ];

        $post_count = [
            'total' => Post::count(),
            'today_create' => Post::whereDate('created_at', 'today')->count()
        ];

        return view('admin.index.index', compact(
            'sysInfos',
            'today_visitors',
            'month_visitors',
            'visitors_distributed',
            'user_count',
            'post_count'
        ));
    }

    protected function dashboard(): array
    {
        $provider = ProviderFactory::create();
        $info = [];
        $info['memory'] = [
            'used' => ceil($provider->getUsedMem() / 1024 / 1024),
            'total' => ceil($provider->getTotalMem()  / 1024 / 1024),
            'unit' => 'M'
        ];
        $info['memory']['free'] = $info['memory']['total'] - $info['memory']['used'];
        $info['OS'] = $provider->getOsRelease();
        $info['cpu_num'] = $provider->getPhysicalCpus();
        $info['cpu_model'] = $provider->getCpuModel();
        $info['server_ip'] = $provider->getServerIP();
        $info['server_version'] = $provider->getServerSoftware();
        $info['php_version'] = $provider->getServerSoftware();
        $info['disk'] = array_values($provider->getDiskUsage())[0];
        return $info;
    }
}
