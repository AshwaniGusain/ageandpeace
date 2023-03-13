<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>{{ $page_title }}</title>
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! css('admin')->version() !!}
    {!! css('styles')->version()->links() !!}

    <script type="text/javascript">
        window.SNAP_CONFIG = {!! json_encode($js_config)!!};
    </script>

    {!! js('snap')->version() !!}
    {!! js('scripts')->version()->scripts(['defer' => 'defer']) !!}
    {!! js('admin')->version()->scripts(['defer' => 'defer']) !!}

</head>
<body>

<div id="snap-admin" :class="{'snap-preview-mode': previewMode, 'snap-menu-on': menu === true, 'snap-menu-off': menu === false}">
