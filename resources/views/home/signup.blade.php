<section class="signup pt-8 pb-3" id="signup">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h3>Start living with peace of mind</h3>
                <p>
                    This is a great place to start, whatever your situation. You’ll sign up to get a custom checklist, resources and guidance for whatever you’re dealing with now, and in the future. You are entitled to freedom and well-being at every age, and we’re here to ensure you get it.
                </p>
            </div>

            <div class="col-12 col-md-6">
                <form action="{{ route('customer.invite-post') }}" method="post" class="p-4 p-lg-7 bg-white shadow">
                    @csrf

                    <div class="form-group form-row align-items-center">
                        <label for="email" class="col-sm-3 p-0 col-form-label">Email</label>

                        <div class="col-sm-9 pr-0">
                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   name="email" value="{{ old('email') }}" placeholder="Enter your email to receive an invite" required>
                        </div>
                    </div>

                    <div class="d-none">
                        <label>A Random Field</label>
                        <input type="text" name="random" id="random" />
                    </div>

                    <input type="hidden" id="role" name="role" value="customer">

                    <div class="form-row justify-content-end">
                        <div class="col-sm-9 pr-0">
                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
