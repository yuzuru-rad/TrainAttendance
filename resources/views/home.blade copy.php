@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class = "row">
    <div class="col s12 m4">
        <h4>研修一覧</h4>
        <form id="trainingForm">
            @foreach ($trainings as $training)
                <p>
                    <label>
                        <input name="trainingGroup" type="radio" data-group="{{ $training->training_group }}"/>
                        <span style="font-size: 16pt; color: blue">{{ $training->training_group }}</span>
                    </label>
                </p>
            @endforeach
        </form>
    </div>

        <div class="col s12 m8">
            <h4>出席確認</h4>
            <div id="message"></div>
            <button id="startReader" class="btn waves-effect waves-light" type="submit" name="action">読み取り開始
            </button>
            <button id="stopReader" class="btn waves-effect waves-light" type="submit" name="action">読み取り停止
            </button>
            <button id="button" class="btn waves-effect waves-light">読み取り</button>
            <div><br></div>
            <div id="result">IDmをここに表示します</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('#startReader').on('click', function() {
            // 読み取り開始時の処理
            // "ICカード受付中……"を表示
            $('#message').text("ICカード受付中……");
            // ICカードリーダーの読み取り開始処理をここに追加
        });

        $('#stopReader').on('click', function() {
            // 読み取り終了時の処理
            // メッセージを消去
            $('#message').empty();
            // ICカードリーダーの読み取り停止処理をここに追加
        });
        });
    </script>
    <script>

        $(function(){
        
            $("#button").click(function(event){
                $.ajax({
                    url: " /api/readtest",
                    dataType : "text",
                    async: true,
        
                }).done(function(data){
                    $("#result").text("カードのIDmは"+data+"です");
                })
            });
        });
        
    </script>
        
@endsection

