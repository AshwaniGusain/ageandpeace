<div class="form-wrapper"<?php if ( ! empty($ref)) : ?> data-form-ref="{{ $ref }}"<?php endif; ?>>

    @if (! empty($groups))
        @if (count($groups) > 1)
            <ul class="nav nav-tabs mb-4" role="tablist">
                <?php $i = 0; foreach ($groups as $name => $group) : ?>
                <li class="nav-item mr-2">
                    <a class="nav-link{{($i == 0) ? ' active' : '' }}" data-toggle="tab" href="#{{ str_slug($name) }}" role="tab">{{ $name }}</a>
                </li>
                <?php $i++; endforeach; ?>
            </ul>
        @endif

        <div class="errors">

        </div>

        <div class="tab-content pane-inner">

            <?php
            //$hidden = [];
            $i = 0; foreach ($groups as $name => $group) :
            ?>
            @if (count($groups) > 1)
                <div class="tab-pane{{ ($i == 0) ? ' active' : '' }}" id="{{ str_slug($name) }}" role="tabpanel">
                    @endif
                    @foreach($group as $input)
                        @if ($input->getType() == 'hidden') :
                        $hidden[$field->getKey()] = $field;
                        continue;
                        @endif

                        <div class="form-group field-{{ $input->getId() }}">
                            {!! $input->render() !!}
                        </div>

                        <?php $i++; endforeach; ?>
                        @if (count($groups) > 1)
                </div>
            @endif
            @endforeach
        </div>

        @foreach ($hidden as $name => $input)
            {!! $input->render() !!}
        @endforeach
    @endif

</div>