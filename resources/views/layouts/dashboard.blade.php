@extends('layouts.app', ['header' => 'top-only', 'dashboard' => true])

@section('content')
    <div class="dashboard">
        <div class="content">
            @yield('main')
        </div>

        <portal-target name="task-details" multiple></portal-target>
    </div>
@endsection
