<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $website_name }}</title>
    @include('Layouts.cmn_head_link')

    <style>
        .doc_name {
            margin: 5px 0 5px 0;
        }
    </style>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('Layouts.top_nav')
            @include('Layouts.Admin_left_nav')
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @if (session()->get('user_type') != 'Payroll_QC')
                        <div class="row ">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Total Emp</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_tlt_emp_cnt }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-green">10%</span> Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/1.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Verified Emp</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_verified_tlt_emp_cnt }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-orange">09%</span> Decrease</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/2.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Pending Q</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_pen_query_cnt }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-green">18%</span>Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/3.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Completed Q</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_com_query_cnt }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-green">42%</span> Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/4.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (session()->get('user_type') == 'Payroll_QC')
                        <div class="row ">
                        <div class="col-xl-1"></div>

                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Overall F&F cases</h5>
                                                        <h2 class="mb-3 font-18">
                                                            {{ $get_tlt_emp_cnt }}
                                                        </h2>
                                                        <!-- <p class="mb-0"><span class="col-green">10%</span> Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/1.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card" style="background-color: #e40b0bb8;">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Revert cases in F&F</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_verified_tlt_emp_cnt }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-orange">09%</span> Decrease</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/2.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card" style="background-color: #e8f429c7;">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Observation in F&F</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_pen_query_cnt }}</h2>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/3.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card" style="background-color: #4aef71c7;">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Completed F&F</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_com_query_cnt }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-green">42%</span> Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/4.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xl-1"></div>

                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Overall Resettlement cases</h5>
                                                        <h2 class="mb-3 font-18">
                                                            {{ $get_tlt_emp_cnt_resettlement }}

                                                        </h2>
                                                        <!-- <p class="mb-0"><span class="col-green">10%</span> Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/8.png') }}"
                                                            alt="" style="max-width: 89%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card" style="background-color: #e4330b8a;">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Reverts in Resettlement</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_verified_tlt_emp_cnt_resttlement }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-orange">09%</span> Decrease</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/7.png') }}"
                                                            alt="" style="max-width: 59%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card" style="background-color: #e8f429c7;">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Observation in Resettlement</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_pen_query_cnt_resttlement }}</h2>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/3.png') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="card" style="background-color: #4c894ef7;">
                                    <div class="card-statistic-4">
                                        <div class="align-items-center justify-content-between">
                                            <div class="row ">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                    <div class="card-content">
                                                        <h5 class="font-15">Completed in Resettlement</h5>
                                                        <h2 class="mb-3 font-18">{{ $get_com_query_cnt_resettlement }}</h2>
                                                        <!-- <p class="mb-0"><span class="col-green">42%</span> Increase</p> -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                    <div class="banner-img">
                                                        <img src="{{ asset('assets/img/banner/6.png') }}"
                                                            alt="" style="max-width: 89%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
                @include('Layouts.theme_setting')
            </div>
            @include('Layouts.footer')
        </div>
    </div>
    @include('Layouts.cmn_footer_link')
</body>
<script>
    $(document).ready(function() {
        $(".dashbord_drop").addClass("active");
        $(".dashbord_a").addClass("toggled");
    })
</script>

<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
