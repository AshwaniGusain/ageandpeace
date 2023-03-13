@component('components.block-cta', ['link' => route('pages', ['providers']), 'class' => $class ?? 'py-6 px-4 mb-2'])

    @slot('icon')
        @svg('assets/svg/icon_find_provider.svg', 'icon icon-block-cta mr-2')
    @endslot

    @slot('header')
        Find a Provider
    @endslot

    @slot('copy')
        Take me to the provider list
    @endslot
@endcomponent