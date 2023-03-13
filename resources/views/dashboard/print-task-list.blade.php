<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

{{--favicons--}}
<!-- generics -->
    <link rel="shortcut icon" href="{{ asset('assets/favicons/favicon-32x32.png') }}">
    <link rel="icon" href="{{ asset('assets/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" href="{{ asset('assets/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/favicons/favicon-96x96.png') }}" sizes="96x96">

    <!-- Android -->
    <link rel="shortcut icon" sizes="192x192" href=“{{ asset('assets/favicons/android-icon-192x192.png') }}">

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

    <title>{{$title}} | {{ config('app.name', 'Laravel') }}</title>

    {{--OG--}}
    <meta property="og:url" content="{{Request::getRequestUri()}}" />
    <meta property="og:title" content="@yield('og:title', 'Age & Peace')">
    <meta property="og:type" content="@yield('og:type', 'website')" />
    <meta property="og:description" content="@yield('og:description', 'Age & Peace default og:meta description')">
    <meta property="og:image" content="@yield('og:image', 'http://placehold.it/1900X1000/42A76E/FFF?text=Age%20Peace')">

    <meta name="robots" content="noindex">

    {{-- Styles --}}
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" style="position: relative">
    <header>
        <div class="container text-center py-5 py-md-8">
            <a href="{{ url('/') }}">
                @svg('assets/svg/logo_header.svg', 'logo-header')

                @svg('assets/svg/logo_mobile.svg', 'logo-header-mobile')
            </a>
        </div>

        <div class="container">
            <h1>{{$title}}</h1>
            <hr class="my-1">
        </div>
    </header>

    <main>
        <div class="container position-relative">
            <table class="table table-borderless">
                <tr>
                    <th></th>
                    <th class="pl-0">Task</th>
                    <th width="150" class="text-right">Status</th>
                </tr>
                @foreach($tasks as $task)
                    <tr>
                        <td valign="top" ><div class="print-checklist-checkbox"></div></td>
                        <td valign="top" class="pl-0">{{$task->title}}</td>
                        <td valign="top" align="right" nowrap>
                            <b>
                                @if($task->completed)
                                    Completed on {{$task->completed_date->format('n/j/y')}}
                                @elseif($task->due_date)
                                    Due on {{$task->due_date->format('n/j/y')}}
                                @else
                                    No Due Date set
                                @endif
                            </b>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </main>

    <footer class="pt-8 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8">
                    <strong>©{{date('Y')}} Age and Peace | ageandpeace.com</strong>
                    <br>
                    <small>
                        Age and Peace strives to empower and guide everyone through the aging process by providing a trusted
                        foundation built around best thought content, a holistic set of preparation tasks, and a qualified list
                        of providers.
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script>
      (function() {
        print();
      })()
    </script>
</div>

</body>
</html>
