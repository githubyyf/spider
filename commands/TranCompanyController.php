<?php
/**
 * 获取快递公司名称
 */

namespace app\commands;

use app\models\TranCompany;
use yii\console\Controller;

class TranCompanyController extends Controller
{
    public function actionCreate()
    {
        $qp= html5qp("http://www.ckd.cc/company.html")->find('#company')->children('table');
        $info=[];
        foreach ($qp as $key=>$child) {
            if ($key!=0){
                continue;
            }
            $td=$child->find('td');
            foreach ($td as $temp_key=>$temp_td){
                $data1=$temp_td;
                $data2=$temp_td;
                $tran_company=new TranCompany();
                $url=trim($data1->children('a')->attr('href'));
                $name=trim($data2->text());
                if (empty($name)){
                    continue;
                }
                $tran_company->name=$name;
                $tran_company->index_url=$url;
                $tran_company->created_at=time();
                $tran_company->updated_at=time();
                if (!$tran_company->save())
                {
                    var_dump($tran_company->getErrors());
                    continue;
                }
                $info[]=[
                    'url'=>$url,
                    'name'=>$name,
                    'num'=>$key,
                ];
            }

        }

        $myfile=fopen("js.text",'w');
        fwrite($myfile,json_encode($info));
        //匹配字符<div id="company">[\s\S]*</table>
    }
}