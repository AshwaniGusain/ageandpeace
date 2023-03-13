@if (!empty($scopes))
<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link{{ empty($active) ? ' active' :'' }}" href="{{ $module->url($module->currentUri(), [], ['limit' => $pagination_limit]) }}">{{ trans('admin::resources.scopes_all') }}</a>
        </li>
        @foreach($scopes as $scope => $label)
        <li class="nav-item">
            <a class="nav-link{{ ($active == $scope) ? ' active' :'' }}" href="{{ $module->url($module->currentUri(), [], ['scope' => $scope, 'limit' => $pagination_limit]) }}">{{ $label }}</a>
        </li>
        @endforeach
    </ul>
    <input type="hidden" name="scope" value="{{ $active }}">
</div>
@endif