@extends('core::layouts.app')

@section('content')

    <main class="container">

        <section class="page-header">

            <ol class="breadcrumb">
                <li class="active">Items</li>
            </ol>

            <h1>{{$page_title}}</small></h1>

            @include('partials.messages')

        </section>

        @if ($items->count() == 0)
            <p>Nothing here.</p>
        @else

            <table class="table">
                <thead>
                    <tr>
                        <th>Due</th>
                        @if ($show_done)
                            <th>Done</th>
                        @endif
                        <th>Persons</th>
                        <th>Concept</th>
                        <th>Title</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        @if ($item->due_at)
                            <?php $due_on = strftime('%Y-%m-%d', strtotime($item->due_at)); ?>
                            <td style="white-space:nowrap"><a href="/{{$due_on}}">{{$due_on}}</a></td>
                        @else
                            <td style="white-space:nowrap"></td>
                        @endif
                        @if ($show_done)
                            <td style="white-space:nowrap">{{ $item->done_at ? strftime('%Y-%m-%d', strtotime($item->done_at)) : '' }}</td>
                        @endif
                        <td>
                            <ul class="persons">
                            @foreach ($item->persons as $person)
                                    <li><a href="{{ route('concept.show', $person->id) }}">{{$person->title}}</a></li>
                            @endforeach
                            </ul>
                        </td>
                        <td><a href="{{ route('concept.show', $item->concept_id) }}">{{$item->concept->title}}</a></td>
                        <td width="50%">
                            {!! $item->title !!}
                            @foreach ($item->tags as $tag)
                                <a href="{{route('item.index', ['tag' => $tag->slug])}}" class="badge badge-secondary">{{$tag->name}}</a>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">{{ $items }}</div>
        @endif
    </main>

@endsection