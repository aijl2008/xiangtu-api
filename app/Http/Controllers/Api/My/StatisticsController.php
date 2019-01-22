<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:34
 */

namespace App\Http\Controllers\Api\My;


use App\Helper;
use App\Http\Controllers\Controller;
use App\Service\Statistics;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    function __invoke()
    {
        return Helper::success(
            (new Statistics())->make(Auth::guard('api')->user(), true)
        );
    }



    function make()
    {
        \JpGraph\JpGraph::load();
        \JpGraph\JpGraph::module('bar');

        $data = array(19, 23, 34, 38, 45, 67, 20);
        $ydata = array("2018-12-21", "2018-12-22", "2018-12-21", "2018-12-21", "2018-12-21", "2018-12-21", "2018-12-21");

        $graph = new \Graph(600, 400); //创建新的Graph对象
        $graph->SetScale("textlin"); //刻度样式
        $graph->SetShadow();     //设置阴影
        $graph->img->SetMargin(70, 60, 60, 70); //设置边距

        $graph->graph_theme = null; //设置主题为null，否则value->Show(); 无效

        $barplot = new \BarPlot($data); //创建BarPlot对象
        $barplot->SetFillColor('blue'); //设置颜色
        $barplot->value->Show(); //设置显示数字
        $graph->Add($barplot); //将柱形图添加到图像中
        $title = "图标实例";
        $title = @mb_convert_encoding($title, "GBK", "auto");
        $graph->title->Set($title);
        $mouth = "月份";
        $mouth = @mb_convert_encoding($mouth, "GBK", "auto");
        $graph->xaxis->title->Set($mouth); //设置标题和X-Y轴标题
        $traffic = "流量(Mbits)";
        $traffic = @mb_convert_encoding($traffic, "GBK", "auto");
        $graph->yaxis->title->Set($traffic);
        $graph->title->SetColor("red");
        $graph->title->SetMargin(10);
        $graph->xaxis->title->SetMargin(5);
        $graph->xaxis->SetTickLabels($ydata);
        $graph->title->SetFont(FF_SIMSUN, FS_NORMAL, 30);
        $graph->xaxis->title->SetFont(FF_SIMSUN, FS_NORMAL, 20);
        $graph->xaxis->SetLabelAngle(50);
        $graph->yaxis->title->SetFont(FF_SIMSUN, FS_NORMAL, 20);
        $graph->Stroke();

    }


    function upload()
    {
        $this->make();
    }

    function play()
    {
        $this->make();
    }

    function follower()
    {
        $this->make();
    }
}