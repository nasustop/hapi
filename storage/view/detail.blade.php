<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Elehaus- Electronics eCommerce Website Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Work+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/simple-line-icons.css">
    <link rel="stylesheet" href="/assets/css/plugins/fancybox.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/nice-select.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
<div class="wrapper">
    <header class="header-wrapper">
        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center justify-content-between align-items-center">
                    <div class="col-auto">
                        <div class="header-logo-area">
                            <a href="/index.html">
                                <img class="logo-main" src="/assets/images/logo.png" width="182" height="31" alt="Logo">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="main-content">
        <div class="product-detail-area section-space">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="product-detail-thumb me-lg-6">
                            <div class="swiper single-product-thumb-slider">
                                <div class="swiper-wrapper">
                                    @foreach ($spu_info['spu_images'] ?? [] as $value)
                                        <a class="lightbox-image swiper-slide" data-fancybox="gallery" href="{{$value}}">
                                            <img src="{{$value}}" width="640" height="530" alt="Image">
                                        </a>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="single-product-nav-wrp">
                                <div class="swiper single-product-nav-slider">
                                    <div class="swiper-wrapper">
                                        @foreach ($spu_info['spu_images'] ?? [] as $value)
                                            <div class="nav-item swiper-slide">
                                                <img src="{{$value}}" alt="Image" width="127" height="127">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="swiper-button-style11">
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-detail-content">
                            <h2 class="product-detail-title mt-n1 me-10">{{$spu_info['spu_name']}}</h2>
                            <div class="product-detail-price">${{$spu_info['price_min']}}</div>
                            <p class="product-detail-desc">{{$spu_info['spu_brief']}}</p>
                            <ul class="product-detail-meta">
                                @foreach ($spu_info['params_value_data'] ?? [] as $value)
                                    <li>
                                        <span>{{$value['params_name']}}:</span>
                                        @foreach ($value['params_value'] as $vv)
                                            @if(! empty($spu_info['params_value'][$value['params_id']]) && $spu_info['params_value'][$value['params_id']] === $vv['params_value_id'])
                                                {{$vv['params_value_name']}}
                                            @endif
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="nav product-detail-nav" id="product-detail-nav-tab" role="tablist">
                    <button class="product-detail-nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">商品详情</button>
                    <button class="product-detail-nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab" aria-controls="specification" aria-selected="false">商品参数</button>
                </div>
                <div class="tab-content" id="product-detail-nav-tabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        {!! $spu_info['spu_intro'] !!}
                    </div>
                    <div class="tab-pane" id="specification" role="tabpanel" aria-labelledby="specification-tab">
                        <ul class="product-detail-info-wrap">
                            @foreach ($spu_info['params_value_data'] ?? [] as $value)
                                <li>
                                    <span>{{$value['params_name']}}:</span>
                                    @foreach ($value['params_value'] as $vv)
                                        @if(! empty($spu_info['params_value'][$value['params_id']]) && $spu_info['params_value'][$value['params_id']] === $vv['params_value_id'])
                                            {{$vv['params_value_name']}}
                                        @endif
                                    @endforeach
                                </li>
                            @endforeach
                        </ul>
                        <p>{{$spu_info['spu_brief']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer-area">
        <div class="container">
            <div class="footer-bottom" style="text-align: center;display: block">
                <p class="copyright">© 2022 <span class="text-primary">Hshop</span></p>
            </div>
        </div>
    </footer>
    <div class="scroll-to-top"><span class="fa fa-angle-double-up"></span></div>
</div>
<script src="/assets/js/vendor/modernizr-3.11.7.min.js"></script>
<script src="/assets/js/vendor/jquery-3.6.0.min.js"></script>
<script src="/assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.min.js"></script>
<script src="/assets/js/plugins/swiper-bundle.min.js"></script>
<script src="/assets/js/plugins/fancybox.min.js"></script>
<script src="/assets/js/plugins/jquery.nice-select.min.js"></script>
<script src="/assets/js/plugins/jquery.countdown.min.js"></script>
<script src="/assets/js/plugins/isotope.pkgd.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>

</html>
