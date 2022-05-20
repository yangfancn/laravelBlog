<?php
namespace App\Handlers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryHandler
{
    protected array $tree = [];
    public array $recursionList = [];
    protected array $temp = [];
    protected $recursion = false;

    public function __construct(public Collection $channels)
    {
    }

    public function getChannels(int $pid = 0, bool $list = false): array
    {
        //temp key
        $key = $pid . '_' . ($list ? 'list' : 'tree');
        if (isset($this->temp[$key])) {
            return $this->temp[$key];
        }
        if ($this->recursion) {
            $tree = $this->recursion($this->channels->sortBy('pid'), $pid);
            $result = $list ? $this->recursionList : $tree;
        } else {
            $wholeTree = $this->quote();
            if ($list) {
                $result = $this->quoteList($pid === 0 ? $wholeTree : $this->findPartTree($wholeTree, $pid));
            } else {
                $result = $pid === 0 ? $wholeTree : $this->findPartTree($wholeTree, $pid);
            }
        }

        $this->temp[$key] = $result;
        return $result;
    }

    ################### 递归 #####################

    protected function recursion($channels, int $pid, int $depth = 1): array
    {
        $tree = [];
        foreach ($channels as $key => $channel) {
            if (!in_array($channel['pid'], $this->channels->pluck('id')->all(), true)) {
                $channel['pid'] = 0;
            }
            if ($channel['pid'] === $pid) {
                $channel['depth'] = $depth;
                $channel['max_depth'] = 1;
                $this->recursionList[$channel['id']] = $tree[$channel['id']] = $channel;
                unset($channels[$key]);
                $tree[$channel['id']]['children'] = self::recursion($channels, $channel['id'], $depth + 1);
                if ($tree[$channel['id']]['children']) {
                    $tree[$channel['id']]['max_depth'] = max(array_column($tree[$channel['id']]['children'], 'max_depth')) + 1;
                }
            }
            $tree = $this->sortArrayByMultiFields($tree, 'rank', SORT_ASC, 'id', SORT_ASC);
        }
        return $tree;
    }

    ################### 引用传值 #####################

    public function quote(): array
    {
        $items = [];
        foreach ($this->channels as $channel) {
            $items[$channel['id']] = $channel;
        }
        $tree = [];
        foreach ($items as $key => &$item) {
            if (isset($items[$item['pid']])) {
                $children = $items[$item['pid']]['children'] ?? [];
                $children[$item['id']] = &$item;
                $items[$item['pid']]['children'] = $children;
            } else {
                $tree[$item['id']] = &$items[$key];
            }
        }
        return $tree;
    }

    public function findPartTree(array $wholeTree, int $pid): array
    {
        static $tree = [];
        foreach ($wholeTree as $part) {
            if ($part['pid'] === $pid) {
                $tree[$part['id']] = $part;
            } else if (isset($part['children']) && $part['children']) {
                $this->findPartTree($part['children'], $pid);
            }
        }
        return $tree;
    }

    public function quoteList(array $tree, int $depth = 1, int $filter = null): array
    {
        static $data = [];
        foreach ($tree as $item) {
            if ($item['id'] === $filter) {
                continue;
            }
            $item['depth'] = $depth;
            $data[$item['id']] = $item;
            if (isset($item['children']) && $item['children']) {
                $this->quoteList($item['children'], $depth + 1, $filter);
            }
            unset($data[$item['id']]['children']);
        }
        return $data;
    }

    ###################  core end #####################

    private function sortArrayByMultiFields(array $arr, ...$args): array
    {
        foreach($args as $key => $field){
            if(is_string($field)){
                $temp = [];
                foreach($arr as $index => $val){
                    $temp[(string)$index] = $val[$field];
                }
                $args[$key] = $temp;
            }
        }
        $keys = array_keys($arr);
        $args[] = &$arr;
        $args[] = &$keys;
        array_multisort(...$args);
        return array_combine(array_pop($args), array_pop($args));
    }

    public function buildLevelName(array  $quoteList, int $spaceNum = 6, string $levelStr = '├─',
                                   string $endLevelStr = '└─', string $field = 'name'): array
    {
        $keys = array_keys($quoteList);
        $values = array_values($quoteList);
        foreach ($values as $key => $channel) {
            if ($channel['depth'] !== 1) {
                $pad = str_repeat('&nbsp;', $spaceNum * ($channel['depth'] - 1));
                $pad .= (!isset($values[$key + 1]['depth']) || $channel['depth'] > $values[$key + 1]['depth'])
                    ? $endLevelStr : $levelStr;
                $channel['level_name'] = $pad . $channel[$field];
            } else {
                $channel['level_name'] = $channel[$field];
            }
            $quoteList[$keys[$key]] = $channel;
        }
        return $quoteList;
    }

    public function getChannel(int $id): Category
    {
        return clone $this->channels->where('id', $id)->first();
    }


    public function getParents(int $id, Category $currentModel = null): Category
    {
        $model = $this->getChannel($id);
        unset($model['child']);
        if ($currentModel) {
            $model['child'] = $currentModel;
        }
        if ($model['pid'] !== 0) {
            return $this->getParents($model['pid'], $model);
        }
        return $model;
    }

}
