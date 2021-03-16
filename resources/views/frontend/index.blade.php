@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
    <!--main page -->
    <div class="section-main">
        <div class="jumbotron jumbotron-fluid text-right vertical-center">
            <div class="container flex-column">
                <b-row>
                    <b-col></b-col>
                    <b-col><h1 class="masthead-heading text-uppercase mb-0">Učenie sa stáva <br> jednoduchším</h1>
                        <p class="lead">Huwe dáva možnosť zdokovaľovať sa v cudzom jazyku, vo vyučovaní na škôlkach, základných a stredných školách a zlepšiť prostredie pre ľudí s poruchami učenia. </p>
                        <div class="btn-toolbar float-right" role="toolbar">
                            <div class="btn-group">
                                <a class="btn btn-start btn-lg text-white" href="{{route('frontend.auth.login')}}" class="nav-link {{ active_class(Route::is('frontend.auth.login')) }}">Začať</a>
                            </div>
                        </div></b-col>
                </b-row>
            </div>
        </div>
    </div>
    <!--plan page -->
    <div class="plan-page">
        <div class="jumbotron jumbotron-fluid text-left">
            <div class="container flex-column ">
                <h1 class="text-uppercase text-center">Učebná plán</h1>
                <div class="row justify-content-md-center">
                    <div class="col col-md-3">
                        <h2>Cudzie jazyky</h2>
                        <p>Umožnuje výučbu rozličných cudzích jazykov v materinskom jazyku</p>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col col-md-3">
                        <h2>Školy a škôlky</h2>
                        <p>Prispieva funkciami stránky pre výučbu na škôlkach, základných a stredných školách</p>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col col-md-3">
                        <h2>Dyslexia</h2>
                        <p>Vytvára prostredie pre ľúdi s dyslexiou k prijateľnejšiemu spôsobu učenia</p>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col col-md-3">
                        <h2>Vzdelávací systém</h2>
                        <p>Všetky čati webovej aplikácie sa snažia podporiť a zlepšiť vzdelávací systém</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="information-page">
        <div class="jumbotron jumbotron-fluid text-right vertical-center mb-0">
            <div class="container flex-column">
                <b-row>
                    <b-col></b-col>
                    <b-col>                <h1 class="masthead-heading">S kýmkoľkvek, kdekoľvek</h1>
                        <p class="lead">Vždy keď chceš svoj čas urobiť produktívnym, stačí sa pripojiť na internet a začať pracovať na sebe</p>
                    </b-col>
                </b-row>
            </div>
        </div>
    </div>
    <footer-vue></footer-vue>
@endsection
@section('style')
    <style>
        .main-page h1 {
            font-size: 3rem;
        }
        .section-main .jumbotron {
            background: url("https://andywork.site/images/main-pagepsd.jpg?a6b3127c03ec1f9e734a9002e594ada2") no-repeat;
            width: 100%;
        }
        .plan-page .jumbotron {
            background: url("images/our-plan.png?9bb681750cbf622b1fc845460eebe072") no-repeat center center;
        }
        .plan-page .col {
            margin-bottom: 15%;
        }
        .plan-page h1 {
            margin-bottom: 5%;
        }
        .information-page .jumbotron {
            width: 100%;
            background: url("images/information.jpg?022b1f123551ac76e25da7112c3fe789") no-repeat left center;
        }
        .jumbotron {
            width: 100%;
            height: 768px;
            background-size: 100% 100%;
        }
        .footer .jumbotron{
            height: 300px;
        }
        .vertical-center {
            min-height: 100%;
            min-height: 100vh;

            display: flex;
            align-items: center;
        }
        .btn-start {
            background: #622161;
        }
        @media only screen and (max-width: 900px) {
            .section-main .jumbotron {
                background: none;
            }
            .plan-page .jumbotron {
                background: none;
            }
            .information-page .jumbotron {
                background: none;
            }
        }
    </style>
@endsection

