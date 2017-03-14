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

use Jenssegers\Date\Date;
use SplFileInfo;

class Log
{
    /* ------------------------------------------------------------------------------------------------
       |  Properties
       | ------------------------------------------------------------------------------------------------
       */
    /** @var string */
    public $date;

    public $month;

    /** @var string */
    private $path;

    /** @var LogEntryCollection */
    private $entries;

    /** @var \SplFileInfo */
    private $file;

    /**
     * Log constructor.
     *
     * @param         $month
     * @param  string $date
     * @param  string $path
     * @param  string $raw
     */
    public function __construct($month, $date, $path, $raw)
    {
        $this->month   = $month;
        $this->date    = $date;
        $this->path    = $path;
        $this->file    = new SplFileInfo($path);
        $this->entries = (new LogEntryCollection)->load($raw);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function file()
    {
        return $this->file;
    }

    public function size()
    {
        return $this->formatSize($this->file->getSize());
    }

    public function createdAt()
    {
        return Date::createFromTimestamp($this->file()->getATime());
    }

    public function updatedAt()
    {
        return Date::createFromTimestamp($this->file()->getMTime());
    }

    public static function make($month, $date, $path, $raw)
    {
        return new self($month, $date, $path, $raw);
    }

    public function entries()
    {
        return $this->entries;
    }

    public function stats()
    {
        return $this->entries->stats();
    }

    private function formatSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}