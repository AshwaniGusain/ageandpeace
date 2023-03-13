LISTING
<ul>
@foreach ($models as $model)
    <li>{{ $model->name }}</li>
@endforeach
</ul>