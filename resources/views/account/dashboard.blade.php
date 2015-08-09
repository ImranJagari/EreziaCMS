@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/dashboard.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-bank"></span> Mon compte
        </h1>

        {{ var_dump(Auth::user()->transactions()->get()) }}
    </div> <!-- content -->
@stop
