<?php
/**
 * Created by PhpStorm.
 * User: haowang
 * Date: 2016/12/20
 * Time: 下午4:42
 */

namespace app\commands;


use GuzzleHttp\Client;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * 获取城市数据入库
 * Class SiteController
 * @package app\commands
 */
class BiaController extends Controller
{
    const URL = 'https://p.nikkansports.com/';
    const COOKIE='';

    public function actionIndex()
    {
        $url = static::URL . 'goku-uma/member/compi/compi_list.zpl';
        $client = new Client(['verify' => false]);
        $response = $client->request('get', $url, ['headers' => ['Cookie' => static::COOKIE]]);
        $html = $response->getBody();
        /**
         *
         * <ul class="schedule clearfix">
         * <li><a href=./compi.zpl?course_id=006&date=20220326>中山</a></li>
         * <li><a href=./compi.zpl?course_id=009&date=20220326>阪神</a></li>
         * <li><a href=./compi.zpl?course_id=007&date=20220326>中京</a></li>
         * </ul>
         * <h4 class="dateTitle">2022年3月27日</h4>
         * <ul class="schedule clearfix">
         * <li><a href=./compi.zpl?course_id=006&date=20220327>中山</a></li>
         * <li><a href=./compi.zpl?course_id=009&date=20220327>阪神</a></li>
         * <li><a href=./compi.zpl?course_id=007&date=20220327>中京</a></li>
         * </ul>
         */
        $infoes = html5qp((string)$html,'body > div#wrapper > div#contents > section#compiArea > ul.schedule.clearfix')
            ->children();
        if ($infoes->length==0){
            die('未获取到城市信息！');
        }


        foreach ($infoes as $info) {
            $regions = $info->find('a');
            foreach ($regions as $region) {
                $cityName = $region->text();
                file_put_contents('first.txt', $cityName . PHP_EOL, FILE_APPEND);
                $tempHref = $region->attr('href');
                $href = str_replace('./', 'goku-uma/member/compi/', $tempHref);
                $this->fetchCity($href);
            }
        }
        die('end');
    }

    public function fetchCity($href)
    {

        $url = static::URL . $href;

        file_put_contents('first.txt', $url . PHP_EOL, FILE_APPEND);
        $client = new Client(['verify' => false]);
        $response = $client->request('get', $url, ['headers' => ['Cookie' => static::COOKIE]]);
        $html = $response->getBody();
        //【馬番コンピ】※赤はコンピ推奨
        $infoes = html5qp((string)$html,
            'body#compi > div#wrapper > div#contents > section#compiArea > table.compiTable.umabanTable')->children();
        foreach ($infoes as $key => $info) {
            //第一列为表头，第二列以后为数据值
            if ($key == 0) {
                continue;
            }

            $tds = $info->children();
            foreach ($tds as $tdKey => $td) {
                if ($tdKey == 0) {
                    $race = $td->find('span.race')->text();
                    $name = $td->find('span.name')->text();
                    $lag = $td->find('span.name>font')->text();

                    file_put_contents('first.txt', $race, FILE_APPEND);
                    file_put_contents('first.txt', $name, FILE_APPEND);
                    file_put_contents('first.txt', $lag, FILE_APPEND);

                } else if ($tdKey == 1) {
                    continue;
                } else {
                    $result = [];
                    /**
                     * <td>14<br/>81</td>
                     */
                    $numberHtml = $td->html();
                    preg_match('/<td\D*(\d+)<br\/>(\d+)<\/td>/', $numberHtml, $result);//$result[0]表示原数据，从下标1开始为匹配到的内容

                    $number1 = ArrayHelper::getValue($result, 1, '');
                    $number2 = ArrayHelper::getValue($result, 2, '');
                    file_put_contents('first.txt', $number1 . '|', FILE_APPEND);
                    file_put_contents('first.txt', $number2 . '   ', FILE_APPEND);
                }
            }

            file_put_contents('first.txt', PHP_EOL, FILE_APPEND);
        }

    }
}