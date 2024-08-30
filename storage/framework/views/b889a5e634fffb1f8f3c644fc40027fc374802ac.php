
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

            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <?php if($logo = opt('site_logo')): ?>
                    <img src="<?php echo e(asset($logo)); ?>" alt="logo" class="site-logo"/>
                <?php else: ?>
                    <?php echo e(opt( 'site_title' )); ?>

                <?php endif; ?>
            </a><!-- logo -->

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button><!-- navbar toggler icon (mobile) -->

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                <button class="navbar-toggler close_tgl" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <img src="<?php echo e(asset('images/close.png')); ?>" alt=""/>
                </button><!-- close navi on mobile -->

                <ul class="navbar-nav menu_sec">
                   <li> <?php if( auth()->guest() ): ?></li>
                        <li>
                            <a href="/"><i class="fas fa-door-closed"></i> <?php echo app('translator')->get( 'navigation.home' ); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if( !auth()->guest() ): ?>
                    <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('search-creators')->dom;
} elseif ($_instance->childHasBeenRendered('itjw3aH')) {
    $componentId = $_instance->getRenderedChildComponentId('itjw3aH');
    $componentTag = $_instance->getRenderedChildComponentTagName('itjw3aH');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('itjw3aH');
} else {
    $response = \Livewire\Livewire::mount('search-creators');
    $dom = $response->dom;
    $_instance->logRenderedChild('itjw3aH', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        <li>
                            <a href="<?php echo e(route('feed')); ?>"><i class="fas fa-book-open"></i> <?php echo app('translator')->get('navigation.feed'); ?></a>
                        </li>
                        <li>
                            <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('notifications-icon')->dom;
} elseif ($_instance->childHasBeenRendered('qwS6qs0')) {
    $componentId = $_instance->getRenderedChildComponentId('qwS6qs0');
    $componentTag = $_instance->getRenderedChildComponentTagName('qwS6qs0');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('qwS6qs0');
} else {
    $response = \Livewire\Livewire::mount('notifications-icon');
    $dom = $response->dom;
    $_instance->logRenderedChild('qwS6qs0', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        </li>
                        <li>
                            <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('unread-messages-count')->dom;
} elseif ($_instance->childHasBeenRendered('1ScZGFn')) {
    $componentId = $_instance->getRenderedChildComponentId('1ScZGFn');
    $componentTag = $_instance->getRenderedChildComponentTagName('1ScZGFn');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('1ScZGFn');
} else {
    $response = \Livewire\Livewire::mount('unread-messages-count');
    $dom = $response->dom;
    $_instance->logRenderedChild('1ScZGFn', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        </li>
                        <li>
                            <a href="<?php echo e(route('profile.show', ['username' => auth()->user()->profile->username ])); ?>">
                                <i class="fas fa-user"></i> <?php echo app('translator')->get('navigation.myProfile'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('startMyPage')); ?>">
                               <i class="fas fa-money-bill"></i> <?php echo app('translator')->get('navigation.account'); ?>
                                <?php if(auth()->user()->profile->isVerified == 'Yes' && auth()->user()->profile->monthlyFee): ?>
                                    <span class=""><?php echo e('(' . opt('payment-settings.currency_symbol') . number_format(auth()->user()->balance,2) . ')'); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('browseCreators')); ?>"><i class="fas fa-users"></i> <?php echo app('translator')->get('navigation.exploreCreators'); ?></a>
                    </li>
                    <?php if( auth()->guest() ): ?>
                        <li class="d-none d-sm-none d-md-block">
                            <a href="<?php echo e(route('register')); ?>"
                               class="border-white border-radius-account-buttons padding-account-buttons signupButton">
                                <i class="fas fa-user"></i> <i class="fas fa-key"></i> <?php echo app('translator')->get('navigation.signUp'); ?>
                            </a>
                        </li>
                        <li class="d-none d-sm-none d-md-block">
                            <a href="<?php echo e(route('login')); ?>"
                               class="bg-white border-radius-account-buttons padding-account-buttons loginButton">
                                <i class="fas fa-sign-in-alt"></i> <?php echo app('translator')->get('navigation.login'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if( !auth()->guest() ): ?>
                        <li>
                            <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <?php echo app('translator')->get('navigation.logout'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(auth()->guard()->guest()): ?>
                     <li class="search-creators-desktop">
                    <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('search-creators')->dom;
} elseif ($_instance->childHasBeenRendered('Nem98H3')) {
    $componentId = $_instance->getRenderedChildComponentId('Nem98H3');
    $componentTag = $_instance->getRenderedChildComponentTagName('Nem98H3');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('Nem98H3');
} else {
    $response = \Livewire\Livewire::mount('search-creators');
    $dom = $response->dom;
    $_instance->logRenderedChild('Nem98H3', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                    </li>
                    <?php endif; ?>
<!--                        <li class="search-creators-mobile">-->
<!--    <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('search-creators')->dom;
} elseif ($_instance->childHasBeenRendered('cO4n7xZ')) {
    $componentId = $_instance->getRenderedChildComponentId('cO4n7xZ');
    $componentTag = $_instance->getRenderedChildComponentTagName('cO4n7xZ');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('cO4n7xZ');
} else {
    $response = \Livewire\Livewire::mount('search-creators');
    $dom = $response->dom;
    $_instance->logRenderedChild('cO4n7xZ', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>-->
<!--</li>-->
                </ul>
                <ul class="rt_btn d-lg-none d-md-block d-sm-block d-block">
                    <?php if( auth()->guest() ): ?>
                        <li>
                            <a href="<?php echo e(route('register')); ?>"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-user"></i> <?php echo app('translator')->get('navigation.signUp'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('login')); ?>"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-sign-in-alt"></i> <?php echo app('translator')->get('navigation.login'); ?></a>
                        </li>
                    <?php endif; ?>
                      <?php if(auth()->guard()->guest()): ?>
                     <div class="search-creators-mobile mt-2" style="width:100%;">
                    <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('search-creators')->dom;
} elseif ($_instance->childHasBeenRendered('FqyuDbf')) {
    $componentId = $_instance->getRenderedChildComponentId('FqyuDbf');
    $componentTag = $_instance->getRenderedChildComponentTagName('FqyuDbf');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('FqyuDbf');
} else {
    $response = \Livewire\Livewire::mount('search-creators');
    $dom = $response->dom;
    $_instance->logRenderedChild('FqyuDbf', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                    </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
<?php /**PATH C:\laragon\www\vipclub\resources\views/partials/topnavi.blade.php ENDPATH**/ ?>