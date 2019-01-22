<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:34
 */

namespace App\Http\Controllers;


use App\Models\Wechat;
use App\Service\Statistics;

class StatisticsController extends Controller
{
    function make($value, $key, $title = "", $yTitle = "", $xTitle = "")
    {
        \JpGraph\JpGraph::load();
        \JpGraph\JpGraph::module('line');


        $data = $value;
        $ydata = $key;

        $graph = new \Graph(600, 300); //创建新的Graph对象
        $graph->SetScale("textlin"); //刻度样式
        $graph->SetShadow();     //设置阴影
        $graph->img->SetMargin(50, 50, 20, 50); //设置边距
        $graph->graph_theme = null; //设置主题为null，否则value->Show(); 无效

        $linePlot = new \LinePlot($data); //创建BarPlot对象
        $linePlot->SetFillColor('blue'); //设置颜色
        $linePlot->value->Show(); //设置显示数字
        $graph->Add($linePlot); //将柱形图添加到图像中


        $title = @mb_convert_encoding($title, "GBK", "auto");
        $graph->title->Set($title);
        $graph->title->SetColor("red");
        $graph->title->SetMargin(10);
        $graph->title->SetFont(FF_SIMSUN, FS_NORMAL, 30);


        $yTitle = @mb_convert_encoding($yTitle, "GBK", "auto");
        $graph->yaxis->title->Set($yTitle);
        $graph->yaxis->title->SetFont(FF_SIMSUN, FS_NORMAL, 20);


        $xTitle = @mb_convert_encoding($xTitle, "GBK", "auto");
        $graph->xaxis->title->Set($xTitle);
        $graph->xaxis->title->SetMargin(5);
        $graph->xaxis->SetTickLabels($ydata);
        $graph->xaxis->title->SetFont(FF_SIMSUN, FS_NORMAL, 20);
        //$graph->xaxis->SetLabelAngle(50);
        $graph->Stroke();

    }


    function upload(Wechat $user)
    {
        $data = (new Statistics())->make($user);
        $this->make(
            array_values($data['upload']),
            array_keys($data['upload'])
        );
    }

    function play(Wechat $user)
    {
        $data = (new Statistics())->make($user);
        $this->make(
            array_values($data['play']),
            array_keys($data['play'])
        );
    }

    function follower(Wechat $user)
    {
        $data = (new Statistics())->make($user);
        $this->make(
            array_values($data['followers']),
            array_keys($data['followers'])
        );
    }
}