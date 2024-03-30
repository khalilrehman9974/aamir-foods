<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
        </x-slot>
        <x-slot:headerFiles>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
                    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
                    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            @vite(['resources/scss/light/assets/components/timeline.scss'])
            @vite(['resources/scss/light/assets/components/accordions.scss'])
            @vite(['resources/scss/dark/assets/components/accordions.scss'])
{{--            @vite(['resources/scss/light/assets/elements/alert.scss'])--}}
{{--            @vite(['resources/scss/dark/assets/elements/alert.scss'])--}}
            <link rel="stylesheet" href="{{asset('plugins/filepond/filepond.min.css')}}">
            <link rel="stylesheet" href="{{asset('plugins/filepond/FilePondPluginImagePreview.min.css')}}">
            @vite(['resources/scss/light/plugins/filepond/custom-filepond.scss'])
            @vite(['resources/scss/dark/plugins/filepond/custom-filepond.scss'])
    </x-slot>
    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
        </x-slot>

        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chart of Accounts</li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Account</li>
                </ol>
            </nav>
        </div>


        <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <!-- <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Chart of Accounts</h4>
                            </div>
                        </div>
                    </div> -->
                <div class="widget-content widget-content-area">
                    <div class="simple-pill">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab" tabindex="0">
                                <div id="basic" class="col-lg-12">
                                    <div class="statbox widget box box-shadow">
                                        @if(session('error'))
                                            <div
                                                class="alert alert-light-danger alert-dismissible fade show border-0 mb-4"
                                                role="alert">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                         height="24" viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor" stroke-width="2"
                                                         stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-x close"
                                                         data-bs-dismiss="alert">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                                <strong>Error!</strong> {{ session('error') }} </div>
                                            {{--                                                    <div class="bg-red-500 text-white text-center text-xl m-4 p-4">{{ session('error') }}</div>--}}
                                        @endif
                                        <div class="widget-content widget-content-area">
                                            <div class="row">
                                                <div class="col-lg-10 col-12 ">
                                                    <form
                                                        action="{{ !empty($detailAccount) ? route('co-inventory-detail-account.update') : route('co-inventory-detail-account.save') }}"
                                                        method="POST" class="row g-3 needs-validation" enctype="multipart/form-data"
                                                        novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                               value="{{ isset($detailAccount->id) ? $detailAccount->id : '' }}"/>
                                                        <div class="form-group input-group ">
                                                            <div class="col-lg-0 col-12 form-group mb-4">
                                                                <label for="inputState" class="form-label">Main
                                                                    Head</label>
                                                                <select id="main-head" name="main_head"
                                                                        class="form-select" required>
                                                                    <option selected>Please select main head
                                                                    </option>
                                                                    @foreach($mainHeads as $index=>$value)
                                                                        <option
                                                                            value="{{ $index }}" {{ (old("main_head") == $index ? "selected":"") || (!empty($detailAccount->main_head) ? collect($detailAccount->main_head)->contains($index) : '') ? 'selected':'' }}>{{ $value }}</option>
                                                                    @endforeach
                                                                </select>

                                                                @if($errors->has('main_head'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('main_head') }}
                                                                    </div>
                                                                @endif

                                                            </div>
                                                            <br>
                                                            <div class="col-lg-0 col-12 form-group mb-4">
                                                                <label for="inputState" class="form-label">Sub Head</label>
                                                                @if(!empty($detailAccount))
                                                                    <select id="sub-head"
                                                                            name="sub_head"
                                                                            class="form-select" required>

                                                                        @foreach($subHeads as $key=>$value)
                                                                            <option
                                                                                value="{{ $key }}" {{!empty($detailAccount) && ($detailAccount->sub_head == $key) ? "selected" : ''}} {{($key == old('sub')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                @else
                                                                    <select id="sub-head"
                                                                            name="sub_head"
                                                                            class="form-select">
                                                                        @foreach($subHeads as $key=>$value)
                                                                            <option
                                                                                value="{{ $key }}" {{($key == old('sub_head')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                                @if($errors->has('sub_head'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('sub_head') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <br>
                                                            <div class="col-lg- 0 col-12 form-group mb-2">
                                                                <label for="code" class="form-label">
                                                                    Account Code</label>
                                                                <input id="code" type="text"
                                                                       name="code"
                                                                       value="{{ old('code', !empty($detailAccount->code) ? $detailAccount->code : '') }}"
                                                                       class="form-control" readonly>
                                                            </div>
                                                            <br>
                                                            <div class="col-lg-0 col-12 form-group mb-4">
                                                                <label for="name" class="form-label">
                                                                    Account Name </label>
                                                                <input id="name" type="text"
                                                                       name="name"
                                                                       value="{{ old('name', !empty($detailAccount->name) ? $detailAccount->name : '') }}"
                                                                       placeholder="Please Enter Detail Account "
                                                                       class="form-control" required>
                                                                @if($errors->has('name'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('name') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-lg-0 col-12 form-group mb-4">
                                                                <label for="name" class="form-label">
                                                                    Upload Product Image </label>

                                                            <div style='height: 0px;width: 0px; overflow:;'>
                                                                <input id="image" name="image" type="file" value="Upload" onchange="sub(this)" /></div>
                                                        </div>
                                                            <br>
                                                            <br>
                                                            <br>

                                                            @if(@$detailAccount)
                                                                <div class="col-lg-0 col-12 form-group mb-4">
                                                                <div class="media">
                                                                    <div class="avatar me-2">

                                                                    <img alt="avatar" @if($detailAccount->image == null || !file_exists(base_path('resources/images/inventory/')  . $detailAccount->image))
                                                                        src="{{ Vite::asset('resources/images/no-attachments.png')}}"

                                                                         @else
                                                                        src="{{ Vite::asset('resources/images/inventory/')  . $detailAccount->image}}"

                                                                     @endif

                                                                     class="rounded-circle" />
                                                                </div>
                                                                </div>
                                                                </div>
                                                            @endif

                                                            {{--                                                            <div class="control-group input-group">--}}
{{--                                                                <input type="file" name="file[]" class="form-control">&nbsp;&nbsp;--}}
{{--                                                                <div class="input-group-btn">--}}
{{--                                                                    <button class="btn btn-danger delete-attachment" type="button"><i--}}
{{--                                                                            class="glyphicon glyphicon-remove" disabled="disabled"></i>x--}}
{{--                                                                    </button>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                                <div class="col-md-6 mx-auto">--}}

{{--                                                                    <div class="multiple-file-upload">--}}

{{--                                                                        <input  onclick="document.getElementById('image').click()"--}}
{{--                                                                               class="file-upload-multiple"--}}
{{--                                                                               name="image1"--}}
{{--                                                                               id="image1">--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}

{{--                                                            <div class="col-md-6 mx-auto">--}}


{{--                                                                    <input type="file"--}}
{{--                                                                           class=""--}}
{{--                                                                           name="image1"--}}
{{--                                                                           id="image1"  onclick="getFile()>--}}
{{--                                                            </div>--}}



                                                            {{--                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)--}}
                                                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <div class="col-lg-0 col-12 form-group mb-4">

                                                                <button type="submit"
                                                                            class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                            data-bs-container="body"
                                                                            data-bs-placement="right"
                                                                            data-bs-content="Tooltip on right">
                                                                        @if (!isset($detailAccount))
                                                                            Save
                                                                        @else
                                                                            Update
                                                                        @endif
                                                                    </button>
                                                                @endif
                                                                <a href="{{ route('co-inventory-detail-account.list') }}"
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
        <x-slot:footerFiles>

            <script src="{{asset('plugins/filepond/filepond.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/FilePondPluginFileValidateType.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/FilePondPluginImageExifOrientation.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/FilePondPluginImagePreview.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/FilePondPluginImageCrop.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/FilePondPluginImageResize.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/FilePondPluginImageTransform.min.js')}}"></script>
            <script src="{{asset('plugins/filepond/filepondPluginFileValidateSize.min.js')}}"></script>
            <script src="{{asset('js/inventory-detail-account.js')}}"></script>

            <script>

                {{--singleFile.addFiles("{{Vite::asset('resources/images/drag-1.jpeg')}}");--}}
                {{--multifiles.addFiles("{{Vite::asset('resources/images/list-blog-style-2.jpeg')}}");--}}

                var config = {
                    routes: {
                        getSubHeads: "{{ url('co-inv-detail-account/get-sub-head-accounts') }}",
                        getDetailAccountCode: "{{ url('co-inv-detail-account/get-detail-account-code') }}",
                    },
                }

                // function getFile() {
                //     document.getElementById("image").click();
                // }
                //
                // function sub(obj) {
                //     var file = obj.value;
                //     var fileName = file.split("\\");
                //     document.getElementById("image").innerHTML = fileName[fileName.length - 1];
                //     // document.myForm.submit();
                //     // event.preventDefault();
                // }

            </script>

            </x-slot>
</x-base-layout>
