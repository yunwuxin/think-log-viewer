<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace yunwuxin\logViewer\entities;

use think\Collection;
use yunwuxin\logViewer\LogParser;
use yunwuxin\logViewer\LogTypes;
use yunwuxin\logViewer\paginate\Semantic;

class LogEntryCollection extends Collection
{
    public function load($raw)
    {
        foreach (LogParser::parse($raw) as $entry) {
            list($heading, $logs) = $entry;

            $this->offsetSet(null, new LogEntry($heading, $logs));
        }

        return $this;
    }

    public function stats()
    {
        $counters = $this->initStats();

        /** @var LogEntry $entry */
        foreach ($this->items as $entry) {
            foreach ($entry->groupByType() as $type => $logs) {
                $count = count($logs);
                if (isset($counters[$type])) {
                    $counters[$type] += $count;
                }
                $counters['all'] += $count;
            }
        }

        return $counters;
    }

    public function forPage($page, $perPage)
    {
        return $this->slice(($page - 1) * $perPage, $perPage);
    }

    public function paginate($perPage = 20)
    {
        $page = request()->param('page', 1);
        $path = request()->baseUrl();

        return Semantic::make($this->forPage($page, $perPage), $perPage, $page, $this->count(), false, ['path' => $path]);
    }

    private function initStats()
    {
        $types = array_merge_recursive(
            ['all', 'error'],
            LogTypes::all()
        );
        return array_map(function () {
            return 0;
        }, array_flip($types));
    }
}