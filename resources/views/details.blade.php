@extends('layouts.app')
@section('content')
    <style>
        .filled-heart{
            color: darkred;
        }
    </style>
    <main class="pt-90">
        <div class="mb-md-1 pb-md-3"></div>
        <section class="product-single container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="product-single__media" data-media-type="vertical-thumbnail">
                        <div class="product-single__image">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach(explode(",", $product->images) as $gImage)
                                        <div class="swiper-slide product-single__image-item">
                                            <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{$gImage}}" width="674"
                                                 height="674" alt="{{$product->name}}" />
                                            <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{$gImage}}" data-bs-toggle="tooltip"
                                               data-bs-placement="left" title="Zoom">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_zoom" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach


                                </div>
                                <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_prev_sm" />
                                    </svg></div>
                                <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_next_sm" />
                                    </svg></div>
                            </div>
                        </div>
                        <div class="product-single__thumbnail">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach(explode(",", $product->images) as $gImage)
                                    <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                                                                                              src="{{asset('uploads/products/thumbnails')}}/{{$gImage}}" width="104" height="104" alt="" /></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex justify-content-between mb-4 pb-md-2">
                        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                            <a href="{{route('home.index')}}" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                            <a href="{{route('shop.index')}}" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
                        </div><!-- /.breadcrumb -->


                    </div>
                    <h1 class="product-single__name">{{$product->name}}</h1>

                    <div class="product-single__price">
                        <span class="current-price">
                            @if($product->sale_price)
                                <s>{{$product->regular_price}}</s> $ {{$product->sale_price}}
                            @else
                                {{$product->regular_price}}
                            @endif
                        </span>
                    </div>
                    <div class="product-single__short-desc">
                        <p>{{$product->short_description}}</p>
                    </div>
                    @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                        <a href="{{route('cart.index')}}" class="btn btn-warning mb-3">Go To Cart</a>
                    @else
                    <form name="addtocart-form" method="post" action="{{route('cart.add')}}">
                        @csrf
                        <div class="product-single__addtocart">
                            <div class="qty-control position-relative">
                                <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                                <div class="qty-control__reduce">-</div>
                                <div class="qty-control__increase">+</div>
                            </div><!-- .qty-control -->
                            <input type="hidden" name="id" value="{{$product->id}}">
                            <input type="hidden" name="name" value="{{$product->name}}">
                            <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}">
                            <button type="submit" class="btn btn-primary btn-addtocart" data-aside="cartDrawer">Add to Cart</button>
                        </div>
                    </form>
                    @endif
                    <div class="product-single__addtolinks">
                        @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                            <form action="{{route('wishlist.remove')}}" method="POST" id="wishlistRemoveForm">
                                @csrf
                                <input type="hidden" name="rowId" value="{{Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId}}">
                            <a href="javascript:void(0);" class="menu-link menu-link_us-s add-to-wishlist filled-heart" onclick="document.getElementById('wishlistRemoveForm').submit()"><svg width="16" height="16" viewBox="0 0 20 20"
                                                                                                               fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_heart" />
                                </svg>&nbsp;<span>Remove From Wishlist</span></a>
                            </form>
                        @else
                            <form action="{{route('wishlist.add')}}" method="POST" id="wishlistForm">
                                @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <input type="hidden" name="name" value="{{$product->name}}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}">
                                <a href="javascript:void(0);" class="menu-link menu-link_us-s add-to-wishlist" onclick="document.getElementById('wishlistForm').submit()"><svg width="16" height="16" viewBox="0 0 20 20"
                                                                                                  fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_heart" />
                                    </svg>&nbsp;<span>Add to Wishlist</span></a>
                            </form>
                        @endif
                    </div>
                    <div class="product-single__meta-info">
                        <div class="meta-item">
                            <label>SKU:</label>
                            <span>{{$product->SKU}}</span>
                        </div>
                        <div class="meta-item">
                            <label>Categories:</label>
                            <span>{{$product->category->name}}</span>
                        </div>
                        <div class="meta-item">
                            <label>Tags:</label>
                            <span>NA</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-single__details-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
                           href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Description</a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
                         aria-labelledby="tab-description-tab">
                        <div class="product-single__description">
                            <h3 class="block-title mb-4">{{$product->description}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="products-carousel container">
            <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">Related <strong>Products</strong></h2>

            <div id="related_products" class="position-relative">
                <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
              "el": "#related_products .products-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": "#related_products .products-carousel__next",
              "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 14
              },
              "768": {
                "slidesPerView": 3,
                "slidesPerGroup": 3,
                "spaceBetween": 24
              },
              "992": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              }
            }
          }'>
                    <div class="swiper-wrapper">
                        @foreach($relatedProducts as $rProducts)
                            <div class="swiper-slide product-card">
                                <div class="pc__img-wrapper">
                                    <a href="{{route('shop.product.details', ['product_slug' => $rProducts->slug])}}">
                                        <img loading="lazy" src="{{asset('uploads/products')}}/{{$rProducts->image}}" width="330" height="400"
                                             alt="{{$rProducts->name}}" class="pc__img">
                                        @foreach(explode(",", $rProducts->images) as $gImage)
                                        <img loading="lazy" src="{{asset('uploads/products')}}/{{$gImage}}" width="330" height="400"
                                             alt="{{$rProducts->name}}" class="pc__img">
                                        @endforeach
                                    </a>
                                    @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                                        <a href="{{route('cart.index')}}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn btn-warning mb-3">Go To Cart</a>
                                    @else
                                        <form name="addtocart-form" method="post" action="{{route('cart.add')}}">
                                            @csrf
                                            <div class="qty-control position-relative">
                                                <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                                                <div class="qty-control__reduce">-</div>
                                                <div class="qty-control__increase">+</div>
                                            </div><!-- .qty-control -->
                                            <input type="hidden" name="id" value="{{$product->id}}">
                                            <input type="hidden" name="name" value="{{$product->name}}">
                                            <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}">
                                            <button type="submit" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium" data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="pc__info position-relative">
                                    <p class="pc__category">{{$rProducts->category->name}}</p>
                                    <h6 class="pc__title"><a href="{{route('shop.product.details', ['product_slug' => $product->slug])}}">{{$rProducts->name}}</a></h6>
                                    <div class="product-card__price d-flex">
                                        <span class="money price">
                                            @if($product->sale_price)
                                                <s>{{$product->regular_price}}</s> $ {{$product->sale_price}}
                                            @else
                                                {{$product->regular_price}}
                                            @endif
                                        </span>
                                    </div>


                                    @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
                                        <button type="submit" class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist filled-heart"
                                                title="Add To Wishlist">
                                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_heart" />
                                            </svg>
                                        </button>
                                    @else
                                        <form action="{{route('wishlist.add')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$product->id}}">
                                            <input type="hidden" name="name" value="{{$product->name}}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}">
                                            <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                                                    title="Add To Wishlist">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_heart" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div><!-- /.swiper-wrapper -->
                </div><!-- /.swiper-container js-swiper-slider -->

                <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_prev_md" />
                    </svg>
                </div><!-- /.products-carousel__prev -->
                <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_next_md" />
                    </svg>
                </div><!-- /.products-carousel__next -->

                <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
                <!-- /.products-pagination -->
            </div><!-- /.position-relative -->

        </section><!-- /.products-carousel container -->
    </main>

@endsection
