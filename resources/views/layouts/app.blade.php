<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : '' }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz"
        crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .bg-grey {
            background-color: #e9ecef
        }
        .full-height{
           height: 100vh; 
        }
        .black-gradient {
            background: rgb(181,189,200); /* Old browsers */
            background: -moz-linear-gradient(left, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 40%, rgba(130,140,149,1) 50%, rgba(130,140,149,1) 56%, rgba(130,140,149,1) 62%, rgba(181,189,200,1) 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(left, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 40%,rgba(130,140,149,1) 50%,rgba(130,140,149,1) 56%,rgba(130,140,149,1) 62%,rgba(181,189,200,1) 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to right, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 40%,rgba(130,140,149,1) 50%,rgba(130,140,149,1) 56%,rgba(130,140,149,1) 62%,rgba(181,189,200,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#b5bdc8',GradientType=1 ); /* IE6-9 */
        }
    </style>
</head>
<body class="full-height flex">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/accueil') }}">
                    <img class="mt-1" src="/img/logo-only.png" width="80vw">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                    @isset($company)
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item ml-4 text-center ">
                                <a class="nav-link mt-4 " href="{{ '/' . $company->name . '/Devis'}}">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-dark"></i>
                                    <p>Devis</p>
                                </a>
                            </li>
                            <li class="nav-item ml-4 text-center">
                                <a class="nav-link mt-4 " href="{{ '/' . $company->name . '/Facture'}}">
                                    <i class="fas fa-handshake fa-2x text-dark"></i>
                                    <p>Facture</p>
                                </a>
                            </li> 
                            @if (Auth::user()->role == 'Manager')
                                <li class="nav-item ml-4 text-center">
                                    <a class="nav-link mt-4" href="{{'/' . $company->name . '/Payement'}}">
                                        <i class="fas fa-calculator fa-2x text-dark"></i>
                                        <p class="">Caisse</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item ml-4 text-center">
                                <a class="nav-link mt-4 " href="{{ '/' . $company->name . '/Client'}}">
                                    <i class="fas fa-users fa-2x text-dark"></i>
                                    <p class="">Clients</p>
                                </a>
                            </li> 
                            @if (Auth::user()->role == 'Manager')
                                <li class="nav-item ml-4 text-center">
                                    <a class="nav-link mt-4 not-allowed" href="{{'/' . $company->name . '/Produits'}}">
                                        <i class="fas fa-boxes fa-2x text-secondary "></i>
                                        <p class="">Produits</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4 text-center ">
                                    <a class="nav-link mt-4 not-allowed " href="{{'/' . $company->name . '/Services'}}">
                                        <i class="fas fa-file-contract fa-2x text-secondary"></i>
                                        <p class="">Services</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4 text-center">
                                    <a class="nav-link mt-4 not-allowed" href="{{'/' . $company->name . '/Fournisseurs'}}">
                                        <i class="fas fa-user-tie fa-2x text-secondary"></i>
                                        <p class="">Fournisseur</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endisset
                        
                    @endauth
                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Se Connecter</a>
                            </li> 
                        @else
                        @isset($record)
                            
                        @endisset
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ $company['name'] }}/paramètres">Paramètres</a>
                                    @isset($company)
                                        
                                        <a class="dropdown-item">{{ Carbon::parse(now()->startOfDay())->diffInDays($company['expiry_date'])}} Credit(s) Restant(s)</a>
                                    @endisset
                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 mb-4">
            @yield('content')
        </main>

        {{-- <footer class="fixed-bottom black-gradient box-shadow">
            <div class="row pt-4">
                <div class="col">
                    <a class="ml-5 mr-2" href="https://www.facebook.com/serv2i" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="mx-2" href="#"><i class="fab fa-twitter"></i></a>
                </div>
                <div class="col mr-5">
                    <p class="text-right">
                        Log'ici<span class="mx-1">&copy;</span>{{ now()->format('Y') }}
                    </p>
                </div>
            </div>
        </footer> --}}
    </div>
</body>
</html>