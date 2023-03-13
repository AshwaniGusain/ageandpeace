<snap-repeatable-input ref="repeatable" class="repeatable" name="{{ $name }}" :depth-init="{{ $depth }}" data-input="{{ $input }}" url="{{ $url }}" warn="{{ $warn}}"{!!   ($min) ? ' :min="'.$min.'"' : ''!!}{!! ($max) ? ' :max="'.$max.'"' : '' !!}{{ ($sortable) ? ' sortable' : ''}}{{($collapse) ? ' collapse' : ''}} inline-template>

    <div data-name="{{ $name }}" :data-index="index" :data-depth="depth" :data-prefix="prefix" :data-scope="scope">

        <div class="list-group repeatable-rows" v-on:removed="remove()" ref="repeatable-rows">

            @foreach($rows as $index => $row)
            {!! view('form::inputs.repeatable-row', ['inputs' => $row, 'row_label' => $row_label]) !!}
            @endforeach

        </div>

        <div class="repeatable-add"><a href="javascript:;" class="btn btn-secondary btn-sm btn-action" @click="add" v-show="canAdd"><i class="fa fa-plus"></i></a></div>

    </div>

</snap-repeatable-input>