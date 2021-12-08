<?php
/**
 * +----------------------------------------------------------------------
 * 定时任务加载
 * +----------------------------------------------------------------------
 * 官网：https://www.sw-x.cn
 * +----------------------------------------------------------------------
 * 作者：小黄牛 <1731223728@qq.com>
 * +----------------------------------------------------------------------
 * 开源协议：http://www.apache.org/licenses/LICENSE-2.0
 * +----------------------------------------------------------------------
*/

/*
 * 格式 ：二维数组
 * 
 * 字段如下：
 * 
 * rule    ：定时器规则，若为int类型则是自定义毫秒，字符串则为linux的crontab规则
 * use     ：定时器的命名空间地址
 * status  ：是否启用 true.开启  false.关闭  默认.true 
 * bin_log ：是否记录日志  true.开启  false.关闭  默认.false 
 * 
 * crontab规则（6）：
 * 秒分时天月星期
 * * * * * * 要执行的命令
 - - - - - - 
 | | | | | |
 | | | | | ----- 星期几 (0 - 7) (星期天=0 或者 7)
 | | | | ------ 月份 (1 - 12)
 | | | ------- 天数 (1 - 31)
 | | -------- 小时 (0 - 23)
 | --------- 分钟 (0 - 59)
 ----------- 秒   (0 - 59)
*/

return [
    [
        'rule' => 70000,
        'use' => '\box\crontab\dieniao',
        'status' => true,
    ],
    [
        'rule' => 60000,
        'use' => '\box\crontab\fatezero',
        'status' => true,
    ],
    [
        'rule' => 90000,
        'use' => '\box\crontab\ihuan',
        'status' => true,
    ],
    [
        'rule' => 5000,
        'use' => '\box\crontab\ip66',
        'status' => true,
    ],
    [
        'rule' => 30000,
        'use' => '\box\crontab\ip89',
        'status' => true,
    ],
    [
        'rule' => 20000,
        'use' => '\box\crontab\ip3366',
        'status' => true,
    ],
    [
        'rule' => 30000,
        'use' => '\box\crontab\jiangxianli',
        'status' => true,
    ],
    [
        'rule' => 20000,
        'use' => '\box\crontab\kuaidaili',
        'status' => true,
    ],
    [
        'rule' => 60000,
        'use' => '\box\crontab\kxdaili',
        'status' => true,
    ],
    [
        'rule' => 20000,
        'use' => '\box\crontab\proxy11',
        'status' => true,
    ],
    [
        'rule' => 60000,
        'use' => '\box\crontab\proxylistplus',
        'status' => true,
    ],
    [
        'rule' => 10000,
        'use' => '\box\crontab\seofangfa',
        'status' => true,
    ],
    [
        'rule' => 60000,
        'use' => '\box\crontab\taiyanghttp',
        'status' => true,
    ],
    [
        'rule' => 20000,
        'use' => '\box\crontab\xsdaili',
        'status' => true,
    ],
    [
        'rule' => 5000,
        'use' => '\box\crontab\yqie',
        'status' => true,
    ]
];