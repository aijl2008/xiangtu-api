<?php

namespace App\Http\Controllers;


class JpGraphController extends Controller
{
    function __invoke()
    {
        \JpGraph\JpGraph::load();
        \JpGraph\JpGraph::module('bar');

        $data = array(19,23,34,38,45,67,20);
        $ydata = array("2018-12-21","2018-12-22","2018-12-21","2018-12-21","2018-12-21","2018-12-21","2018-12-21");

        $graph = new \Graph(500,300); //创建新的Graph对象
        $graph->SetScale("textlin"); //刻度样式
        $graph->SetShadow();     //设置阴影
        $graph->img->SetMargin(40,30,40,50); //设置边距

        $graph->graph_theme = null; //设置主题为null，否则value->Show(); 无效

        $barplot = new \BarPlot($data); //创建BarPlot对象
        $barplot->SetFillColor('blue'); //设置颜色
        $barplot->value->Show(); //设置显示数字
        $graph->Add($barplot); //将柱形图添加到图像中



        $title = "JpGraph中文测试";
        $title = iconv("UTF-8", "gb2312", $title);
        $graph->title->Set($title);

        $graph->xaxis->title->Set("月份"); //设置标题和X-Y轴标题
        $graph->yaxis->title->Set("流 量(Mbits)");
        $graph->title->SetColor("red");
        $graph->title->SetMargin(10);
        $graph->xaxis->title->SetMargin(5);
        $graph->xaxis->SetTickLabels($ydata);

//        $graph->title->SetFont(FF_SIMSUN,FS_BOLD); //设置字体
//        $graph->yaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
//        $graph->xaxis->title->SetFont(FF_SIMSUN,FS_BOLD);
//        $graph->xaxis->SetFont(FF_SIMSUN,FS_BOLD);
        $graph->Stroke();

    }
}
