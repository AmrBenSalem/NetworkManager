<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scan;
use App\Jobs\DoScan;
use App\FusionCharts;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

            $failedpings = DB::table('pings')->where('status','failed')->count();
            $successpings = DB::table('pings')->where('status','success')->count();
            $total = $failedpings + $successpings;
            $data = array(
            "chart" => array(
            "caption"=> "Split of ping checks result on the networks",
            "subCaption"=> "",
            "numberPrefix"=> "",
            "showBorder"=> "1",
            "use3DLighting"=> "0",
            "enableSmartLabels"=> "0",
            "startingAngle"=> "310",
            "showLabels"=> "0",
            "showPercentValues"=> "1",
            "showLegend"=> "1",
            "defaultCenterLabel"=> "Total pings : $total",
            "centerLabel"=> '$label : $value',
            "centerLabelBold"=> "1",
            "showTooltip"=> "0",
            "decimals"=> "0",
            "useDataPlotColorForLabels"=> "1",
            "theme"=> "fint",
            "formatNumberScale" => "0"
        )
    );

    $data["data"] = array();

      array_push($data["data"], array(
          "label" => "Success",
          "value" => "$successpings"
          )
      );

      array_push($data["data"], array(
          "label" => "Failed",
          "value" => "$failedpings"
          )
      );
      $jdata = json_encode($data);


            $failedscans = DB::table('scans')->where('status','failed')->count();
            $successscans = DB::table('scans')->where('status','success')->count();
            $total2 = $failedscans + $successscans;
            $data2 = array(
            "chart" => array(
            "caption"=> "Split of snmp checks result on the networks",
            "subCaption"=> "",
            "numberPrefix"=> "",
            "showBorder"=> "1",
            "use3DLighting"=> "0",
            "enableSmartLabels"=> "0",
            "startingAngle"=> "150",
            "showLabels"=> "0",
            "showPercentValues"=> "1",
            "showLegend"=> "1",
            "defaultCenterLabel"=> "Total snmp checks : $total2",
            "centerLabel"=> '$label : $value',
            "centerLabelBold"=> "1",
            "showTooltip"=> "0",
            "decimals"=> "0",
            "useDataPlotColorForLabels"=> "1",
            "theme"=> "fint",
            "formatNumberScale" => "0"
              )
            );

    $data2["data"] = array();

      array_push($data2["data"], array(
          "label" => "Success",
          "value" => "$successscans"
          )
      );

      array_push($data2["data"], array(
          "label" => "Failed",
          "value" => "$failedscans"
          )
      );
      $jdata2 = json_encode($data2);





            $data3 = array(
            "chart" => array(
                "caption"=> "Devices in each network",
                "subCaption"=> "",
                "yAxisName"=> "Devices number ",
                "numberPrefix"=> "",
                "paletteColors"=> "#1786cf",
                "bgColor"=> "#ffffff",
                "showBorder"=> "1",
                "showCanvasBorder"=> "0",
                "usePlotGradientColor"=> "0",
                "plotBorderAlpha"=> "10",
                "placeValuesInside"=> "1",
                "valueFontColor"=> "#ffffff",
                "showAxisLines"=> "1",
                "axisLineAlpha"=> "25",
                "divLineAlpha"=> "10",
                "alignCaptionWithCanvas"=> "0",
                "showAlternateVGridColor"=> "0",
                "captionFontSize"=> "14",
                "subcaptionFontSize"=> "14",
                "subcaptionFontBold"=> "0",
                "toolTipColor"=> "#ffffff",
                "toolTipBorderThickness"=> "0",
                "toolTipBgColor"=> "#000000",
                "toolTipBgAlpha"=> "80",
                "toolTipBorderRadius"=> "2",
                "toolTipPadding"=> "5",
                "maxBarHeight"=>"15",
                "showYAxisValues"=>"0"
              )
            );


      $data3["data"] = array();
      $networks = DB::table('networks')->get();

      foreach ($networks as $network) {
        $nb=DB::table('devices')->where('nname',$network->ip)->count();
      
      array_push($data3["data"], array(
          "label" => "$network->nname",
          "value" => "$nb"
          )
      );
      }
      

      $jdata3 = json_encode($data3);





        return view('/dashboard/home')->with('data',$jdata)->with('data2',$jdata2)->with('data3',$jdata3);
    }
}
