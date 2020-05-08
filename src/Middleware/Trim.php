<?php

namespace Ecopro\Trim\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * @author liaiyong
 */
class Trim
{
    public function handle(Request $request, Closure $next)
    {
        $this->trimInput($request);

        return $next($request);
    }

    /**
     * 过滤输入
     * @param Request $request
     * @return void
     */
    protected function trimInput(Request $request)
    {
        $all = $request->all();
        $news = [];
        foreach ($all as $key => &$value) {
            $news[$key] = $this->trimValue($value);
        }
        // 合并过滤后的
        $request->merge($news);
    }

    /**
     * 过滤值
     * @param mixed $value
     * @return mixed
     */
    protected function trimValue($value)
    {
        if(is_array($value)) {
            $tmp = [];
            foreach ($value as $k => $v) {
                $tmp[$k] = $this->trimValue($v);
            }
            $value = $tmp;
        } else if(is_string($value)) {
            $value = $this->trim($value);
        }

        return $value;
    }

    /**
     * trim方法，默认使用PHP自带
     * @param string $value
     * @return string
     */
    protected function trim($value)
    {
        return trim($value);
    }
}
