<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class jiangxianli extends Crontab
{
    private $url = 'https://ip.jiangxianli.com/blog.html';

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        $head = [
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
        ];
        $conetnt = Tool::http($this->url, $head);
        $res = Tool::exp($conetnt, '<div class="contar-wrap">', '</div></div></div>');
        $reg ='/<a href=\"(.*?)\".*?>(.*?)<\/a>/i';//匹配所有A标签
        preg_match_all($reg, $res, $array);
        if (empty($array[1][0])) {
            return false;
        }
        $url = $array[1][0];
        $res = Tool::http($url, $head);
        $res = Tool::exp($res, '<p>', '</p>');
        $res = explode('<br>', $res);
        if ($res) {
            foreach ($res as $v) {
                $str = Tool::rep($v);
                $arr = explode('@', $str);
                if (count($arr) != 2) continue;
                $str = $arr[1];
                $arr = explode(':', $arr[0]);
                
                if (stripos($str, '中国') !== false) {
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

        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
