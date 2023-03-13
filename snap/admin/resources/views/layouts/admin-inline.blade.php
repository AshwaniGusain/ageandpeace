@include('admin::layouts.partials.header')

    <main is="snap-inline" id="snap-main-inline" role="main" inline-template>
        @yield('body')
    </main>

@include('admin::layouts.partials.footer')