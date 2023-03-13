@extends('admin::layouts.admin' )


@section('body')
    <div>

        <form id="snap-form" class="form" action="" method="get">

            {!! $heading !!}

            <div id="snap-main-display" class="fuel-main-display-with-footer">

                <div class="container-fluid" style="padding: 1.25rem">
                    <div class="row">
                        <div class="col-9">
                            <div class="form-inline">
                                <div role="group" class="input-group">
                                    <input type="text" name="q" id="q" value="" placeholder="Search" aria-label="Search" class="form-control">
                                    <div class="input-group-append"><button type="submit" class="btn btn-secondary">
                                            <i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-9">
                            @foreach($results as $result)
                                <div class="mb-4">
                                    <h5 class="mb-0"><a href="{{ url($result->uri) }}"><span class="badge badge-secondary">{{ $result->category }}</span></a> <a href="{{ url($result->uri) }}">{{ $result->title }}</a></h5>
                                    @if ($result->excerpt)
                                    <p>{!! highlight($result->excerpt, $q) !!} <a href="{{ url($result->uri) }}">&#x2192;</a></p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <div id="snap-main-footer" class="bg-light">

                @if ($results)

                    <div class="pagination-count">
                        {{ trans_choice('admin::resources.num_items', $results->total(), ['count' => $results->total()]) }}
                    </div>

                    <div class="form-inline justify-content-center">

                        <div class="pagination-container">
                            @if ($results->count())
                                {!! $results->links('admin::components.pagination') !!}
                            @endif
                        </div>

                    </div>

                @endif
            </div>

        </form>

    </div>
@endsection('body')