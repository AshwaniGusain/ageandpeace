@if ($displayValue)
<div>
    <div class="row">
    @foreach ($models as $model)
    <div class="col">
        <div><img src="{{ $model->getUrl() }}" alt="{{ $model->file_name }}"></div>
        <div>{{ $model->file_name }}</div>
        <div>{{ $model->human_readable_size }}</div>
    </div>
    @endforeach
    </div>
</div>
@endif