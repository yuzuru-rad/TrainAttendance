@extends('layouts.app')

@section('title', 'Excel Output')

@section('content')

<div class="row">
    <div class="col s12 m6">
        <h4>出力日付選択</h4>
        <form action="{{ route('createExcel') }}" method="POST">
            @csrf
            <div class="input-field">
                <input id="date" type="text" class="datepicker" name="date" required>
                <label for="date">Date</label>
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
    });
</script>
@endsection
