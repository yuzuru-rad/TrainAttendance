@extends('layouts.app')

@section('title', 'Excel Output')

@section('content')

@if(Session::has('message'))
<div class="card-panel green">
    <span class="white-text">{{ Session::get('message') }}</span>
</div>
@endif

@if(Session::has('error'))
<div class="card-panel red">
    <span class="white-text">{{ Session::get('error') }}</span>
</div>
@endif

<div class="row">
    <div class="col s12 m6">
        <h4>職員リスト更新</h4>
        <form action="{{ route('userupdate') }}" method="POST">
            @csrf
            <button class="btn waves-effect waves-light" type="submit" name="action">職員更新
            </button>
        </form>
    </div>
</div>
@endsection
