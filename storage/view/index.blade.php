<!DOCTYPE html>
<html class="no-js" lang="zh">

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
    <link rel="stylesheet" href="/assets/css/plugins/range-slider.css">
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
                            <a href="/index.html"><img class="logo-main" src="/assets/images/logo.png" width="182" height="31" alt="Logo"></a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="header-action d-flex justify-content-end align-items-center">
                            <form action="/index.html" class="header-search-box d-none d-lg-block">
                                <input class="form-control" type="text" id="search" name="name" placeholder="请输入商品名称">
                                <button type="submit" class="btn-src"><i class="icon-magnifier"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="main-content" style="min-height: 700px">
        <div class="product-area section-space" style="padding-top: 0;padding-bottom: 40px">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xl-9 order-0 order-lg-1">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="column-three" role="tabpanel" aria-labelledby="column-three-tab">
                                <div class="row mb-n6">
                                    @foreach ($spuList as $info)
                                        <div class="col-sm-6 col-lg-6 col-xl-4 mb-6">
                                            <div class="product-item">
                                                <a class="product-item-thumb" href="{{'/detail/'.$info['spu_id'].'.html'}}">
                                                    <img src="{{$info['spu_thumb']}}" width="270" height="264" alt="Image-HasTech">
                                                </a>
                                                <div class="product-item-action">
                                                    <button type="button" class="product-action-btn action-btn-quick-view" data-bs-toggle="modal" data-bs-target="#action-QuickViewModal{{$info['spu_id']}}">
                                                        <i class="icon-magnifier"></i>
                                                    </button>
                                                </div>
                                                <div class="product-item-info text-center" style="padding: 15px">
                                                    <h5 class="product-item-title mb-2">
                                                        <a href="{{'/detail/'.$info['spu_id'].'.html'}}">
                                                            {{$info['spu_name']}}
                                                        </a>
                                                    </h5>
                                                    <div class="product-item-price" style="margin-bottom: 0">
                                                        ${{$info['price_min']}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-12">
                                        <nav class="pagination-area mt-6 mb-6">
                                            <ul class="page-numbers justify-content-center">
                                                @for($i = 1; $i <= $allPage; $i++)
                                                    <li>
                                                        @if($i === $allPage && $page < $allPage)
                                                            <a class="page-number {{$i==$page ? 'active' : ''}}" href="{{$url . '?page=' . $i}}">
                                                                {{$i}}
                                                            </a>
                                                            <a class="page-number" href="{{$url . '?page=' . ($page+1)}}">
                                                                <i class="icon-arrow-right"></i>
                                                            </a>
                                                        @elseif($i === 1 && $page > 1)
                                                            <a class="page-number" href="{{$url . '?page=' . ($page-1)}}">
                                                                <i class="icon-arrow-left"></i>
                                                            </a>
                                                            <a class="page-number {{$i==$page ? 'active' : ''}}" href="{{$url . '?page=' . $i}}">
                                                                {{$i}}
                                                            </a>
                                                        @else
                                                            <a class="page-number {{$i==$page ? 'active' : ''}}" href="{{$url . '?page=' . $i}}">{{$i}}</a>
                                                        @endif
                                                    </li>
                                                @endfor
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 order-1 order-lg-0">
                        <div class="sidebar-area mt-10 mt-lg-0">
                            <div class="widget-item widget-item-one pb-5">
                                <h3 class="widget-two-title text-black">商品分类</h3>
                                <div class="widget-categories-list">

                                    @foreach ($categoryList as $info)
                                        @if (! empty($info['children']) && is_array($info['children']))
                                            <a class="widget-category-item" id="{{$info['value']}}">
                                                <span class="icon">+</span>{{$info['label']}}
                                            </a>
                                            <div class="widget-category-item-children" id="children-{{$info['value']}}">
                                            @foreach ($info['children'] ?? [] as $value)
                                                    @if (! empty($value['children']) && is_array($value['children']))
                                                        <a class="widget-category-item" id="{{$value['value']}}">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <span class="icon">+</span>{{$value['label']}}
                                                        </a>
                                                        <div class="widget-category-item-children" id="children-{{$value['value']}}">
                                                            @foreach ($value['children'] ?? [] as $vv)
                                                                <a class="widget-category-item" href="/category/{{$vv['value']}}.html">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <span class="icon">-</span>{{$vv['label']}}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <a class="widget-category-item" href="/category/{{$value['value']}}.html">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <span class="icon">-</span>{{$value['label']}}
                                                        </a>
                                                    @endif
                                            @endforeach
                                            </div>
                                        @else
                                            <a class="widget-category-item" href="/category/{{$info['value']}}.html">
                                                <span class="icon">-</span>{{$info['label']}}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer-area">
        <div class="container">
            <div class="footer-bottom" style="text-align: center;display: block">
                <p class="copyright">© 2024 <span class="text-primary">Hshop</span></p>
            </div>
        </div>
    </footer>
    <div class="scroll-to-top"><span class="fa fa-angle-double-up"></span></div>

    @foreach ($spuList as $info)
        <aside class="product-cart-view-modal modal fade" id="action-QuickViewModal{{$info['spu_id']}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="product-quick-view-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"><span>×</span></button>
                            <div class="row row-gutter-0">
                                <div class="col-lg-6">
                                    <div class="single-product-slider">
                                        <div class="single-product-thumb">
                                            <div class="swiper single-product-quick-view-slider">
                                                <div class="swiper-wrapper">
                                                    @foreach ($info['spu_images'] ?? [] as $value)
                                                    <div class="swiper-slide">
                                                        <div class="thumb-item"><img src="{{$value}}" alt="Image" width="640" height="710">
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <div class="swiper-button-next"></div>
                                                <div class="swiper-button-prev"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="product-detail-content mt-6 mt-lg-0">
                                        <h2 class="product-detail-title mt-n1 me-10">{{$info['spu_name']}}</h2>
                                        <div class="product-detail-price">${{$info['price_min']}}</div>
                                        <p class="product-detail-desc">{{$info['spu_brief']}}</p>
                                        <ul class="product-detail-meta pt-6">
                                            @foreach ($info['params_value_data'] ?? [] as $value)
                                                <li>
                                                    <span>{{$value['params_name']}}:</span>
                                                    @foreach ($value['params_value'] as $vv)
                                                        @if(! empty($info['params_value'][$value['params_id']]) && $info['params_value'][$value['params_id']] === $vv['params_value_id'])
                                                            {{$vv['params_value_name']}}
                                                        @endif
                                                    @endforeach
                                                </li>
                                            @endforeach
{{--                                            <li><span>SKU:</span>WX-256HG</li>--}}
{{--                                            <li><span>Categories:</span>Home,Electronic</li>--}}
{{--                                            <li><span>Tag</span>Electronic</li>--}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    @endforeach
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
<script src="/assets/js/plugins/range-slider.js"></script>
<script src="/assets/js/main.js"></script>
</body>

</html>
