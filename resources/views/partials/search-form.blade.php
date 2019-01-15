<form action="{{ route('concept.index') }}"{!! !empty($class) ? " class=\"{$class}\"" : '' !!}>
    @if (!empty($concept))
        <input type="hidden" name="concept_id" value="{{ $concept->id }}">
    @endif
    <div class="input-group">
        <input id="search-input" type="search" name="q" class="form-control" value="{{ $search_term ?? '' }}" placeholder="Search {{ $concept->title ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
            @if (!empty($concept))
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <div class="dropdown-menu">
                    @foreach ($concept->ancestors()->get()->reverse() as $ancestor)
                        <a class="dropdown-item search-context" href="#" data-id="{{ $ancestor->id }}">&hellip; {{ $ancestor->title }}</a>
                    @endforeach
                    <a class="dropdown-item search-context" href="#">&hellip; globally</a>
                </div>
            @endif
        </div>
    </div><!-- /input-group -->
</form>

@push('scripts')
    <script>
        $('a.search-context').click(function (e) {
            var id = $(this).data('id');

            e.preventDefault();

            if (id) {
                $('input[name=concept_id]').val(id);
            }
            else {
                $('input[name=concept_id]').val('');
            }

            $(this).parents('form').submit();
        });
    </script>
@endpush