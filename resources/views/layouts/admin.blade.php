<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="author" content="surfside media"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/animation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('font/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('icon/style.css')}}">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/favicon.ico')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}">

    <style>

        .notification{
            position: absolute;
            z-index: 5;
            top: -2px;
            right: 0px;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            font-weight: 500;
            line-height: 17px;
            border-radius: 999px;
            background: #2275fc;
            color: #fff;
        }

        .notification::after{
            position: absolute;
            content: "";
            top: 0;
            right: 0;
            width: 16px;
            height: 16px;
            background-color: #2275fc;
            border-radius: 50%;
            z-index: -1;
            animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        .product-item{
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 15px;
            cursor: pointer;
            padding: 10px;
            transition: all 0.3s ease;
            padding-right: 5px;
        }

        .product-item .image{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            gap: 10px;
            flex-shrink: 0;
            padding: 5px;
            border-radius: 10px;
            background-color: #EFF4F8;
        }

        #box-content-search li{
            list-style: none;
        }

        #box-content-search .product-item{
            margin-bottom: 10px;
        }
    </style>
    @stack("styles")
</head>
<body class="body">
<div id="wrapper">
    <div id="page" class="">
        <div class="layout-wrap">

            <!-- <div id="preload" class="preload-container">
<div class="preloading">
    <span></span>
</div>
</div> -->

            <div class="section-menu-left">
                <div class="box-logo">
                    <a href="{{route('admin.index')}}" id="site-logo-inner">
                        <img class="" id="logo_header_mobile" alt="" src="{{asset('images/logo/logo.png')}}"
                             data-light="{{asset('images/logo/logo.png')}}" data-dark="{{asset('images/logo/logo.png')}}"
                             data-width="154px" data-height="52px" data-retina="{{asset('images/logo/logo.png')}}">
                    </a>
                    <div class="button-show-hide">
                        <i class="icon-menu-left"></i>
                    </div>
                </div>
                <div class="center">
                    <div class="center-item">
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="{{route('admin.index')}}" class="">
                                    <div class="icon"><i class="icon-grid"></i></div>
                                    <div class="text">Dashboard</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="center-item">
                        <ul class="menu-list">
                            <li class="menu-item has-children">
                                <a href="javascript:void(0);" class="menu-item-button">
                                    <div class="icon"><i class="icon-shopping-cart"></i></div>
                                    <div class="text">Products</div>
                                </a>
                                <ul class="sub-menu">
                                    <li class="sub-menu-item">
                                        <a href="{{route('admin.product-add')}}" class="">
                                            <div class="text">Add Product</div>
                                        </a>
                                    </li>
                                    <li class="sub-menu-item">
                                        <a href="{{route('admin.products')}}" class="">
                                            <div class="text">Products</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item has-children">
                                <a href="javascript:void(0);" class="menu-item-button">
                                    <div class="icon"><i class="icon-layers"></i></div>
                                    <div class="text">Brand</div>
                                </a>
                                <ul class="sub-menu">
                                    <li class="sub-menu-item">
                                        <a href="{{route('admin.brand-add')}}" class="">
                                            <div class="text">New Brand</div>
                                        </a>
                                    </li>
                                    <li class="sub-menu-item">
                                        <a href="{{route('admin.brands')}}" class="">
                                            <div class="text">Brands</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item has-children">
                                <a href="javascript:void(0);" class="menu-item-button">
                                    <div class="icon"><i class="icon-layers"></i></div>
                                    <div class="text">Category</div>
                                </a>
                                <ul class="sub-menu">
                                    <li class="sub-menu-item">
                                        <a href="{{route('admin.category-add')}}" class="">
                                            <div class="text">New Category</div>
                                        </a>
                                    </li>
                                    <li class="sub-menu-item">
                                        <a href="{{route('admin.categories')}}" class="">
                                            <div class="text">Categories</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="menu-item ">
                                <a href="{{route('admin.orders')}}" class="menu-item-button">
                                    <div class="icon"><i class="icon-file-plus"></i></div>
                                    <div class="text">Orders</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{route('admin.slides')}}" class="">
                                    <div class="icon"><i class="icon-image"></i></div>
                                    <div class="text">Slider</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{route('admin.coupons')}}" class="">
                                    <div class="icon"><i class="icon-grid"></i></div>
                                    <div class="text">Coupons</div>
                                </a>
                            </li>


                            <li class="menu-item position-relative">
                                <a href="{{route('admin.contact')}}" class="">
                                    <div class="icon "><i class="icon-message-square"></i></div>
                                    <div class="text">Messages</div>
                                    <!--text-white bg-danger end-0 top-0 p-2 fw-bold fs-5 position-absolute rounded-circle text-center-->
                                    @if(\App\Models\Contact::where('is_read',0)->count() > 0)
                                        <span class="notification text-tiny" style="">
                                            {{\App\Models\Contact::where('is_read',0)->count()}}
                                        </span>
                                    @endif
                                </a>
                            </li>



                            <li class="menu-item">
                                <form action="{{route('logout')}}" method="POST" id="logout-form">
                                    @csrf
                                <a href="{{route('logout')}}" class="" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <div class="icon"><i class="icon-log-out"></i></div>
                                    <div class="text">Logout</div>
                                </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="section-content-right">

                <div class="header-dashboard">
                    <div class="wrap">
                        <div class="header-left">
                            <a href="{{route('admin.index')}}">
                                <img class="" id="logo_header_mobile" alt="" src="{{asset('images/logo/logo.png')}}"
                                     data-light="{{asset('images/logo/logo.png')}}" data-dark="{{asset('images/logo/logo.png')}}"
                                     data-width="154px" data-height="52px" data-retina="{{asset('images/logo/logo.png')}}">
                            </a>
                            <div class="button-show-hide">
                                <i class="icon-menu-left"></i>
                            </div>


                            <form class="form-search flex-grow">
                                <fieldset class="name">
                                    <input type="text" placeholder="Search here..." id="search-input" autocomplete="off" class="show-search" name="name"
                                           tabindex="2" value="" aria-required="true" required="">
                                </fieldset>
                                <div class="button-submit">
                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                </div>
                                <div class="box-content-search" id="box-content-search">


                                </div>
                            </form>

                        </div>

                    </div>
                </div>
                <div class="main-content">
                    @yield('content')



                    <div class="bottom-page">
                        <div class="body-text">Copyright © 2024 Kaan Uçarcı</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script src="{{asset('js/apexcharts/apexcharts.js')}}"></script>
<script>
    $(function () {
        $('#search-input').on('keyup', function (){
            var searchQuery = $(this).val().trim();
            var searchURL = "{{route('admin.search')}}";
            if(searchQuery.length > 2){
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.search')}}",
                    data: {query : searchQuery},
                    dataType: 'json',
                    success:function (data){
                        var searchDiv = $('#box-content-search');
                        searchDiv.html('');

                        if(data.length > 0)
                        {
                            $.each(data, function (index, item){
                                var url = "{{route('admin.product.edit', ['id' => 'product_id_pls'])}}";
                                var link = url.replace('product_id_pls', item.id);

                                searchDiv.append(`
                                   <li>
                                       <ul>
                                        <a href="${link}" class="body-text">
                                           <li class="product-item gap14 mb-10">
                                               <div class="image no-bg">
                                                    <img src="{{asset('uploads/products/thumbnails')}}/${item.image}" alt="${item.name}">
                                               </div>
                                               <div class="flex items-center justify-between gap20 flex-grow">
                                                   <div class="name">
                                                     ${item.name}
                                                   </div>
                                               </div>
                                           </li>
                                        </a>
                                           <li class="mb-10">
                                                <div class="divider"></div>
                                           </li>
                                       </ul>
                                   </li>
                               `);

                            });
                        }
                        else
                        {
                            searchDiv.append(`<span class="text-center fw-bold fs-4 text-secondary">No Results Found</span>`);
                        }
                    }
                });
            }
        })
    });
</script>
<script src="{{asset('js/main.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    @stack("scripts")
</body>

</html>
