<section class="page-header">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('concept.index')}}">Concepts</a></li>

            @foreach ($concept->ancestors()->get() as $ancestor)
                <li class="breadcrumb-item"><a href="{{route('concept.show', ['concept' => $ancestor])}}">
                        {{$ancestor->title}}
                    </a>
                </li>
            @endforeach

            <li class="breadcrumb-item active">{{$concept->title}}</li>
        </ol>
    </nav>

    @if ($can_update)

        <div class="btn-group float-right" role="group">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#concept-edit-form">
            <i class="fas fa-edit"></i> Edit concept
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu">
                @if ($is_owner)
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#concept-share-form">
                    <i class="fas fa-share-alt"></i> Share
                        @if ($concept->shares->count() > 0)
                            <span class="badge">{{ $concept->shares->count() }}</span>
                        @endif
                    </a>
                    <a class="dropdown-item" href="{{ route('attachment.index', [$concept]) }}">
                    <i class="fas fa-paperclip"></i> Attachments
                    </a>
                    <a class="dropdown-item" href="{{ route('concept.slides', [$concept]) }}">
                    <i class="fas fa-chart-line"></i> Slides
                    </a>

                    @if (!empty(env('WEBSITE_' . $concept->id)))
                        <a class="dropdown-item" href="{{route('website.publish', [$concept])}}"
                            onclick="event.preventDefault(); document.getElementById('publish-form').submit();"><i class="fas fa-globe"></i> Publish Website</a>

                        <form id="publish-form" action="{{route('website.publish', [$concept])}}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endif
                @endif
                <a class="dropdown-item" href="{{route('concept.create', ['parent_id' => $concept->id])}}"><i class="fas fa-sitemap"></i> Add child</a>
                <a class="dropdown-item" href="{{route('concept.versions', [$concept])}}"><i class="fas fa-code-branch"></i> Versions</a>
                @if ($is_owner)
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('concept.destroy', [$concept])}}"
                        onclick="event.preventDefault(); document.getElementById('delete-form').submit();"><i class="fas fa-minus-circle"></i> Delete</a>

                    <form id="delete-form" action="{{route('concept.destroy', [$concept])}}" method="POST" style="display: none;">
                        <input type="hidden" name="_method" value="DELETE">
                        {{ csrf_field() }}
                    </form>
                @endif
            </div>
        </div>

    @endif

    <h1>
        {{$concept->title}} <small>
        @if ($concept->is_flagged)
            <i class="fas fa-heart"></i>
        @endif
        @if ($concept->source_url)
            <a href="{{$concept->source_url}}">
                <i class="fas fa-link"></i>
            </a>
        @endif
        @if ($concept->shares->count() > 0)
            <i style="color:red" class="fas fa-share-alt"></i>
        @endif
        </small>
    </h1>

    <p class="meta">
        Concept <a href="{{ route('concept.short', $concept) }}">{{$concept->id}}</a>,
        <?php
        $created = strftime('%Y-%m-%d', strtotime($concept->created_at));
        $updated = strftime('%Y-%m-%d', strtotime($concept->updated_at));
        ?>
        created {{ $created }}, @if ($created != $updated)
            updated {{ $updated }},
        @endif
        viewed {{$concept->viewed_count}} time{{$concept->viewed_count > 1 ? 's' : ''}}.

        @if ($concept->type != 'concept')
            <span class="label label-info">{{ucfirst($concept->type)}}</span>
        @endif

        @if ($concept->tags->count())
            @foreach ($concept->tags as $tag)
                <a class="label label-default" href="{{route('concept.index', ['tag' => $tag->slug])}}">{{$tag->name}}</a>
            @endforeach
        @endif
    </p>

    @include('core::partials.messages')

</section>
