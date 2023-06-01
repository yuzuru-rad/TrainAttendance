<?php

namespace App\Http\Controllers;

use App\Models\Userdata;
use App\Models\UserdataSqlsrv;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * 複数のDBを扱ってるだけ。
     * 現在のDBを削除して、コピー元から丸々持ってくる作業。
     */
    public function updateData()
    {
        Log::debug('start!');
        // MySQLのテーブルから全レコードを削除
        Userdata::truncate();
        Log::debug('delete complete!');
        // SQL Serverからデータを取得
        $users = UserdataSqlsrv::all();
        Log::debug('get complete!');

        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                // MySQLにデータを保存
                Userdata::create($user->toArray());
            }
            Log::debug($user -> CodeNo);
            DB::commit();

            // 完了したらユーザーにメッセージを表示
            return redirect()->back()->with('message', '職員データを更新しました');
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollback();

            // エラーメッセージをユーザーに表示（
            return redirect()->back()->with('error', '職員データの更新に失敗しました: ' . $e->getMessage());
        }
    }
}
