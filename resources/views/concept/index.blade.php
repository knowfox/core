@extends('core::layouts.app')

@section('content')

    <main class="container">

        <section class="page-header">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Concepts</li>
                </ol>
            </nav>

            <a class="btn btn-primary float-right" href="{{route('concept.create')}}"><i class="fas fa-plus-circle"></i> New concept</a>
            <h1>{{ $page_title }} <small>{!! $sub_title !!}</small></h1>

            @include('core::partials.messages')

        </section>

        @if ($concepts->count() == 0)
            <p>Nothing here.</p>
        @else

            <table class="table">
                @include('core::partials.table-header')
                <tbody>
                @foreach ($concepts as $concept)
                    @include('core::partials.table-row')
                @endforeach
                </tbody>
            </table>

            <div class="text-center">{{ $concepts }}</div>
        @endif
    </main>

@endsection