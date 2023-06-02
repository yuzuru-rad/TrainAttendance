<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


use App\Models\Attendance;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OutputController extends Controller
{
    /**
     * 出席者をエクセルに出力する
     * PhpSprreadsheetというライブラリを使えばできる
     * 
     */
    public function createExcel(Request $request) {
        $dateInput = $request->input('date');
        //formatで求めてる形にデータを整形
        $date = Carbon::parse($dateInput)->format('Y-m-d');
        $attendances = Attendance::where('training_day', $date)->get();

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

        $fileName = "出席者".$date.".xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        return response()->download($temp_file, $fileName);
    }
}
