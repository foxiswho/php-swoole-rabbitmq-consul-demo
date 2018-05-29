<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**通用返回
 * @param       $msg
 * @param bool  $success
 * @param array $data
 * @return array
 */
function ret($msg, $success = false, $data = [])
{
    $ret = [];
    if ($data) {
        if (is_array($data)) {
            $ret = $data;
        }
    }
    $ret['msg'] = $msg;
    $ret['ret'] = $success ? 1 : 0;
    return $ret;
}