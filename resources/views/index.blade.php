@extends('layouts.app')

@section('title', 'Report Builder')

@section('content')
<div style="margin-top: 30px;">

    <div id="app">
        <contacts :current-user="{{json_encode(\Illuminate\Support\Facades\Auth::user())}}"></contacts>
    </div>

</div>
@endsection
