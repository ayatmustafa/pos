@extends('layouts.dashboard.app')
@section('content')
<div class="content-wrapper">

    <section class="content-header">

        <h1>@lang('site.users')</h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>

        </ol>
    </section>
</div>
@endsection
