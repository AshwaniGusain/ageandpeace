<div class="pagination-count">
    {{ trans_choice('admin::resources.num_items', $items->pagination->total(), ['count' => $items->pagination->total()]) }}
</div>

<div class="form-inline justify-content-center">

    <div class="pagination-container">
        @if ($items->pagination->count())
            {!! $items->pagination->links('admin::components.pagination') !!}
        @endif
    </div>

    <div class="form-group ml-3 mr-3">
        <select name="limit" id="limit" class="form-control" title="Results to display">
            @foreach ($limit_options as $l)
                <option value="{{ $l }}"<?php if ($l == $limit) : ?> selected<?php endif; ?>>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="page" value="{{ $items->pagination->currentPage() }}">
</div>