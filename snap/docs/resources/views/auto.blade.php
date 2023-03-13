@extends('admin::layouts.admin' )

@section('body')
    <div>
        {!! $heading !!}

        <div id="snap-main-display">
            <div class="container-fluid" style="padding: 1.25rem">
                <div class="row">
                    <div class="col-12 docs-class">

                        <h3>{{ $class->type() }}</h3>

                        @if ($class->canAutoDoc())
                            @if ($comment && $description = $comment->description())
                            <p>{!!  $description !!}</p>
                            @endif

                            @if ($comment && $example = $comment->example())
                            <pre><code>{!! $example !!}</code></pre>
                            @endif

                            @if($parent = $class->parent())
                            <h4>Parent</h4>
                            <pre><code><a href="{{ $url }}?c={{ $parent->name }}" class="docs-link">{{ $parent->name }}</a></code></pre>
                            @endif

                            @if($interfaces->count())
                            <h4>Interfaces</h4>
                            <pre class="docs-interfaces"><code>@foreach ($interfaces as $interface){{ $interface->name."\n" }}@endforeach</code></pre>
                            @endif

                            @if($traits->count())
                            <h4>Traits</h4>
                                <pre class="docs-traits"><code>@foreach ($traits as $trait)<a href="{{ $url }}?c={{ $trait->name }}" class="docs-link">{{ $trait->name }}</a>{{ "\n" }}@endforeach</code></pre>
                            @endif

                            @if ($constants->count())
                                <h4>Constants</h4>
                                    <pre class="docs-traits"><code>@foreach ($constants as $constant){{ $constant->asString() }}{{ "\n" }}@endforeach</code></pre>
                            @endif

                            @if ($props->count())
                            <h4>Public Properties</h4>
                            @foreach ($props as $prop)
                                <h5>
                                    <span class="docs-prop-name">{{ $prop->asString() }}</span>
                                </h5>
                                @if ($prop->comment()->exists())
                                <p class="docs-class-prop-comment">{{ $prop->comment()->description() }}</p>
                                @endif
                            @endforeach
                            @endif

                            <hr>

                            @if ($methods->count())
                            <h4>Public Methods</h4>
                            @foreach ($methods as $method)
                                <h5 id="method-{{ $method->name }}">
                                    <span class="docs-method-name">{{ $method->name }}</span>(<span class="docs-method-params">{{ $method->params()->asString() }}</span>)
                                </h5>
                                @if ($method->comment()->exists())
                                <p class="docs-method-comment">{{ $method->comment()->description() }}</p>

                                    @if ($example = $method->comment()->example())
                                        <pre><code>{!! $example !!}</code></pre>
                                    @endif
                                @endif
                                <hr>
                            @endforeach
                            @endif

                        @endif

                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection