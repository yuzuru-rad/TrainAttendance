<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Attendance;
use App\Models\Userdata;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * めっちゃ回避策。
     * attendaneceが止まらないため、最新のタッチレコードを削除する必要があった。
     * IDのずれが頻繁に発生することになるのが課題、IDは別に出力しないからいいか…。
     */
    public function cancel()
    {
        $attendance = Attendance::orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->first();
        $attendance->delete();
        return;
    }

    /**
     * phpのサーバーサイドの難しさの体現
     * キューとジョブ管理はいつかやらなければいけない課題だが、今回はFelicaですでに面倒な課題があったため、省略。
     * 回避策が上記のcancel()ファンクションとなった。
     * pythonを呼び出しているのだが、このpythonが曲者。こいつのせいで処理がリクエスト町の段階で止まり、あとから止める手段が存在しない。
     * 当然、あとからこちらに止めるリクエストを送るわけにもいかないので、これは止まらないリクエストとして動いてしまう。
     * 回避策は、その動作を理解してそれを無効化することだったので、cancelではレコードの削除を実装している。
     */
    public function attendance(Request $request)
    {
        //最悪30分は待つ構え
        set_time_limit(3000);
        
        $pythonPath =  "../app/Python/";
        $command = "python " . $pythonPath . "readnfc.py";
        
        /**
         * pythonを起動、pythonファイルはあらかじめ配置、カードがタッチされて情報が行くまで、ここで全てのセッションリクエストが止まる。
         * もしかしてこれ、python側でn分経ったら止めるとか実装するのが丸い…？
         * 要検証
         */
        exec($command,$output);
        
        $idm=$output[0];
        
        //FelicaIDを基にuserdataテーブルから検索
        $userdata = Userdata::where('IDMNo', $idm)->first();

        if ($userdata !== null) {
            Log::debug("hit");
            Log::debug($userdata);
            //ざっくりデータを用意する
            $fullname = $userdata->FullName;
            $codeNo = $userdata->CodeNo;
            $section = $userdata->Section;
            //受け取ったリクエストから$training_groupを作成
            $training_group = $request->input('training_group');

            //attendanceテーブルに保存するための準備
            $attendance = new Attendance();
            $attendance->FullName = $fullname;
            $attendance->CodeNo = $codeNo;
            $attendance->Section = $section;
            $attendance->training_group = $training_group;
            $attendance->training_day = now()->format('Y-m-d');    

            $attendance->save();
            print $fullname;
            exit();
        } else {
            //IDMNoが未登録だった場合こちらを返す
            $message = "未登録の職員です。";
            print $message;
            exit();
        }

    }
}
