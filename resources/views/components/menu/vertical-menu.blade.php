{{--

/**
*
* Created a new component <x-menu.vertical-menu/>.
*
*/

--}}


<div class="sidebar-wrapper sidebar-theme">
    <div class="sidebar-wrapper sidebar-theme">

        <nav id="sidebar">

            <div class="navbar-nav theme-brand flex-row  text-center">
                <div class="nav-logo">
                    <div class="nav-item theme-logo">
                        <a href="{{getRouterValue();}}/home">
                            <img alt="image-404" src="{{asset('images/logo.png')}}" class="light-element theme-logo">
                        </a>
                    </div>
                    <div class="nav-item theme-text">
                        <a href="{{ getRouterValue() }}/home" class="nav-link"> Amir Foods </a>
                    </div>
                </div>
                <div class="nav-item sidebar-toggle">
                    <div class="btn-toggle sidebarCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevrons-left">
                            <polyline points="11 17 6 12 11 7"></polyline>
                            <polyline points="18 17 13 12 18 7"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
            @if (!Request::is('collapsible-menu/*'))
                <div class="profile-info">
                    <div class="user-info">
                        <div class="profile-img">
                            <img src="{{ Vite::asset('resources/images/profile-30.png') }}" alt="avatar">
                        </div>
                        <div class="profile-content">
                            <h6 class="">{{ \Illuminate\Support\Facades\Auth::user()->name ?? '' }}</h6>
                            <p class="">{{ \Illuminate\Support\Facades\Auth::user()->designation ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="shadow-bottom"></div>
            <ul class="list-unstyled menu-categories" id="accordionExample">
                <li class="menu {{ Request::is('*/dashboard/*') ? 'active' : '' }}">
                    <a href="#dashboard" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/dashboard/*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/dashboard/*') ? 'show' : '' }}"
                        id="dashboard" data-bs-parent="#accordionExample">
                        <li class="{{ Request::routeIs('analytics') ? 'active' : '' }}">
                            <a href="{{ getRouterValue() }}/dashboard/analytics"> Analytics </a>
                        </li>
                        <li class="{{ Request::routeIs('sales') ? 'active' : '' }}">
                            <a href="{{ getRouterValue() }}/dashboard/sales"> Sales </a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{ Request::is('*/app/chart-of-account/*') ? 'active' : '' }}">
                    <a href="#chart-of-account" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/app/chart-of-account/*') ? 'true' : 'false' }}"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Chart of Accounts</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/chart-of-account/*') ? 'show' : '' }}"
                        id="chart-of-account" data-bs-parent="#accordionExample">
                        <li class="{{ Request::routeIs('chart-of-account-main-head') ? 'active' : '' }}">
                            <a href="{{ route('main-head.list') }}"> Main Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-control-head') ? 'active' : '' }}">
                            <a href="{{ route('control-head.list') }}"> Control Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-sub-head') ? 'active' : '' }}">
                            <a href="{{ route('sub-head.list') }}"> Sub Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-sub-head') ? 'active' : '' }}">
                            <a href="{{ route('sub-sub-head.list') }}"> Sub-Sub Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-detail-account') ? 'active' : '' }}">
                            <a href="{{ route('detail-account.list') }}"> Detail Account </a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{ Request::is('*/app/invoice/*') ? 'active' : '' }}">
                    <a href="#invoice" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/app/invoice/*') ? 'true' : 'false' }}"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            </svg>
                            <span>Inventory</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/invoice/*') ? 'show' : '' }}"
                        id="invoice" data-bs-parent="#accordionExample">
                        <li class="">
                            <a href="{{ route('dispatch-note.list') }} "> Dispatch Note </a>
                        </li>
                        <li class="{{ Request::routeIs('invoice-preview') ? 'active' : '' }}">
                            <a href="{{ route('store-issue-note.list') }}"> Store Issue Note </a>
                        </li>
                        <li class="{{ Request::routeIs('invoice-preview') ? 'active' : '' }}">
                            <a href="{{ route('storeReturn.list') }}">Store return </a>
                        </li>
                        <li class="{{ Request::routeIs('invoice-preview') ? 'active' : '' }}">
                            <a href="{{ route('grn.list') }}">GRN </a>
                        </li>
                        <li class="{{ Request::routeIs('invoice-preview') ? 'active' : '' }}">
                            <a href="{{ route('purchase-order.list') }}">Purchase Order </a>
                        </li>
                        <li class="{{ Request::routeIs('invoice-preview') ? 'active' : '' }}">
                            <a href="{{ route('sale-order.list') }}">Sale Order </a>
                        </li>
                    </ul>
                </li>
                <li class="menu {{ Request::is('*/app/chart-of-inventory/*') ? "active" : "" }}">
                    <a href="#chart-of-inventory" data-bs-toggle="collapse"
                       aria-expanded="{{ Request::is('*/app/chart-of-inventory/*') ? "true" : "false" }}"
                       class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Chart of Inventory</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/chart-of-inventory/*') ? "show" : "" }}"
                        id="chart-of-inventory" data-bs-parent="#accordionExample">
                        <li class="{{ Request::routeIs('chart-of-account-main-head') ? 'active' : '' }}">
                            <a href="{{route('co-inventory-main-head.list')}}"> Main Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-control-head') ? 'active' : '' }}">
                            <a href="{{route('co-inventory-sub-head.list')}}"> Sub Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-control-head') ? 'active' : '' }}">
                            <a href="{{route('co-inventory-sub-sub-head.list')}}">Sub Sub Head </a>
                        </li>
                        <li class="{{ Request::routeIs('chart-of-account-detail-account') ? 'active' : '' }}">
                            <a href="{{ route('co-inventory-detail-account.list') }}"> Detail Account </a>
                        </li>
                    </ul>
                </li>
                <li class="menu {{ Request::is('*/app/management/*') ? 'active' : '' }}">
                    <a href="#management" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/app/management /*') ? 'true' : 'false' }}"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            </svg>
                            <span>Management</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/management/*') ? 'show' : '' }}"
                        id="management" data-bs-parent="#accordionExample">

                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('sector.list') }}"> Sector</a>
                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('area.list') }}"> Area</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('assignArea.list') }}">Assign Area</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('assignSector.list') }}">Assign Sector</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('saleMan.list') }}">Sale Man</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('business.list') }}">Business</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('users.list') }}">User</a>

                        </li>

                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('transporter.list') }}">Transporters</a>

                        </li>

                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('distributer.list') }}">Distributer</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('priceTag.list') }}">Price Tag</a>

                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('MeasurementType.list') }}">Measurement Type</a>
                        </li>
                        <li class="{{ Request::routeIs('management-preview') ? 'active' : '' }}">
                            <a href="{{ route('PackingType.list') }}">Packing Type</a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{ Request::is('*/app/sale/*') ? 'active' : '' }}">
                    <a href="#sale" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/app/sale /*') ? 'true' : 'false' }}"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            </svg>
                            <span>Sale </span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/sale/*') ? 'show' : '' }}"
                        id="sale" data-bs-parent="#accordionExample">

                        <li class="{{ Request::routeIs('sale-preview') ? 'active' : '' }}">
                            <a href="{{ route('sale.sales') }}">Sale Invoice</a>

                        </li>
                        <li class="{{ Request::routeIs('sale-preview') ? 'active' : '' }}">
                            <a href="{{ route('sale-return.sales-return') }}">Sale Return</a>

                        </li>
                    </ul>
                </li>

                <li class="menu {{ Request::is('*/app/purchase/*') ? 'active' : '' }}">
                    <a href="#purchase" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/app/purchase /*') ? 'true' : 'false' }}"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            </svg>
                            <span>Purchase </span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/purchase/*') ? 'show' : '' }}"
                        id="purchase" data-bs-parent="#accordionExample">

                        <li class="{{ Request::routeIs('purchase-preview') ? 'active' : '' }}">
                            <a href="{{ route('purchase.list') }}">Purchase</a>

                        </li>
                        <li class="{{ Request::routeIs('purchase-preview') ? 'active' : '' }}">
                            <a href="{{ route('purchase-return.list') }}">Purchase Return</a>
                        </li>
                    </ul>
                </li>

                <li class="menu {{ Request::is('*/app/voucher/*') ? 'active' : '' }}">
                    <a href="#voucher" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('*/app/voucher /*') ? 'true' : 'false' }}"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            </svg>
                            <span>Vouchers </span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ Request::is('*/app/voucher/*') ? 'show' : '' }}"
                        id="voucher" data-bs-parent="#accordionExample">

                        <li class="{{ Request::routeIs('voucher-preview') ? 'active' : '' }}">
                            <a href="{{ route('bpv.list') }}">BPV</a>

                        </li>
                        <li class="{{ Request::routeIs('voucher-preview') ? 'active' : '' }}">
                            <a href="{{ route('brv.list') }}">BRV</a>

                        </li>
                        <li class="{{ Request::routeIs('voucher-preview') ? 'active' : '' }}">
                            <a href="{{ route('cpv.list') }}">CPV</a>

                        </li>
                        <li class="{{ Request::routeIs('voucher-preview') ? 'active' : '' }}">
                            <a href="{{ route('crv.list') }}">CRV</a>

                        </li>
                        <li class="{{ Request::routeIs('voucher-preview') ? 'active' : '' }}">
                            <a href="{{ route('jv.list') }}">JV</a>

                        </li>
                    </ul>
                </li>
            </ul>


            </ul>
            </ul>
        </nav>


    </div>

</div>
