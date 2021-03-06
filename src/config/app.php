<?php
/**
 * +----------------------------------------------------------------------
 * 应用配置
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

return [
    // 调试模式
    'de_bug' => true,
    // 语言包
    'lang' => 'zh-cn',
    // 默认时区
    'default_timezone' => 'PRC',
    // APP错误日志写入
    'error_log_status' => true,
    // SQL日志写入
    'sql_log_status' => false,
    // 设置PHP最大内存上线，测试阶段建议开小，false表示不使用该项目配置
    'memory_limit' => '128M',

    // +-----------------------------
    // | Cookies
    // +-----------------------------

    // 前缀
    'cookies_prefix' => 'swx_',
    // 过期时间(s)
    'cookies_outtime' => 7200,
    // 存放目录
    'cookies_path' => '/',
    // domain
    'cookies_domain' => '',
    // secure
    'cookies_secure' => false,
    // httponly
    'cookies_httponly' => false,
    
    // +-----------------------------
    // | Session
    // +-----------------------------

    // 前缀
    'session_prefix' => 'swx_',
    // 过期时间(s)
    'session_outtime' => 7200,

    // +-----------------------------
    // | 文件上传配置
    // +-----------------------------

    'file' => [
        // 最大上传大小(KB)
        'size' => 15678,
        // 允许上传路径
        'ext' => 'jpg,jpeg,png,gif',
        // 保存目录不存在是否自动创建
        'auto_save' => true,
        // 文件名生成算法，支持sha1，md5，time三种
        'name_algorithm' => 'time',
        // 文件默认保存目录
        'path' => ROOT_PATH.'/upload/',
    ],

    // +-----------------------------
    // | 验证码设置
    // +-----------------------------
    
    'verify'             => [
        // 验证码字体大小(px)
        'fontsize' => 20,     
        // 验证码图片高度 
        'height'   => 32,      
        // 验证码图片宽度
        'width'    => 150,  
        // 验证码位数   
        'length'   => 4,       
        // 验证码字体样式
        'ttf' 	   => '6.ttf', 
        // 验证码过期时间,单位:秒
        'expire'   => 60,      
        // 是否添加混淆曲线
        'curve'	   => true,	   
        // 是否添加杂点
        'noise'	   => true,	 
        // 发起验证后是否需要更新验证码  
        'update'   => true,
	],
];
