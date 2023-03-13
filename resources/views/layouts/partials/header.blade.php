<site-navigation inline-template>
    <div>
        <header class="site-header relative">
            <nav class="nav-top">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <div class="col-4 col-sm-3 col-md-4">
                        <div class="nav-toggle-wrapper d-lg-none" @click="toggleNav">
                            <a href="#" class="nav-toggle nav-link px-0" v-show="nav == 'closed'">
                                @svg('assets/svg/icon_hamburger.svg', 'icon')
                                Menu
                            </a>
                            <a href="#" class="nav-toggle nav-link px-0 v-cloak--hidden" v-show="nav == 'open'" style="display: none;">
                                @svg('assets/svg/icon_x.svg', 'icon')
                            </a>
                        </div>

                        <ul class="nav d-none d-lg-flex">
                            @if(Auth::user() && Auth::user()->isCustomer() && Request::segment(1) != 'dashboard')
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link btn btn-primary back-to-dash">
                                    @svg('assets/svg/icon_back_arrow.svg', 'icon mr-1') Back to Dashboard
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('news') }}" class="nav-link">News &amp; Articles</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-4 col-sm-6 col-md-4 text-center">
                        <a href="{{ url('/') }}">
                            <span class="sr-only">Age &amp;amp; Peace</span>
                            @svg('assets/svg/logo_header.svg', 'logo-header')

                            @svg('assets/svg/logo_mobile.svg', 'logo-header-mobile')
                        </a>
                    </div>

                    <ul class="nav justify-content-end align-items-center col-4 col-sm-3 col-md-4">
                        @if(Auth::user() && Auth::user()->isCustomer())
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Account Menu">
                                    @svg('assets/svg/icon_login.svg', 'icon icon-login')
                                    <span class="d-none d-md-inline login-label">Account</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                       Dashboard
                                    </a>

                                    <a class="dropdown-item" href="{{ url('me') }}">
                                        Settings
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{route('login')}}" aria-label="login" class="nav-link">
                                    @svg('assets/svg/icon_login.svg', 'icon icon-login')
                                    <span class="d-none d-md-inline login-label">Login</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-link py-0 d-none d-lg-flex">
                            <modal-trigger button-text="Get Help Now">
                                @include('modals.help')
                            </modal-trigger>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="nav-wrapper {{ $header }} shadow">
            <div class="nav-container">
                <div class="row">
                    <div class="col-12">
                        <transition name="fade">
                            <nav class="nav-categories" :class="{open: nav === 'open'}" ref="navCategories">
                                <div class="bg-light">
                                    <ul class="nav d-flex d-lg-none">
                                        <li class="nav-item">
                                            <a href="{{ route('providers') }}" class="nav-link" aria-label="Search Providers">
                                                @svg('assets/svg/icon_search_header.svg', 'icon')
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('pages', ['about']) }}" class="nav-link">About</a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('news') }}" class="nav-link">News & Articles</a>
                                        </li>

                                        <li class="bg-primary ml-auto text-white">
                                            <modal-trigger>
                                                <a href="#" slot="openButton" slot-scope="{show}" @click.prevent="show" class="nav-link">
                                                    <span class="d-sm-none">Help</span>
                                                    <span class="d-none d-sm-block">Get Help Now</span>
                                                </a>

                                                @include('modals.help')
                                            </modal-trigger>
                                        </li>
                                    </ul>
                                </div>

                                <ul class="nav justify-content-between flex-column flex-lg-row">

                                    @cachebegin('megamenu')

                                    @foreach (App\Models\Category::topLevel()->with(['childrenCategories', 'childrenCategories.providerTypes'])->get() as $category)
                                        <accordion>
                                            <span class="nav-link nav-label">{{ $category->name }} @svg('assets/svg/icon_chevron_down.svg', 'icon')</span>
                                            @include('components.megamenu-content', ['category' => $category])
                                        </accordion>
                                    @endforeach

                                    @cacheend('megamenu')

                                    <li class="d-none d-lg-block">
                                        <a href="{{ route('providers') }}" class="nav-link nav-search" aria-label="Search Providers">
                                            @svg('assets/svg/icon_search_header.svg', 'icon')
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</site-navigation>
