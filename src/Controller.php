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
use Jenssegers\Date\Date;
use think\View;
use yunwuxin\logViewer\exception\LogNotFoundException;

class Controller
{
    protected $logViewer;

    public function __construct()
    {
        $this->logViewer = new LogViewer();
    }

    private function fetch($template, $data = [])
    {
        $template = file_get_contents(__DIR__ . '/../resource/view/' . $template . '.twig');
        return View::instance(['type' => 'twig', 'auto_add_function' => true])
            ->display($template, $data);
    }

    public function index($month = null)
    {
        $now = Date::parse('this month');
        try {
            $thisMonth = Date::parse($month . '01');
            if ($thisMonth->gt($now)) {
                throw new Exception;
            }
        } catch (Exception $e) {
            $thisMonth = Date::parse('this month');
            $month     = $thisMonth->format('Ym');
        }
        $stats = $this->logViewer->stats($month);

        return $this->fetch('index', [
            'this'  => $thisMonth,
            'now'   => $now,
            'stats' => $stats
        ]);
    }

    public function read($month, $file)
    {
        try {
            $thisMonth = Date::parse($month . '01');

            $log = $this->logViewer->get($month, $file);

            $entries = $log->entries()->paginate();

            return $this->fetch('read', [
                'this'    => $thisMonth,
                'log'     => $log,
                'entries' => $entries
            ]);
        } catch (LogNotFoundException $e) {
            abort(404);
        }

    }
}