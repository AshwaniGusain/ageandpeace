<div class="col-12 col-sm-6 col-md-4 mb-4 mb-lg-0 {{$class ?? ''}}">
    <div class="row align-items-center">
        @if(isset($image))
            <div class="col-12 mb-2">
                <div class="image-container text-center">
                    {{ $image }}
                </div>
            </div>
        @endif

        <div class="col-12">
            {{ $slot}}
        </div>
    </div>
</div>
