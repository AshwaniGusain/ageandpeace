<div class="form-wrapper">

    @if ($use_form_tag)
        <form action="{{ $action }}" method="{{ $method }}"{!! html_attrs($form_attrs) !!}>
            @endif

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

                @if (count($groups) > 1)
                    <div class="tab-content pane-inner">
                        @endif
                        <?php
                        //$hidden = [];
                        $i = 0; foreach ($groups as $name => $group) :
                        ?>
                        <div @if(count($groups) > 1)class="tab-pane{{ ($i == 0) ? ' active' : '' }}" id="{{ str_slug($name) }}" role="tabpanel"@endif>
                            @foreach($group as $input)

                                <div class="form-group field-{{ $input->getId() }}">
                                    {!! $input->render() !!}
                                </div>

                                <?php $i++; endforeach; ?>
                        </div>
                        @endforeach

                        @if (count($groups) > 1)
                    </div>
                @endif

                <div class="buttons">
                    @foreach ($buttons as $name => $button)
                        {!! $button->render() !!}
                    @endforeach
                </div>

                @foreach ($hidden as $name => $input)
                    {{ $input }}
                @endforeach
            @endif

            @if ($use_form_tag)
                {{ method_field($_method) }}
                {{ csrf_field() }}
        </form>
    @endif

</div>