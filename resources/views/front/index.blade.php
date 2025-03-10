@extends('front.fixe')
@section('titre', 'Accueil')
@section('body')
    <main>
        @php
            $config = DB::table('configs')->first();
            $service = DB::table('services')->get();
            $produit = DB::table('produits')->get();
        @endphp


        <main class="main-wrapper">
            <style>
                .carousel-item img {
                    height: 600px;
                    /* Ajuste la hauteur selon ton besoin */
                    object-fit: cover;
                    /* Assure un bon recadrage sans déformation */

                }

                /* Tablettes */
                @media (max-width: 992px) {
                    .carousel-item img {
                        height: 400px;
                        /* Réduire la hauteur sur tablette */
                    }
                }

                /* Mobiles */
                @media (max-width: 768px) {
                    .carousel-item img {
                        height: 300px;
                        /* Hauteur plus petite pour mobile */
                    }
                }


                .main-slider-content {
                    text-align: left;
                    /* Aligner le texte à gauche */
                    margin-left: 0;
                    /* Réduire tout espace à gauche */
                    padding-left: 20px;
                    /* Ajouter un peu d'espace si nécessaire */
                }

                .shop-btn {
                    text-align: left;
                    /* Aligner le bouton à gauche également */
                }
            </style>

            <div class="container-fluid px-0 mb-5">
                <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($banners as $key => $banner)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img class="d-block w-100" src="{{ Storage::url($banner->image) }}" alt="Image">


                                <div class="carousel-caption  d-md-block">
                                    <div class="container">

                                        <div class="main-slider-content">
                                            <span class="subtitle"style="font-size: 3rem; color: #ffffff"><i
                                                    class="fas fa-fire"></i>

                                                {{ \App\Helpers\TranslationHelper::TranslateText($banner->titre ?? ' ') }}
                                            </span>
                                            <p style="font-size: 3rem; color: #ffffff;  margin-top: 10px; ">

                                                {{ \App\Helpers\TranslationHelper::TranslateText($banner->sous_titre ?? ' ') }}
                                            </p>

                                        </div>
                                        <br>
                                        <div class="shop-btn d-flex justify-content-center">
                                            <a href="{{ route('shop') }}" class="axil-btn btn-bg-primary2 right-icon">

                                                {{ \App\Helpers\TranslationHelper::TranslateText('Découvrir la collection') }}
                                                <i class="fal fa-long-arrow-right"></i>
                                            </a>
                                        </div>




                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>


            <!-- Start Axil Product Poster Area  -->
            <div class="axil-poster axil-section-gap pb--0">
                <div class="container">
                    <div class="row g-lg-5 g-4">
                        <div class="col-lg-6">
                            <div class="single-poster">
                                <a href="{{ url('promotion') }}">
                                    <img src="{{ Storage::url($config->image_promo) }}" alt="">
                                    <div class="poster-content">
                                        <div class="inner">
                                            <h3 class="title">
                                                {{ \App\Helpers\TranslationHelper::TranslateText($config->titre_promo ?? ' ') }}
                                            </h3>
                                            <span class="sub-title">
                                                {{ \App\Helpers\TranslationHelper::TranslateText($config->description_promo ?? ' ') }}
                                                <i class="fal fa-long-arrow-right"></i></span>
                                        </div>
                                    </div>
                                    <!-- End .poster-content -->
                                </a>
                            </div>
                            <!-- End .single-poster -->
                        </div>
                        <div class="col-lg-6">
                            <div class="single-poster">
                                <a href="{{ url('promotion') }}">
                                    <img src="{{ Storage::url($config->image_promo1) }}" alt="">
                                    <div class="poster-content content-left">
                                        <div class="inner">
                                            <span class="sub-title">
                                                {{ \App\Helpers\TranslationHelper::TranslateText($config->titre_promo1 ?? ' ') }}</span>
                                            <h3 class="title">
                                                {{ \App\Helpers\TranslationHelper::TranslateText($config->description_promo1 ?? ' ') }}
                                            </h3>
                                        </div>
                                    </div>
                                    <!-- End .poster-content -->
                                </a>
                            </div>
                            <!-- End .single-poster -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Axil Product Poster Area  -->



            <div class="axil-best-seller-product-area bg-color-white axil-section-gap pb--50 pb_sm--30">
                <div class="container">
                    <div class="section-title-wrapper">
                        <span class="title-highlighter highlighter-secondary"><i class="far fa-shopping-basket"></i>This
                            Month</span>
                        <h2 class="title">Les nouveatés</h2>
                    </div>
                    <div
                        class="new-arrivals-product-activation-2 slick-layout-wrapper--15 axil-slick-arrow arrow-top-slide product-slide-mobile">
                        @foreach ($rescentesproduits as $produit)
                            <div class="slick-single-layout">
                                <div class="axil-product product-style-three">
                                    <div class="thumbnail">
                                        <a
                                            href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                            <img style="border-radius: 8px; width: 300px; height: 200px; object-fit: cover;"
                                                data-sal="zoom-out" data-sal-delay="200" data-sal-duration="800"
                                                loading="lazy" class="main-img" border-radius="8px"
                                                src="{{ Storage::url($produit->photo) }}" alt="Product Images">
                                            <img style="border-radius: 8px; width: 300px; height: 200px; object-fit: cover;"
                                                class="hover-img" border-radius="8px"
                                                src="{{ Storage::url($produit->photo) }}" alt="Product Images">
                                        </a>

                                        <style>
                                            .top-left {
                                                position: absolute;
                                                top: 8px;
                                                right: 18px;
                                                color: #EFB121;
                                            }
                                        </style>

                                        <div class="top-left" style="background-color: #EFB121;color: white;">
                                            <span>
                                                @if ($produit->inPromotion())
                                                    <span>
                                                        -{{ $produit->inPromotion()->pourcentage }}%</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="product-hover-action">
                                            <ul class="cart-action">
                                                {{-- <li class="quickview"><a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#{{ $produit->id }}"><i class="far fa-eye"></i></a></li> --}}
                                                <li class="select-option2">
                                                    <a onclick="AddToCart( {{ $produit->id }} )">
                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Ajouter au panier') }}
                                                    </a>
                                                </li>


                                                @if (Auth()->user())
                                                    @php

                                                        $count = DB::table('favoris')
                                                            ->where('id_user', Auth()->user()->id)
                                                            ->where('id_produit', $produit->id)
                                                            ->count();
                                                    @endphp


                                                    <li class="wishlist"><a onclick="AddFavoris({{ $produit->id }})"
                                                            @if ($count == 0) class="" style="color:#000000" @else class="" style="color: #dc3545; background-color:#dc3545" @endif>

                                                            <i class="far fa-heart"></i></a></li>
                                                @endif



                                                <style>
                                                    .select-option2 {
                                                        background-color: #5EA13C;
                                                        color: #ffffff;
                                                        border: none;
                                                        padding: 10px 20px;
                                                        border-radius: 5px;
                                                        text-decoration: none;
                                                    }

                                                    .favori-actif {
                                                        color: red;
                                                        /* Changez la couleur selon votre besoin */
                                                    }
                                                </style>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <div class="inner">
                                            <div class="product-rating">

                                            </div>

                                            <div class="">
                                                <h5 class="title"><a
                                                        href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                        {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->nom, 15)) }}

                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="product__price__wrapper">
                                                <h6 class="product-price--main">


                                                    @if ($produit->inPromotion())
                                                        <div class="row">
                                                            <div class="col-sm-6 col-6">

                                                                <b class="text-success" style="color: #4169E1">
                                                                    {{ $produit->getPrice() }} <x-devise></x-devise>
                                                                </b>
                                                            </div>

                                                            <div class="col-sm-6 col-6 text-end">
                                                                <strike>


                                                                    <span
                                                                        style="font-size: 1.7rem; color: #dc3545; font-weight: bold;">
                                                                        {{ $produit->prix }} <x-devise></x-devise>
                                                                    </span>


                                                                </strike>
                                                            </div>
                                                        @else
                                                            {{-- {{ $produit->getPrice() }}DT --}}


                                                            <span class="price current-price" style="font-size: 1.7rem;">
                                                                {{ $produit->getPrice() }} <x-devise></x-devise>
                                                                </b></span>
                                                    @endif


                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- End Slider Area -->
            <!-- Start Categorie Area  -->
            <!-- Start Categorie Area  -->
            <div class="axil-categorie-area bg-color-white axil-section-gapcommon">
                <div class="container">
                    <div class="section-title-wrapper">
                        <h4> <span class="axil-breadcrumb-item1 active" aria-current="page"> <i class="far fa-tags"></i>
                                {{ \App\Helpers\TranslationHelper::TranslateText('Categories') }}</span> </h4>
                        <h1 class="title">
                            {{ \App\Helpers\TranslationHelper::TranslateText('Nos categories') }}
                        </h1>
                    </div>
                    <div class="categrie-product-activation slick-layout-wrapper--15 axil-slick-arrow  arrow-top-slide">
                        <style>

                        </style>
                        @foreach ($categories as $category)
                            <div class="slick-single-layout">
                                <div class="categrie-product" data-sal="zoom-out" data-sal-delay="200"
                                    data-sal-duration="500">
                                    <a href="/category/{{ $category->id }}"
                                        class="{{ isset($current_category) && $current_category->id === $category->id ? 'selected' : '' }}">
                                        <img src="{{ Storage::url($category->photo) }}"
                                            class="rounded shadow fixed-image" alt="product categorie">

                                        <h6 class="cat-title">
                                            {{ \App\Helpers\TranslationHelper::TranslateText($category->nom ?? '') }}
                                        </h6>
                                    </a>
                                </div>
                                <!-- End .categrie-product -->
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>


            <div class="axil-product-area bg-color-white axil-section-gap">
                <div class="container">
                    <div class="section-title-wrapper">

                        <h4> <span class="axil-breadcrumb-item1 active" aria-current="page"> <i
                                    class="far fa-shopping-basket"></i>
                                {{ \App\Helpers\TranslationHelper::TranslateText('Nos produits') }}</span> </h4>

                        <h1 class="title">
                            {{ \App\Helpers\TranslationHelper::TranslateText('Parcourir nos produits ') }}
                        </h1>
                    </div>
                    <div
                        class="explore-product-activation slick-layout-wrapper slick-layout-wrapper--15 axil-slick-arrow arrow-top-slide">
                        <div class="slick-single-layout">
                            <div class="row row--15">
                                @foreach ($produits as $produit)
                                    @if ($produit)
                                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb--30">
                                            <div class="axil-product product-style-one">
                                                <div class="thumbnail">
                                                    <a
                                                        href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                        <img style="border-radius: 8px; width: 300px; height: 200px; object-fit: cover;"
                                                            data-sal="zoom-out" data-sal-delay="200"
                                                            data-sal-duration="800" loading="lazy" class="main-img"
                                                            border-radius="8px" src="{{ Storage::url($produit->photo) }}"
                                                            alt="Product Images">
                                                        <img style="border-radius: 8px; width: 300px; height: 200px; object-fit: cover;"
                                                            class="hover-img" border-radius="8px"
                                                            src="{{ Storage::url($produit->photo) }}"
                                                            alt="Product Images">
                                                    </a>

                                                    <style>
                                                        .top-left {
                                                            position: absolute;
                                                            top: 8px;
                                                            right: 18px;
                                                            color: #EFB121;
                                                        }
                                                    </style>

                                                    <div class="top-left" style="background-color: #EFB121;color: white;">
                                                        <span>
                                                            @if ($produit->inPromotion())
                                                                <span>
                                                                    -{{ $produit->inPromotion()->pourcentage }}%</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="product-hover-action">
                                                        <ul class="cart-action">
                                                            {{-- <li class="quickview"><a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#{{ $produit->id }}"><i class="far fa-eye"></i></a></li> --}}
                                                            <li class="select-option2">
                                                                <a onclick="AddToCart( {{ $produit->id }} )">
                                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Ajouter au panier') }}
                                                                </a>
                                                            </li>


                                                            @if (Auth()->user())
                                                                @php

                                                                    $count = DB::table('favoris')
                                                                        ->where('id_user', Auth()->user()->id)
                                                                        ->where('id_produit', $produit->id)
                                                                        ->count();
                                                                @endphp


                                                                <li class="wishlist"><a
                                                                        onclick="AddFavoris({{ $produit->id }})"
                                                                        @if ($count == 0) class="" style="color:#000000" @else class="" style="color: #dc3545; background-color:#dc3545" @endif>

                                                                        <i class="far fa-heart"></i></a></li>
                                                            @endif



                                                            <style>
                                                                .select-option2 {
                                                                    background-color: #5EA13C;
                                                                    color: #ffffff;
                                                                    border: none;
                                                                    padding: 10px 20px;
                                                                    border-radius: 5px;
                                                                    text-decoration: none;
                                                                }

                                                                .favori-actif {
                                                                    color: red;
                                                                    /* Changez la couleur selon votre besoin */
                                                                }
                                                            </style>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <div class="inner">
                                                        <div class="product-rating">

                                                        </div>

                                                        <div class="">
                                                            <h5 class="title"><a
                                                                    href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                                    {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->nom, 15)) }}

                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div class="product__price__wrapper">
                                                            <h6 class="product-price--main">


                                                                @if ($produit->inPromotion())
                                                                    <div class="row">
                                                                        <div class="col-sm-6 col-6">

                                                                            <b class="text-success"
                                                                                style="color: #4169E1">
                                                                                {{ $produit->getPrice() }}
                                                                                <x-devise></x-devise>
                                                                            </b>
                                                                        </div>

                                                                        <div class="col-sm-6 col-6 text-end">
                                                                            <strike>


                                                                                <span
                                                                                    style="font-size: 1.7rem; color: #dc3545; font-weight: bold;">
                                                                                    {{ $produit->prix }}
                                                                                    <x-devise></x-devise>
                                                                                </span>


                                                                            </strike>
                                                                        </div>
                                                                    @else
                                                                        {{-- {{ $produit->getPrice() }}DT --}}


                                                                        <span class="price current-price"
                                                                            style="font-size: 1.7rem;">
                                                                            {{ $produit->getPrice() }}
                                                                            <x-devise></x-devise>
                                                                            </b></span>
                                                                @endif


                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center mt--20 mt_sm--0">
                            <a href="{{ route('shop') }}" class="axil-btn btn-bg-primary2 btn-load-more">

                                {{ \App\Helpers\TranslationHelper::TranslateText('Voir tous les produits') }}
                            </a>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Product Quick View Modal Start -->
            @if ($produits)
                @foreach ($produits as $key => $produit)
                    <div class="modal fade quick-view-product" id="{{ $produit->id }}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="far fa-times"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="single-product-thumb">
                                        <div class="row">
                                            <div class="col-lg-7 mb--40">
                                                {{-- <div class="col-lg-6"> --}}
                                                <div class="shop-details-img">
                                                    <div class="tab-content" id="v-pills-tabContent">

                                                        <div class="shop-details-tab-img product-img--main"
                                                            id="zoomContaine" data-scale="1.4"
                                                            style="overflow: hidden; position: relative;">

                                                            <img id="mainImage" src="{{ Storage::url($produit->photo) }}"
                                                                height="600" width="600" alt="Product image"
                                                                style="transition: transform 0.3s ease;" />
                                                        </div>


                                                    </div>
                                                    <br><br>

                                                    <div class="nav nav-pills" id="v-pills-tab" role="tablist"
                                                        aria-orientation="vertical">
                                                        @foreach (json_decode($produit->photos) ?? [] as $image)
                                                            <div class="slider__item">
                                                                <img onclick="changeMainImage('{{ Storage::url($image) }}')"
                                                                    src="{{ Storage::url($image) }}" width="100"
                                                                    height="100" style="border-radius: 8px;"
                                                                    alt="Additional product image" />
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <script>
                                                    function changeMainImage(imageUrl) {
                                                        document.getElementById('mainImage').src = imageUrl;
                                                    }
                                                </script>

                                                <script>
                                                    const zoomContaine = document.getElementById('zoomContaine');
                                                    const mainImage = document.getElementById('mainImage');
                                                    const scale = zoomContaine.getAttribute('data-scale') || 1.4;


                                                    zoomContaine.addEventListener('mouseover', function() {
                                                        mainImage.style.transform = `scale(${scale})`;
                                                        mainImage.style.cursor = "zoom-in";
                                                    });


                                                    zoomContaine.addEventListener('mouseout', function() {
                                                        mainImage.style.transform = "scale(1)";
                                                    });


                                                    function changeMainImage(imageUrl) {
                                                        mainImage.src = imageUrl;
                                                        mainImage.style.transform = "scale(1)";
                                                    }
                                                </script>




                                            </div>

                                            <div class="col-lg-5 mb--40">
                                                <div class="single-product-content">
                                                    <div class="inner">

                                                        <h3 class="product-title">{{ $produit->nom }}</h3>
                                                        <span class="price-amount">
                                                            @if ($produit->inPromotion())
                                                                <b class="text-success" style="color: #4169E1">
                                                                    {{ $produit->getPrice() }} <x-devise></x-devise>
                                                                </b>

                                                                <span
                                                                    style="position: relative; font-size: 1.5rem; color: #dc3545; font-weight: bold;">
                                                                    {{ $produit->prix }} <x-devise></x-devise>
                                                                    <span
                                                                        style="position: absolute; top: 50%; left: 0; width: 100%; height: 2px; background-color: black;"></span>
                                                                </span>
                                                            @else
                                                                {{ $produit->getPrice() }} <x-devise></x-devise>
                                                            @endif
                                                        </span>
                                                        <ul class="product-meta">
                                                            @if ($produit->stock > 0)
                                                                <label class="badge btn-bg-primary2"> Stock
                                                                    disponible</label>
                                                            @else
                                                                <label class="badge bg-danger"> Stock non
                                                                    disponible</label>
                                                            @endif

                                                            <li>Categorie:<span>
                                                                    {{ Str::limit($produit->categories->nom, 30) }}</span>
                                                            </li>
                                                            <li> <span>Reference:</span> {{ $produit->reference }}</li>
                                                        </ul>
                                                        <p class="description">{!! $produit->description !!}</p>

                                                        <div class="product-variations-wrapper">


                                                        </div>


                                                        <div class="product-action-wrapper d-flex-center">

                                                            <div class="pro-qty">
                                                                <span class="quantity-control minus"></span>
                                                                <input type="number" class="input-text qty text"
                                                                    name="quantite" min="1" value="1"
                                                                    id="qte-{{ $produit->id }}" autocomplete="off">
                                                                <span class="quantity-control plus"></i></span>
                                                            </div>

                                                            <ul class="product-action d-flex-center mb--0">
                                                                <li class="add-to-cart"><a
                                                                        onclick="AddToCart( {{ $produit->id }} )"
                                                                        class="axil-btn btn-bg-primary2">Ajouter au
                                                                        panier</a></li>
                                                                @if (Auth()->user())
                                                                    <li class="wishlist"><a
                                                                            onclick="AddFavoris({{ $produit->id }})"><i
                                                                                class="far fa-heart"></i></a></li>
                                                                @endif
                                                            </ul>
                                                            <!-- End Product Action  -->

                                                        </div>
                                                        <!-- End Product Action Wrapper  -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <br><br>

            <!-- Start About Area  -->
            <div class="about-info-area">
                <div class="container">
                    <div class="row row--20">
                        <div class="col-lg-4">
                            <div class="about-info-box">
                                <div class="thumb image-center">
                                    <img src="{{ Storage::url($config->icone_satisfaction ?? '') }}" width="100"
                                        height="100" alt="Shape">
                                </div>

                                <div class="content">
                                    {{--  <p style="text-align: justify"> --}}
                                    <h6 class="title" style="text-align: justify">
                                        {!! \App\Helpers\TranslationHelper::TranslateText($config->titre_satisfaction ?? ' ') !!}
                                    </h6>
                                    {{--   </p> --}}

                                    <p style="text-align: justify">

                                        {!! \App\Helpers\TranslationHelper::TranslateText($config->des_satisfaction ?? '') !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="about-info-box">
                                <div class="thumb image-center">
                                    <img src="{{ Storage::url($config->icone_annee ?? ' ') }}" height="100"
                                        width="100" alt="Shape">
                                </div>
                                <div class="content">
                                    <h6 class="title" style="text-align: justify">{!! \App\Helpers\TranslationHelper::TranslateText($config->titre_annee ?? '') !!}.</h6>
                                    <p style="text-align: justify">
                                        {!! \App\Helpers\TranslationHelper::TranslateText($config->des_annee ?? ' ') !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="about-info-box">
                                <div class="thumb image-center">
                                    <img src="{{ Storage::url($config->icone_prix ?? ' ') }}" height="100"
                                        width="100" alt="Shape">
                                </div>
                                <div class="content">
                                    <h6 class="title" style="text-align: justify"> {!! \App\Helpers\TranslationHelper::TranslateText($config->titre_prix ?? ' ') !!}.</h6>
                                    <p style="text-align: justify">
                                        {!! \App\Helpers\TranslationHelper::TranslateText($config->des_prix ?? '') !!}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End About Area  -->

            <br><br>


            <!-- Start Expolre Product Area  -->
            <div class="axil-product-area bg-color-white axil-section-gapcommon">
                <div class="container">
                    <div class="section-title-wrapper section-title-center">

                        <h2 class="title">{{ \App\Helpers\TranslationHelper::TranslateText('Produits en promotion') }}💥
                        </h2>
                    </div>
                    <style>

                    </style>
                    <div
                        class="popular-product-activation slick-layout-wrapper slick-layout-wrapper--15 axil-slick-angle angle-top-slide">
                        <div class="slick-single-layout">
                            <div class="row">
                                @if ($produits)
                                    @foreach ($produits as $key => $produit)
                                        @if ($produit->inPromotion())
                                            <div class="col-md-6 col-12">
                                                <div class="axil-product product-style-eight product-list-style-3">
                                                    <div class="thumbnail">
                                                        <a
                                                            href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                            <img style="border-radius: 8px; width: 300px; height: 200px; object-fit: cover;"
                                                                class="main-img" width="200" height="200"
                                                                src="{{ Storage::url($produit->photo) }}"
                                                                alt="Product Images">



                                                            <div class="top-left"
                                                                style="background-color:#EFB121;color: white;">
                                                                <span>
                                                                    @if ($produit->inPromotion())
                                                                        <span>
                                                                            -{{ $produit->inPromotion()->pourcentage }}%</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </a>


                                                    </div>
                                                    <div class="product-content">
                                                        <div class=" col-sm-12 inner">
                                                            <div class="top-right">
                                                                @if ($produit->stock > 0)
                                                                    <label class="badge btn-bg-promotion">
                                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Stock disponible') }}</label>
                                                                @else
                                                                    <label class="badge bg-danger">
                                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Stock non disponible') }}</label>
                                                                @endif
                                                                <style>
                                                                    .btn-bg-promotion {
                                                                        background-color: #08f23a;
                                                                        /* Example primary color */
                                                                        color: #fff;
                                                                        border: none;
                                                                    }
                                                                </style>

                                                            </div>

                                                            <div class="product-cate"><a
                                                                    href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                                    {{ \App\Helpers\TranslationHelper::TranslateText($produit->nom) }}</a>
                                                            </div>
                                                            <div class="color-variant-wrapper">

                                                            </div>
                                                            <h5 class="title"><a
                                                                    href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                                    {{--   {{ \App\Helpers\TranslationHelper::TranslateText( Str::limit($produit->description, 20)) }}
 --}}
                                                                </a></h5>
                                                            <div class="product-rating">

                                                            </div>
                                                            <div class="product-price-variant">
                                                                <span class="price-text"> {!! \App\Helpers\TranslationHelper::TranslateText('Coût') !!}:</span>
                                                                <span class="price current-price"> <b class="text-success"
                                                                        style="color: #4169E1">
                                                                        {{ $produit->getPrice() }}
                                                                    </b></span>

                                                                <span class="price current-price"> <b
                                                                        class="text-success fs-7" style="color: #4169E1">
                                                                        <x-devise></x-devise>
                                                                    </b>
                                                                </span>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center mt--20 mt_sm--0">
                            <a href="{{ url('promotion') }}" class="axil-btn btn-bg-primary2 btn-load-more">

                                {{ \App\Helpers\TranslationHelper::TranslateText('Voir toutes les promotions') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Expolre Product Area  -->


            <!-- Start Testimonila Area  -->
            <div class="axil-testimoial-area axil-section-gap bg-vista-white">
                <div class="container">
                    <div class="section-title-wrapper">
                        <h4> <span class="axil-breadcrumb-item1 active" aria-current="page"> <i
                                    class="fal fa-quote-left"></i>
                                {{ \App\Helpers\TranslationHelper::TranslateText('Témoignages') }}</span> </h4>


                        <h2 class="title">
                            {{ \App\Helpers\TranslationHelper::TranslateText('Les retours de nos clients') }}</h2>
                    </div>

                    <!-- End .section-title -->
                    <div
                        class="testimonial-slick-activation testimonial-style-one-wrapper slick-layout-wrapper--20 axil-slick-arrow arrow-top-slide">

                        @if ($testimonials->isEmpty())
                            <p> {{ \App\Helpers\TranslationHelper::TranslateText('Aucun témoignage disponible') }}.</p>
                        @else
                            @foreach ($testimonials as $testimonial)
                                <div class="slick-single-layout testimonial-style-one">
                                    <div class="review-speech">
                                        <p>“
                                            {!! \App\Helpers\TranslationHelper::TranslateText($testimonial->message) !!}
                                            “</p>
                                    </div>
                                    <div class="media">
                                        <div class="thumbnail">
                                            @if ($testimonial->photo)
                                                <img src="{{ asset('uploads/testimonials/' . $testimonial->photo) }}"
                                                    alt="Photo Témoignage" width="100" height="100">
                                            @else
                                                <img src="./assets/images/testimonial/image-1.png"
                                                    alt="testimonial image">
                                            @endif

                                        </div>
                                        <div class="media-body">
                                            <span class="designation">{{ $testimonial->name }}</span>
                                            {{-- <h6 class="title">James C. Anderson</h6> --}}
                                        </div>
                                    </div>
                                    <!-- End .thumbnail -->
                                </div>
                            @endforeach
                        @endif

                        <!-- End .slick-single-layout -->

                    </div>

                </div>
                <br><br>
                <br>
                <div class="col-12 d-flex justify-content-center">
                    <div class="form-group mb--0">
                        <button class="axil-btn btn-bg-primary2" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            type="submit">
                            <span> {{ \App\Helpers\TranslationHelper::TranslateText('Laisser un témoignage') }}</span>
                        </button>
                    </div>

                </div>


                <div id="successMessage" class="alert alert-success" style="display:none;"></div>
                <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>



            </div>


            <div class="axil-section-gap">
                <div class="container">
                    <div class="section-title-wrapper section-title-center">
                        <span class="title-highlighter highlighter-primary"><i class="fas fa-fire"></i>
                            {{ \App\Helpers\TranslationHelper::TranslateText('Les actualités') }}</span>
                        <h3 class="title">

                            {{ \App\Helpers\TranslationHelper::TranslateText('Dernières actualités') }}
                        </h3>
                    </div>
                    <div class="row g-5">
                        @foreach ($events as $event)
                            <div class="col-lg-4">
                                <div class="content-blog blog-grid">
                                    <div class="inner">
                                        <div class="thumbnail">
                                            <a
                                                href="{{ route('details-actualites', ['id' => $event->id, 'slug' => Str::slug(Str::limit($event->titre, 10))]) }}">
                                                <img style="border-radius: 8px; width: 400px; height: 300px; object-fit: cover;"
                                                    src="{{ Storage::url($event->image) }}" alt="Blog Images">
                                            </a>
                                            {{-- <div class="blog-category">
                                        <a href="#">Digital Art's</a>
                                    </div> --}}
                                        </div>
                                        <div class="content">
                                            <h5 class="title"><a
                                                    href="{{ route('details-actualites', ['id' => $event->id, 'slug' => Str::slug(Str::limit($event->titre, 10))]) }}">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText($event->titre ?? '') }}
                                                </a></h5>
                                            <div class="read-more-btn">
                                                <a class="axil-btn right-icon"
                                                    href="{{ route('details-actualites', ['id' => $event->id, 'slug' => Str::slug(Str::limit($event->titre, 10))]) }}">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Voir plus') }}
                                                    <i class="fal fa-long-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                {{ \App\Helpers\TranslationHelper::TranslateText('Témoignage') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>



                        <div class="modal-body">
                            <form id="testimonialForm" action="{{ route('testimonial.store') }}" method="POST"
                                class="testimonial-form p-4 rounded shadow-sm bg-light">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="name" class="form-label text-muted">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('Nom') }}</label>
                                    <input type="text" class="form-control border-0 rounded-pill shadow-sm"
                                        id="name" name="name" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="testimonial" class="form-label text-muted">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('Message') }}</label>
                                    <textarea class="form-control border-0 rounded-3 shadow-sm" id="testimonial" name="message" rows="8" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-bg-primary2 rounded-pill shadow">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('Envoyer') }}</button>
                                </div>
                            </form>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-4">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success mt-4">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <style>

                            </style>

                        </div>



                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#testimonialForm').on('submit', function(e) {
                        e.preventDefault(); // Empêcher l'envoi classique du formulaire

                        $.ajax({
                            url: $(this).attr('action'),
                            method: $(this).attr('method'),
                            data: $(this).serialize(),
                            success: function(response) {
                                // Afficher le message de succès
                                $('#testimonialModal').modal('hide'); // Fermer le modal

                                $('#successMessage').text(
                                    'Témoignage créé avec succès! Il sera valide après confirmation des administrateurs'

                                ).show();

                                setTimeout(function() {
                                    location.reload();
                                }, 5000);
                            },
                            error: function(response) {
                                // Afficher un message d'erreur si nécessaire
                                $('#errorMessage').text('Une erreur est survenue.')
                                    .show(); // Afficher le message d'erreur
                            }
                        });
                    });
                });
            </script>



        </main>



    </main>


@endsection
