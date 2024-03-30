<x-base-layout :scrollspy="false">

<x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        @vite(['resources/scss/light/assets/components/timeline.scss'])

        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chart of Inventory</li>
                        <li class="breadcrumb-item"><a href="{{ route('co-inventory-main-head.list') }}">List</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('co-inventory-main-head.create') }}">Create</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Main Head</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="simple-pill">

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div id="basic" class="col-lg-12">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-content widget-content-area">
                                            <div class="row">
                                                <div class="col-lg-12 col-12 ">
                                                    <form
                                                            action="{{ !empty($mainHead) ? route('co-inventory-main-head.update') : route('co-inventory-main-head.save') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($mainHead->id) ? $mainHead->id : '' }}" />
                                                        <div class="form-group">

                                                            <div class="col-lg-0 col-3 ">
                                                                <label for="code" class="form-label">Code</label>
                                                                <input id="code" type="text" name="code" readonly
                                                                       value="{{ @$mainHead ? '' : $accountCode  }} {{ old('code', !empty($mainHead->code) ? $mainHead->code : '') }}"
                                                                       class="form-control" required>
                                                            </div>
                                                            <br>
                                                            <div class="col-lg-0 col-12 ">
                                                                <label for="name" class="form-label">Main Head</label>
                                                                <input id="name" type="text" name="name"
                                                                    value="{{ old('name', !empty($mainHead->name) ? $mainHead->name : '') }}"
                                                                    placeholder="Please Enter Main Head "
                                                                    class="form-control" required>
                                                                @if($errors->has('name'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('name') }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <button type="submit"
                                                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                        data-bs-container="body"
                                                                        data-bs-placement="right"
                                                                        data-bs-content="Tooltip on right">
                                                                    @if (!isset($mainHead))
                                                                        Save
                                                                    @else
                                                                        Update
                                                                    @endif
                                                                </button>
                                                            @endif
                                                            <a href="{{ route('co-inventory-main-head.list') }}"
                                                               class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <x-slot:footerFiles>
            </x-slot>
</x-base-layout>
