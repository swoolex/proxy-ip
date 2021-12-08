<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class ihuan extends Crontab
{
    private $url = 'https://ip.ihuan.me/';
    private $minPage = 1;
    private $maxPage = 200;

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        $head = [
            'authority' => 'ip.ihuan.me',
            'method' => 'GET',
            'path' => '/',
            'scheme' => 'https',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
        ];
        for ($i=$this->minPage; $i <= $this->maxPage; $i++) {
            if (!isset($url)) $url = $this->url;
            $conetnt = Tool::http($url, $head);
            $res = Tool::exp($conetnt, '<tbody>', '</tbody>');
            $res = Tool::exps($res, '<tr>', '</tr>', 0);

            if ($res) {
                foreach ($res as $v) {
                    $str = Tool::rep($v);
                    $arr = Tool::exps($str, '<td>', '</td>');
                    if (!empty($arr[2])) {
                        $arr[1] = strip_tags($arr[1]);
                        $arr[3] = strip_tags($arr[3]);
                        if (stripos($arr[3], '中国') !== false) {
                            $res = $Db->name('china_ip_verify')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('china_ip')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('china_ip_verify')->insert([
                                'ip' => $arr[1],
                                'port' => $arr[2],
                                'update_time' => time()
                            ]);
                        } else {
                            $res = $Db->name('abroad_ip_verify')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('abroad_ip')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('abroad_ip_verify')->insert([
                                'ip' => $arr[1],
                                'port' => $arr[2],
                                'update_time' => time()
                            ]);
                        }
                        if ($res) $num++;
                    }
                }
            }

            // 下一页
            $res = Tool::exp($conetnt, '<ul class="pagination">', '</ul>');
            $reg ='/<a href=\"(.*?)\".*?>(.*?)<\/a>/i';//匹配所有A标签
            preg_match_all($reg, $res, $array);
            if (empty($array[1][2])) {
                break;
            }
            $url = $this->url.$array[1][2];
        }
        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
