@extends('core::layouts.app')

@section('content')
<div id="app" class="home container">
    @include('core::partials.messages')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('core::partials.search-form')
        </div>
    </div>
</div>
@endsection
