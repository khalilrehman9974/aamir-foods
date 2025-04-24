<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <x-slot:headerFiles>

        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <style>
            .tree-view ul {
                padding-left: 20px;
                border-left: 1px solid #ccc;
                margin-bottom: 0;
            }

            .tree-view li {
                margin: 5px 0;
                position: relative;
            }

            .tree-view li::before {
                content: '';
                position: absolute;
                top: 0;
                left: -12px;
                width: 10px;
                height: 100%;
                /* border-left: 1px solid #ccc; */
            }

            .tree-view li::after {
                content: '';
                position: absolute;
                top: 10px;
                left: -12px;
                width: 10px;
                height: 0;
                border-top: 1px solid #ccc;
            }
        </style>
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <div class="row layout-top-spacing">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg> ...
                    </svg></button>
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <div class="row">
                <div class="col-lg-0 col-6 ">
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('detail-account.treeView') }}">{{ $pageTitle }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row layout-top-spacing col-md-12">
        <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{ $pageTitle }}</h4>
                        </div>
                    </div>
                </div>
                {{-- <div class="widget-content widget-content-area">
                    <div class="tree-view">
                        <ul class="list-unstyled">
                            @foreach ($accounts as $main => $controls)
                                <li ><strong>{{ $main }}</strong>
                                    <ul>
                                        @foreach ($controls as $control => $subs)
                                            <li>{{ $control }}
                                                <ul>
                                                    @foreach ($subs as $sub => $subsubs)
                                                        <li>{{ $sub }}
                                                            <ul>
                                                                @foreach ($subsubs as $subsub => $details)
                                                                    <li>{{ $subsub }}
                                                                        <ul>
                                                                            @foreach ($details as $detail)
                                                                                <li>
                                                                                    {{ $detail->account_name }}
                                                                                    ({{ $detail->account_code }})
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                </div> --}}

                {{-- <div class="tree-view">
                    <ul class="list-unstyled">
                        @foreach ($accounts as $main => $controls)
                            <li><strong style="color: red;">{{ $main }}</strong>
                                <ul>
                                    @foreach ($controls as $control => $subs)
                                        <li><strong style="color: blue;">{{ $control }}</strong>
                                            <ul>
                                                @foreach ($subs as $sub => $subsubs)
                                                    <li><strong style="color: brown;">{{ $sub }}</strong>
                                                        <ul>
                                                            @foreach ($subsubs as $subsub => $details)
                                                                <li><strong style="color: orange;">{{ $subsub }}</strong>
                                                                    <ul>
                                                                        @foreach ($details as $detail)
                                                                            <li style="color: black;">
                                                                                {{ $detail->account_name }} ({{ $detail->account_code }})
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div> --}}

                <div class="tree-view">
                    <ul class="list-unstyled">
                        @foreach ($accounts as $main => $controls)
                            <li><h2 style="color: red;">{{ $main }}</h2>
                                <ul>
                                    @foreach ($controls as $control => $subs)
                                        <li><h3 style="color: blue;">{{ $control }}</h3>
                                            <ul>
                                                @foreach ($subs as $sub => $subsubs)
                                                    <li><h4 style="color: brown;">{{ $sub }}</h4>
                                                        <ul>
                                                            @foreach ($subsubs as $subsub => $details)
                                                                <li><h5 style="color: orange;">{{ $subsub }}</h5>
                                                                    <ul style="margin-left: 10% ! important">
                                                                        @foreach ($details as $detail)
                                                                            <li style="color: black;">
                                                                                {{ $detail->account_name }}
                                                                                {{-- ({{ $detail->account_code }}) --}}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        <script>
            var config = {
                routes: {
                    deleteMainHead: "{{ url('sale-order/delete') }}",
                },
            }
        </script>
    </x-slot>
</x-base-layout>
