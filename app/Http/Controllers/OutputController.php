<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


use App\Models\Attendance;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OutputController extends Controller
{   
    public function index(){
        $trainings = Training::all();
        return view('output', compact('trainings'));
    }
    /**
     * 出席者をエクセルに出力する
     * PhpSprreadsheetというライブラリを使えばできる
     * 
     */
    public function createExcel(Request $request) {
        //formatで求めてる形にデータを整形
        //enddateが入力されているか否かで処理を分ける
        if($request->input('enddate')){
            $startdateInput = $request->input('startdate');
            $enddateInput = $request->input('enddate');
            $trainingGroup = $request->input('trainingGroup');
        }else{
            $startdateInput = $request->input('startdate');
            $enddateInput = $request->input('startdate');
            $trainingGroup = $request->input('trainingGroup');
        }

        $startdate = Carbon::parse($startdateInput)->format('Y-m-d');
        $enddate = Carbon::parse($enddateInput)->format('Y-m-d');
        
        //もらってきたデータで検索する。where句を長くする場合はこんな感じに。
        $attendances = Attendance::whereBetween('training_day', [$startdate, $enddate])
                                ->where('training_group', $trainingGroup)
                                ->get();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        //セルのカラムを設定
        $sheet->setCellValue('A1', '職員番号');
        $sheet->setCellValue('B1', '氏名');
        $sheet->setCellValue('C1', '職分');
        $sheet->setCellValue('D1', '研修会');
        $sheet->setCellValue('E1', '日付');

        //セルの横幅を自動設定(setAutosize(true))したかったが、日本語未対応
        //仕方ないのでこっちで手動設定
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        
        //データの入力開始
        $row = 2;
        foreach($attendances as $attendance) {
            $sheet->setCellValue('A'.$row, $attendance->CodeNo);
            $sheet->setCellValue('B'.$row, $attendance->FullName);
            $sheet->setCellValue('C'.$row, $attendance->Section);
            $sheet->setCellValue('D'.$row, $attendance->training_group);
            $sheet->setCellValue('E'.$row, $attendance->training_day);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = "出席者リスト_".$startdate."_".$trainingGroup.".xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        return response()->download($temp_file, $fileName);
    }
}
