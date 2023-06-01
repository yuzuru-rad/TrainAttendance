@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class = "row">
    <audio id="myAudio">
        <source src="/audio/xxxx.mp3" type="audio/mpeg">
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
            <div id="message"></div>
            <button id="startReader" class="btn waves-effect waves-light" type="submit" name="action">読み取り開始
            </button>
            <button id="stopReader" class="btn waves-effect waves-light" type="submit" name="action">読み取り停止
            </button>
            <div><br></div>
            <div id="result">タッチした人の名前が表示されます。<br>音が鳴ったらタッチしてください。</div></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function(){
            var request; // Ajaxリクエストを保存するための変数

            function startRequest() {
                $('#message').text("ICカード受付中……");
                var audio = document.getElementById("myAudio");
                audio.play();
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
                    $("#result").html(data);
                    // 1秒後にページ更新
                    setTimeout(function () {
                        location.reload();
                    }, "1000");
                })    
            }

            startRequest(); // ページロード時にリクエストを開始

            // スタートボタンクリック時にリクエストを再開
            $('#startReader').on('click', function() {
                if(request.state() === 'rejected') { // リクエストが一度中止されていたら再開
                    startRequest();
                }
            });

            // ストップボタンクリック時にリクエストを停止
            $('#stopReader').on('click', function() {
                if (request) {
                    console.log('cancel');
                    request.abort();//これでリクエスト停止しないと、上記コード『†††』以降のリロードの部分が走っちゃう！！
                    //リクエストを検知して、メッセージを表示
                    $('#message').text("カードをタッチして受付を停止してください");
                }
                //カードをタッチしないと裏で走ってるapi/attendanceが止まってくれない。
                //それが止まらないと次のサーバーへのリクエストは一切停止されるため、ここのコードはカードがタッチされるまで動かない。
                $.ajax({
                    url: "/api/cancel",
                    type: 'POST',
                    async: true,
                }).done(function(){
                    $('#message').html("受付は正常に終了しました。<br>再開する場合は開始ボタンを押してください。");
                });
            });
        });
    </script>
@endsection

