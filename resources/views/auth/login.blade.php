<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{-- {{$pageTitle}} --}}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        @vite(['resources/scss/light/assets/authentication/auth-cover.scss'])
        @vite(['resources/scss/dark/assets/authentication/auth-cover.scss'])

        <style>
            #load_screen {
                display: none;
            }
        </style>
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
            <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation" novalidate>
                @csrf
                <div class="row">

                    <div
                        class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
                        <div class="auth-cover-bg-image"></div>
                        <div class=""></div>

                        <div class="auth-cover">

                            <div class="position-relative">

                                <img src="{{ asset('images/logo.png') }}" alt="auth-img">

                                {{--<h2 class="mt-5 text-white font-weight-bolder px-2">Join the community of expert developers</h2> --}}
                                {{--<p class="text-white px-2">It is easy to setup with great customer experience. Start your 7-day free trial</p> --}}
                            </div>

                        </div>

                    </div>

                    <div
                        class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                        <div class="card mt-3 mb-3">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12 mb-3">

                                        <h2>Sign In</h2>
                                        <p>Enter your email and password to login</p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label">Password</label>
                                                <input type="text" name="password" class="form-control">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label">Business</label>
                                                <select class="form-control" id="business_id"  name="business_id">
                                                    @foreach (Cache::get('businesses') as $id => $business)
                                                        <option value="{{ $id }}">{{ $business }}</option>
                                                    @endforeach
                                                </select>
                                                @error('business_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label">Financial Year</label>
                                                <select class="form-control"  id="financial_year"  name="financial_year">
                                                    @foreach (Cache::get('financialYears') as $id => $year)
                                                        <option value="{{ $id }}">{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                                @error('f_year_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <div class="form-check form-check-primary form-check-inline">
                                                    <input class="form-check-input me-3" type="checkbox"
                                                        id="form-check-default">
                                                    <label class="form-check-label" for="form-check-default">
                                                        Remember me
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-4">
                                                <button class="btn btn-secondary w-100">SIGN IN</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    </script>
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>

    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>
