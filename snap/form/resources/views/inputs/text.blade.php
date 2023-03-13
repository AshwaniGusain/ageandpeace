@component('form::element', ['input' => $self])
    @if (!empty($show_remaining))
    <div is="snap-text-input" :max="{{ $attrs['maxlength'] }}" input-value="{{ $attrs['value'] }}" inline-template>
        <div>
    @endif
            <input{!! html_attrs($attrs) !!}>
    @if (!empty($show_remaining))
            <div class="snap-text-input-remaining" v-cloak :class="remainingClass">Remaining: @{{ remaining }}</div>
        </div>
    </div>
    @endif
@endcomponent