@extends(($inline) ? 'admin::layouts.admin-inline' : 'admin::layouts.admin' )


@section('body')
    <snap-resource-compare id="snap-resource-compare" inline-template>

        <form id="snap-form" action="<?=$module->url('doRestore', [$resource->id])?>" method="post">

            {!! $heading !!}

            <div id="snap-main-display">

                {!! $alerts !!}

                <div style="padding: 1.25rem">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Current</h4>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $exclude = [$resource->getKeyName(), $resource::UPDATED_AT];
                                    $keys = $resource->getArchivableKeys();
                                    foreach ($keys as $key) :
                                        $val = $resource->$key;
                                        if (in_array($key, $exclude)) continue;
                                    ?>
                                    <li class="list-group-item">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col">{{ $key }}</div>
                                                <div class="col">
                                                    @if (is_string($val) || is_numeric($val) || (is_object($val) && method_exists($val, '__toString')))
                                                        {{ $val }}
                                                    @elseif (is_array($val))
                                                        {{ print_r($val) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">

                                    <button type="submit" class="btn btn-primary float-right" @click="restore($event)">Restore</button>

                                    <h4>Version #<?=$version->version?> - <?=$version->created_at->format(config('snap.admin.date_format'))?></h4>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @foreach ($restore_data as $key => $val)
                                    <li class="list-group-item">
                                        <div class="container">
                                            <div class="row<?php
                                            $rawAttrs = $resource->getAttributes();
                                            if (!array_key_exists($key, $rawAttrs) || (array_key_exists($key, $rawAttrs) && $rawAttrs[$key] != $val)) : ?> alert alert-warning<?php endif; ?>">
                                                <div class="col"><?=$key?></div>
                                                <div class="col">
                                                    @if (is_string($val) || is_numeric($val) || (is_object($val) && method_exists($val, '__toString')))
                                                        {{ $val }}
                                                    @elseif (is_array($val))
                                                        {{ print_r($val) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="version" value="{{ $version->version }}">
            {{ csrf_field() }}
        </form>

    </snap-resource-compare>
@endsection('body')

