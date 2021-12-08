<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class xsdaili extends Crontab
{
    private $txtUrl = 'http://www.xsdaili.cn/';
    private $url = 'http://www.xsdaili.cn';

    // 统一入口
    public function run() {
        $vif = ['北京', '上海', '浙江', '河北', '山东', '广东', '湖南'];
        $head = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Host' => 'www.xsdaili.cn',
            'User-Agent' => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36',
        ];
        $str =  Tool::http($this->txtUrl, $head);
        $str = Tool::exp($str, '<div class="col-md-12">', "<div class='page'>");
        $reg ='/<a href=\"(.*?)\".*?>(.*?)<\/a>/i';//匹配所有A标签
        preg_match_all($reg, $str, $array);
        if (empty($array[1][2])) return false;
        $Db = new \x\Db();
        $list = [];
        $list[] = $this->url.$array[1][0];
        $list[] = $this->url.$array[1][2];

        $num = 0;
        foreach ($list as $url) {
            $res = Tool::http($url, $head);
            $res = Tool::exp($res, '<div class="cont">', '</div>');
            $status = false;
            foreach ($vif as $v) {
                if (stripos($res, $v) !== false) {
                    $status = true;
                    break;
                }
            }
            $res = explode('<br>', $res);
            unset($res[0]);
            if ($res) {
                foreach ($res as $v) {
                    $str = Tool::rep($v);
                    $arr = explode('@', $str);
                    if (count($arr) != 2) continue;
                    $arr = explode(':', $arr[0]);
                    if ($status !== false) {
                        $res = $Db->name('china_ip_verify')->where('ip', $arr[0])->where('port', $arr[1])->find();
                        if ($res) continue;
                        $res = $Db->name('china_ip')->where('ip', $arr[0])->where('port', $arr[1])->find();
                        if ($res) continue;
                        $res = $Db->name('china_ip_verify')->insert([
                            'ip' => $arr[0],
                            'port' => $arr[1],
                            'update_time' => time()
                        ]);
                    } else {
                        $res = $Db->name('abroad_ip_verify')->where('ip', $arr[0])->where('port', $arr[1])->find();
                        if ($res) continue;
                        $res = $Db->name('abroad_ip')->where('ip', $arr[0])->where('port', $arr[1])->find();
                        if ($res) continue;
                        $res = $Db->name('abroad_ip_verify')->insert([
                            'ip' => $arr[0],
                            'port' => $arr[1],
                            'update_time' => time()
                        ]);
                    }
                    if ($res) $num++;
                }
            }
        }
        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
