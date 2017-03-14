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
namespace yunwuxin\logViewer;

class LogParser
{

    public static function parse($raw)
    {
        $pattern = '/---------------------------------------------------------------/';
        $data    = array_filter(preg_split($pattern, $raw));

        $entries = [];
        foreach ($data as $group) {
            $heading = null;
            $group   = preg_replace_callback('/^\[\s[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}\+[0-9]{2}:[0-9]{2}\s\]\s.+?\n/', function ($matches) use (&$heading) {
                $heading = trim($matches[0]);
            }, trim($group));
            $pattern = '/(^|\n)\[\s(\w+)\s\]\s/';
            preg_match_all($pattern, $group, $types);
            $types = $types[2];
            $group = preg_split($pattern, $group);
            if ($group[0] < 1) {
                array_shift($group);
            }
            $logs = [];
            array_walk($types, function ($type, $key) use (&$logs, $group) {
                $logs[] = [
                    'type' => $type,
                    'log'  => $group[$key]
                ];
            });
            $entries[] = [$heading, $logs];
        }

        return $entries;
    }

}