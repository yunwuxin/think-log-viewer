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

class LogEntry
{
    public $heading;
    public $logs;

    public function __construct($heading, $logs)
    {
        $this->heading = $heading;
        $this->logs    = $logs;
    }

    public function groupByType()
    {
        $results = [];
        foreach ($this->logs as $log) {
            if (!array_key_exists($log['type'], $results)) {
                $results[$log['type']] = new Collection();
            }

            $results[$log['type']]->offsetSet(null, $log['log']);
        }
        return $results;
    }
}