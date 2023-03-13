<div class="col-6 col-md-4 text-center mb-7 {{ isset($class) ? $class : '' }}">
    {{ $icon }}

    <h4>{{ $name}}</h4>

    <ul class="list-unstyled mb-0 small">
        @foreach ($category->childrenCategories as $child)
            <li><a href="{{route('subcategory', $child)}}">{{ $child->name }}</a></li>
        @endforeach
    </ul>
</div>
