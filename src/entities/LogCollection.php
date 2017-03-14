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
use yunwuxin\logViewer\exception\LogNotFoundException;
use yunwuxin\logViewer\Filesystem;

class LogCollection extends Collection
{

    public static function load($month)
    {
        $items      = [];
        $filesystem = Filesystem::instance();

        foreach ($filesystem->dates($month, true) as $date => $path) {
            $items[$date] = Log::make($month, $date, $path, $filesystem->read($month, $date));
        }

        return self::make($items);
    }

    public function stats()
    {
        $stats = [];

        foreach ($this->items as $date => $log) {
            /** @var Log $log */
            $stats[$date] = $log->stats();
        }

        return $stats;
    }

    public function get($file)
    {
        if (!$this->offsetExists($file)) {
            throw new LogNotFoundException();
        }
        return $this->offsetGet($file);
    }

}