<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>{{ $page_title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! css('admin')->version() !!}
    {!! css('styles')->version()->links() !!}

</head>
<body>
<main id="snap-admin" class="py-4">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @yield('body')
            </div>
        </div>
    </div>

</body>
</html>