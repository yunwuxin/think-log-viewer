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

use Exception;

class Filesystem
{
    private static $instance;

    private function getFiles($month)
    {
        $files = glob(
            LOG_PATH . $month . DS . '*.log', GLOB_BRACE
        );

        return array_filter(array_map('realpath', $files));
    }

    public function logs($month)
    {
        return $this->getFiles($month);
    }

    public function dates($month, $withPaths = false)
    {
        $files = array_reverse($this->logs($month));
        $dates = $this->extractDates($month, $files);

        if ($withPaths) {
            $dates = array_combine($dates, $files); // [date => file]
        }

        return $dates;
    }

    public function read($month, $date)
    {
        try {
            $log = file_get_contents(
                $this->getLogPath($month, $date)
            );
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $log;
    }

    private function getLogPath($month, $date)
    {
        $path = LOG_PATH . $month . DS . $date . '.log';

        if (!is_file($path)) {
            throw new Exception("The log(s) could not be located at : $path");
        }

        return realpath($path);
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function extractDates($month, array $files)
    {
        return array_map(function ($file) use ($month) {
            return basename($file, '.log');
        }, $files);
    }
}