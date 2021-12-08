<?php
/**
 * +----------------------------------------------------------------------
 * SW-X 助手函数
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

/**
 * 打印格式化
 * @todo 无
 * @author 小黄牛
 * @version v1.1.1 + 2020.07.08
 * @deprecated 暂不启用
 * @global 无
 * @param mixed $mixed 需要格式化的内容
 * @return string
*/
function dd($mixed) {
    ob_start();
    var_dump($mixed);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    $output = '<pre>' . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    
    return $output;
}

/**
 * 生成csrf命令牌
 * @todo 无
 * @author 小黄牛
 * @version v2.0.6 + 2021.4.26
 * @deprecated 暂不启用
 * @global 无
 * @return string
*/
function csrf() {
    $csrf = new \x\Csrf();
    return $csrf->create_token();
}

/**
 * 生成jwt密钥
 * @todo 无
 * @author 小黄牛
 * @version v2.0.6 + 2021.4.26
 * @deprecated 暂不启用
 * @global 无
 * @param array $payload
 * @return string
*/
function jwt($payload=[]) {
    $jwt = new \x\Jwt();
    return $jwt->create_token($payload);
}