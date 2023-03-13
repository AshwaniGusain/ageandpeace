<div class="list-group-item task {{ $category ?? '' }}">
    <p class="task-description">
        {{ $slot }}
    </p>

    <div class="btn-toolbar task-toolbar">
        @if($task->due_date)
            <span class="xsmall mr-1 align-self-center">{{ $task->due_date->format('m/d/Y') }}</span>
        @endif

            <button class="btn btn-pagination btn-sm btn-outline-primary mr-1">
                @svg('assets/svg/icon_check.svg', 'icon')
            </button>

            <button href="#" class="btn btn-sm btn-outline-secondary">
                {{$task->due_date ? 'Details' : 'Need Help'}}
            </button>
    </div>
</div>
