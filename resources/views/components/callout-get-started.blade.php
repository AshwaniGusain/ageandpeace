@component('components.block-cta', ['link' => route('pages', ['checklist']).'#signup', 'class' => $class ?? 'py-6 px-4 mb-2'])
    @slot('icon')
        @svg('assets/svg/icon_checklist.svg', 'icon icon-block-cta mr-2')
    @endslot

    @slot('header')
        Let's Get Started
    @endslot

    @slot('copy')
        I'm ready to start my checklist
    @endslot
@endcomponent