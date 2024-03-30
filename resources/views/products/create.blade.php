<x-base-layout :scrollspy="true">

    <x-slot:pageTitle>
        {{-- {{ $title }} --}}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        @vite(['resources/scss/light/assets/components/tabs.scss'])
        @vite(['resources/scss/dark/assets/components/tabs.scss'])
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Product</li>
                <li class="breadcrumb-item"><a href="{{ route('product.list') }}">List of Products</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product.create') }}">Create Product</a></li>
            </ol>
        </nav>
    </div>


    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Product</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form method="POST"
                            action="{{ !empty($product_id) ? route('store-issue-note.update') : route('store-issue-note.save') }}"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <!-- <div class="form-row"> -->
                            <div class="col-md-12">
                                <label for="validationCustom01" class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Enter Product Name" required>
                                @error('name')
                                    <span style="color:red" class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="price">Purchase Price</label>
                                    <input type="text" name="price" class="form-control" id="price"
                                        placeholder="Enter the Purchase Price" required>
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- </div> -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="sale_price">Sale Price</label>
                                    <input type="text" name="sale_price" class="form-control" id="sale_price"
                                        placeholder="Enter the sale Price" required>
                                    @error('sale_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="exampleFormControlTextarea1">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit"
                                    class="mb-2 me-2 _effect--ripple waves-effect waves-light btn btn-primary"
                                    data-bs-container="body" data-bs-placement="right"
                                    data-bs-content="Tooltip on right">
                                    @if (!isset($noteId))
                                        Save
                                    @else
                                        Update
                                    @endif
                                </button>
                                <a href="{{ route('store-issue-note.list') }}"
                                    class="btn btn-dark mb-2 me-4 _effect--ripple waves-effect waves-light"
                                    type="submit">Cancel</a>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <Script>
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
    </Script>
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <!-- <x-slot:footerFiles>

    </x-slot> -->
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>
