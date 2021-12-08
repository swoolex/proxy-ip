<?php
namespace box\crontab;
use x\Crontab;
use org\Tool;

class kuaidaili extends Crontab
{
    private $url = [
        'https://www.kuaidaili.com/free/inha/',
        'https://www.kuaidaili.com/free/intr/',
    ];
    private $minPage = 1;
    private $maxPage = 2;

    // 统一入口
    public function run() {
        $Db = new \x\Db();
        $num = 0;
        foreach ($this->url as $url) {
            for ($i=$this->minPage; $i <= $this->maxPage; $i++) { 
                $res = Tool::http($url.$i, [
                    'Accept' => 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                    'Host: www.kuaidaili.com',
                    'Referer: '.$url,
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
                ]);

                $res = Tool::exp($res, '<tbody>', '</tbody>');
                $res= Tool::exps($res, '<tr>', '</tr>', 0);
                if ($res) {
                    foreach ($res as $v) {
                        $str = Tool::rep($v);
                        $arr = Tool::exps($str, '>', '</td>');
                        $arr[1] = Tool::expLeft($arr[1], '<');
                        $arr[2] = Tool::expLeft($arr[2], '<');
                        if ($arr) {
                            $res = $Db->name('china_ip_verify')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('china_ip')->where('ip', $arr[1])->where('port', $arr[2])->find();
                            if ($res) continue;
                            $res = $Db->name('china_ip_verify')->insert([
                                'ip' => $arr[1],
                                'port' => $arr[2],
                                'update_time' => time()
                            ]);
                            if ($res) $num++;
                        }
                    }
                }
                // 要等一等，不然会被检测到
                \Swoole\Coroutine\System::sleep(0.3);
            }
        }
        $Db->return();

        var_dump('成功抓取：'.$num);
    }
}
