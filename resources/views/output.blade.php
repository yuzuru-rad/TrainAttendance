@extends('layouts.app')

@section('title', 'Excel Output')

@section('content')

<div class="row">
    <div class="col s12 m6">
        <h4>出力日付選択</h4>
        <form action="{{ route('createExcel') }}" method="POST">
            @csrf
            <div class="input-field">
                <input id="startdate" type="text" class="datepicker" name="startdate" required>
                <label for="startdate">開始日</label>
            </div>
            <div class="input-field">
                <input id="enddate" type="text" class="datepicker" name="enddate">
                <label for="enddate">終了日(同日の場合は未入力で結構です。)</label>
            </div>
            <div class="input-field">
                <select name="trainingGroup" required>
                    <option value="" disabled selected>研修会を選択してください。</option>
                    @foreach ($trainings as $training)
                        <option value="{{ $training->training_group }}">{{ $training->training_group }}</option>
                    @endforeach
                </select>
                <label for="training_group">Training Group</label>
            </div>
            <button class="btn waves-effect waves-light" type="submit" name="action">出力
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker();
        $('select').formSelect();
    });
</script>
@endsection
