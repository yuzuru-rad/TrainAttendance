@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class = "row">
    <audio id="myAudio">
        <source src="/audio/success.mp3" type="audio/mpeg">
    </audio>
    <audio id="myAudio2">
        <source src="/audio/unsuccess.mp3" type="audio/mpeg">
    </audio>      
    <div class="col s12 m4">
        <h4>研修一覧</h4>
        <form id="trainingForm">
            @foreach ($trainings as $training)
                <p>
                    <label>
                        <input name="trainingGroup" type="radio" data-group="{{ $training->training_group }}"/>
                        <span style="font-size: 14pt; color: blue">{{ $training->training_group }}</span>
                    </label>
                </p>
            @endforeach
        </form>
    </div>

        <div class="col s12 m8">
            <h4>出席確認</h4>
            <div id="message" style="font-size: 12pt"></div>
            <div><br></div>
            <button id="startReader" class="btn waves-effect waves-light" type="submit" name="action">読み取り開始
            </button>
            <button id="stopReader" class="btn waves-effect waves-light red" type="submit" name="action">読み取り停止
            </button>
            <div><br></div>
            <div id="result" style="font-size: 12pt">タッチした人の名前が表示されます。<br>音が鳴ったらカードを離してください。</div></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    <script>
        var request; // Ajaxリクエストを保存するための変数
        $(document).ready(function(){
            $('#message').text("研修を選択して、読み取り開始を押してください。");
            // セッションストレージにstartReaderStatusがなければ初期化
            if (!sessionStorage.getItem('startReaderStatus')) {
                sessionStorage.setItem('startReaderStatus', 'off');
            }

            // ページロード時にstartReaderStatusがonならリクエストを開始
            if (sessionStorage.getItem('startReaderStatus') === 'on') {
                startRequest();
            }

            // startReaderボタンクリック時にstartReaderStatusを切り替え、リクエストを制御
            $('#startReader').on('click', function() {
                if (sessionStorage.getItem('startReaderStatus') === 'off') {
                    sessionStorage.setItem('startReaderStatus', 'on');
                    startRequest();
                } 
            });
            

            var audio = document.getElementById("myAudio");
            /**セッションに保存されてるラジオボタンがないか確認。
             * あった場合は、それをセット、なかった場合は一番上をセット
             * なんでこんな処理かっていうと、ajaxリクエストを作り直すためにリロード挟んでるから。
             */
            let selectedTrainingGroup = sessionStorage.getItem('selectedTrainingGroup');

            if (selectedTrainingGroup) {
                $("input[name='trainingGroup'][data-group='" + selectedTrainingGroup + "']").prop('checked', true);
            }else {
                $("input[name='trainingGroup']:first").prop('checked', true);
                sessionStorage.setItem('selectedTrainingGroup', $("input[name='trainingGroup']:first").data('group'));
            }

            //ラジオボタンクリック時に、セッションにラジオボタンを保存する動作。
            $("input[name='trainingGroup']").on('click', function() {
                sessionStorage.setItem('selectedTrainingGroup', $(this).data('group'));
            });

            // ストップボタンクリック時にリクエストを停止
            $('#stopReader').on('click', function() {
                sessionStorage.setItem('startReaderStatus','off');
                if (request) {
                    console.log('cancel');
                    request.abort();//これでリクエスト停止しないと、下記コード『†††††††††』以降のリロードの部分が走っちゃう！！
                    //リクエストを検知して、メッセージを表示
                    $('#message').html("必ず出席者のカードをタッチして<br>受付を停止してください。").css({color: 'red', 'font-size': '18pt'});
                }
                //カードをタッチしないと裏で走ってるapi/attendanceが止まってくれない。
                //それが止まらないと次のサーバーへのリクエストは一切停止されるため、ここのコードはカードがタッチされるまで動かない。
                $.ajax({
                    url: "/api/cancel",
                    type: 'POST',
                    async: true,
                }).done(function(){
                    $('#message').html("受付は正常に終了しました。<br>再開する場合は開始ボタンを押してください。").css({color: 'black', 'font-size': '12pt'});
                });
            });
        });

        function startRequest() {
            var audio = document.getElementById("myAudio");
            var audio2 = document.getElementById("myAudio2");

            $('#message').text("ICカード受付中……");
            //ラジオボタンで選択した研修会をセット
            let trainingGroup = sessionStorage.getItem('selectedTrainingGroup');

            //ajaxでtraining_groupの中身を飛ばす。
            //カードの読み取りは/api/attendanceのほうで行っているため、『†††††††††』の部分まではコードが勝手に走ってしまう。
            request = $.ajax({
                url: "/api/attendance",
                type: 'POST',
                data: {
                    'training_group': trainingGroup
                },
                async: true, //　†††††††††　stopReaderはこっから下の処理を止めるコード
            }).done(function(data){
                console.log(data + "を取得しました。");    
                // <div id="result">にdataを代入
                if(data){
                    audio.play();
                    $('#message').text("受付ました。カードを離してください。");
                    $("#result").html(data);
                }else{
                    audio2.play();
                    $('#message').text('不正な入力です、多重出席または、データベースに未登録の職員になります。')
                }
                // 1秒後にページ更新
                setTimeout(function () {
                    location.reload();
                }, "1000");
            })    
        }

    </script>
@endsection

