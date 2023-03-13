<div class="col-12 col-md-4 mb-5 mb-md-0">
    <div class="row align-items-center">
        @if(isset($image))
            <div class="col-12 col-sm-4 col-md-12">
                {{ $image }}
            </div>
        @endif

        <div class="col-12 col-sm-8 col-md-12">
            {{ $slot}}
        </div>
    </div>
</div>
