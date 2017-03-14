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
namespace yunwuxin\logViewer\paginate;

use think\paginator\driver\Bootstrap;

class Semantic extends Bootstrap
{

    protected function getAvailablePageWrapper($url, $page)
    {
        return '<a class="item" href="' . htmlentities($url) . '">' . $page . '</a>';
    }

    protected function getDisabledTextWrapper($text)
    {
        return '<div class="item disabled">' . $text . '</div>';
    }

    protected function getActivePageWrapper($text)
    {
        return '<li class="active item">' . $text . '</li>';
    }

    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<div class="ui segment"><ul class="ui pagination menu">%s %s</ul></div>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                return sprintf(
                    '<div class="ui segment"><ul class="ui pagination menu">%s %s %s</ul></div>',
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton()
                );
            }
        }
    }
}