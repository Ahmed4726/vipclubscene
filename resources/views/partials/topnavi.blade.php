
<style>
    /* Hover effect for navbar links */
    .menu_sec li a:hover {
        color: #1cb9de; /* Change to your desired hover color */
    }

    /* Hover effect for icons */
    .menu_sec li a:hover i {
        color: #1cb9de; /* Change to your desired hover color */
    }
    @media (max-width: 480px) {
        .search-creators-mobile {
            display: block; /* Show on small screens */
        }

        .search-creators-desktop {
            display: none; /* Hide on small screens */
        }
    }

    @media (min-width: 768px) {
        .search-creators-mobile {
            display: none; /* Hide on larger screens */
        }

        .search-creators-desktop {
            display: block; /* Show on larger screens */
        }
    }
</style>
<header class="header_sec innerheaders">
 
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light nav_top">

            <a class="navbar-brand" href="{{ route('home') }}">
                @if($logo = opt('site_logo'))
                    <img src="{{ asset($logo) }}" alt="logo" class="site-logo"/>
                @else
                    {{ opt( 'site_title' ) }}
                @endif
            </a><!-- logo -->

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button><!-- navbar toggler icon (mobile) -->

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                <button class="navbar-toggler close_tgl" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <img src="{{ asset('images/close.png') }}" alt=""/>
                </button><!-- close navi on mobile -->

                <ul class="navbar-nav menu_sec">
                   <li> @if( auth()->guest() )</li>
                        <li>
                            <a href="/"><i class="fas fa-door-closed"></i> @lang( 'navigation.home' )</a>
                        </li>
                    @endif
                    @if( !auth()->guest() )
                    @livewire('search-creators')
                        <li>
                            <a href="{{ route('feed') }}"><i class="fas fa-book-open"></i> @lang('navigation.feed')</a>
                        </li>
                        <li>
                            @livewire('notifications-icon')
                        </li>
                        <li>
                            @livewire('unread-messages-count')
                        </li>
                        <li>
                            <a href="{{ route('profile.show', ['username' => auth()->user()->profile->username ]) }}">
                                <i class="fas fa-user"></i> @lang('navigation.myProfile')
                            </a>
                        </li>
                        <li>
                            <a href="{{  route('startMyPage') }}">
                               <i class="fas fa-money-bill"></i> @lang('navigation.account')
                                @if(auth()->user()->profile->isVerified == 'Yes' && auth()->user()->profile->monthlyFee)
                                    <span class="">{{ '(' . opt('payment-settings.currency_symbol') . number_format(auth()->user()->balance,2) . ')' }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('browseCreators') }}"><i class="fas fa-users"></i> @lang('navigation.exploreCreators')</a>
                    </li>
                    @if( auth()->guest() )
                        <li class="d-none d-sm-none d-md-block">
                            <a href="{{ route('register') }}"
                               class="border-white border-radius-account-buttons padding-account-buttons signupButton">
                                <i class="fas fa-user"></i> <i class="fas fa-key"></i> @lang('navigation.signUp')
                            </a>
                        </li>
                        <li class="d-none d-sm-none d-md-block">
                            <a href="{{ route('login') }}"
                               class="bg-white border-radius-account-buttons padding-account-buttons loginButton">
                                <i class="fas fa-sign-in-alt"></i> @lang('navigation.login')</a>
                        </li>
                    @endif
                    @if( !auth()->guest() )
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                @lang('navigation.logout')
                            </a>
                        </li>
                    @endif
                    @guest
                     <li class="search-creators-desktop">
                    @livewire('search-creators')
                    </li>
                    @endguest
<!--                        <li class="search-creators-mobile">-->
<!--    @livewire('search-creators')-->
<!--</li>-->
                </ul>
                <ul class="rt_btn d-lg-none d-md-block d-sm-block d-block">
                    @if( auth()->guest() )
                        <li>
                            <a href="{{ route('register') }}"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-user"></i> @lang('navigation.signUp')
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-sign-in-alt"></i> @lang('navigation.login')</a>
                        </li>
                    @endif
                      @guest
                     <div class="search-creators-mobile mt-2" style="width:100%;">
                    @livewire('search-creators')
                    </div>
                    @endguest
                </ul>
            </div>
        </nav>
    </div>
</header>
