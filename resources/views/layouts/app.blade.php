<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{--favicons--}}
    <!-- generics -->
    <meta name="description" content="@yield('meta-description', 'Age & Peace provides a set of holistic tools, resources and an organized network of partners for seniors.')" />
    <link rel="shortcut icon" href="{{ asset('assets/favicons/favicon-32x32.png') }}">
    <link rel="icon" href="{{ asset('assets/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" href="{{ asset('assets/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/favicons/favicon-96x96.png') }}" sizes="96x96">

    <!-- Android -->
    <link rel="shortcut icon" href=“{{ asset('assets/favicons/android-icon-192x192.png') }}" sizes="192x192">

    <!-- iOS -->
    <link rel="apple-touch-icon" href="{{ asset('assets/favicons/apple-icon-120x120.png') }}" sizes="120x120">
    <link rel="apple-touch-icon" href="{{ asset('assets/favicons/apple-icon-152x152.png') }}" sizes="152x152">
    <link rel="apple-touch-icon" href="{{ asset('assets/favicons/apple-icon-180x180.png') }}" sizes="180x180">

    <!-- Windows 8 IE 10-->
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="{{ asset('assets/favicons/ms-icon-144x144.png') }}">

    <link rel="manifest" href="{{ asset('assets/favicons/manifest.json') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', config('app.name', config('snap.pages.page_title_default')))</title>

    {{--OG--}}
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('og:title', config('snap.pages.page_title_default'))">
    <meta property="og:type" content="@yield('og:type', 'website')" />
    <meta property="og:description" content="@yield('og:description', 'Age & Peace provides a set of holistic tools, resources and an organized network of partners for seniors.')">
    <meta property="og:image" content="@yield('og:image', asset('assets/images/og_image.png'))">

    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="position-relative">
        @include('layouts.partials.header', ['header' =>  $header ?? 'both'])

        <main>
            @yield('content')
        </main>

        @include('layouts.partials.footer')

        <portal-target name="bottom" multiple></portal-target>

        @if( $successMsg = session('success_message') )
            <flash-messages message="{{ $successMsg }}"></flash-messages>
        @elseif( $errorMsg = session('error_message') )
            <flash-messages message="{{ $errorMsg }}" type="danger"></flash-messages>
        @endif
    </div>
    <!-- Scripts -->
    @if (!empty($dashboard))
        <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    @else
        <script src="{{ asset('assets/js/app.js') }}"></script>
    @endif
    <script src="{{ asset('assets/js/mailchimp-newsletter.js') }}"></script>
    {!! js('app-scripts')->version()->scripts(['defer' => true, 'asyc' => true]) !!}
{{--    <script src="{{ asset('assets/js/init.js') }}" defer></script>--}}
</body>
</html>
