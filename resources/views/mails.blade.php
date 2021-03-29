@extends('layouts.app')

@section('title', 'Report Builder')

@section('content')
    <div style="margin-top: 30px;">

        <div id="app">
            <mails :mails="{{json_encode($mails)}}"></mails>
        </div>

    </div>
@endsection
