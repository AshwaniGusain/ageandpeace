@if ($versions->count())
<snap-restore-versions :restore-id="{{ $resource->getKey() }}" inline-template>
<select id="snap-versions" class="form-control" name="snap-versions" ref="versions" @change="go()" v-model="version">
    <option value="">{{ trans('admin::resources.restore_version') }}</option>
    @foreach ($versions as $key => $val)
        <option value="{{ $key }}">{{ $val }}</option>
    @endforeach;
</select>
</snap-restore-versions>
@endif
