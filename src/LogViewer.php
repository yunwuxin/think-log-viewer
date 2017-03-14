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

use yunwuxin\logViewer\entities\LogCollection;

class LogViewer
{
    public function logs($month)
    {
        return LogCollection::load($month);
    }

    public function stats($month)
    {
        return $this->logs($month)->stats();
    }

    public function get($month, $file)
    {
        return $this->logs($month)->get($file);
    }
}