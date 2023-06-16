<section class="slice pt-md-8 pb-5 <?php echo themeSettings::config('homestyle', 'light', 'bg-white', 'bg-section-dark') ?>" <?php echo themeSettings::config('homecolor') ?>>
    <div data-offset-top="#navbar-main" style="padding-top: 100px">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 pr-lg-5">
                    <h1 class="display-4 <?php echo themeSettings::config('homestyle', 'light', 'text-dark', 'text-white') ?> font-weight-bolder mb-4">
                        <?php echo themeSettings::config('title') ?>
                    </h1>                    
                    <div class="lead <?php echo themeSettings::config('homestyle', 'light', 'text-dark', 'text-white') ?> opacity-8">
                        <?php echo themeSettings::config('description') ?>
                    </div>                 
                    <?php message() ?>                    
                    <form class="mt-5" method="post" action="<?php echo route('shorten') ?>" data-trigger="shorten-form">
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" class="form-control" placeholder="<?php echo e("Paste a long url") ?>" name="url" id="url">
                            <div class="input-group-append">
                                <button class="btn btn-warning d-none" type="button"><?php ee('Copy') ?></button>
                                <button class="btn btn-success" type="submit"><?php ee('Shorten') ?></button>
                            </div>
                        </div>
                        <?php if(!config('pro')): ?>
                            <a href="#advanced" data-toggle="collapse" class="btn btn-xs btn-primary mb-2"><?php ee('Advanced') ?></a>
                            <div class="collapse row" id="advanced">
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="custom" class="control-label"><?php ee('Custom') ?></label>
                                        <input type="text" class="form-control" name="custom" id="custom" placeholder="<?php echo e("Type your custom alias here")?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="pass" class="control-label"><?php ee('Password Protection') ?></label>                                    
                                        <input type="text" class="form-control border-start-0 ps-0" name="pass" id="pass" placeholder="<?php echo e("Type your password here")?>" autocomplete="off">
                                    </div>
                                </div>                                
                            </div>
                        <?php endif ?>
                        <?php if(!\Core\Auth::logged()) { echo \Helpers\Captcha::display(); } ?>
                    </form>
                    <div id="output-result" class="border border-success p-3 rounded d-none">
                        <div class="row">
                            <div id="qr-result" class="col-md-4 p-2"></div>
                            <div id="text-result" class="col-md-8">
                                <p class="<?php echo themeSettings::config('homestyle', 'light', 'text-dark', 'text-white') ?>"><?php ee('Your link has been successfully shortened. Want to more customization options?') ?></p>
                                <a href="<?php echo route('register') ?>" class="btn btn-sm btn-primary"><?php ee('Get started') ?></a>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="col-12 col-lg-6 mt-7 mt-lg-0">                    
                    <div class="position-relative left-8 left-lg-0 d-none d-lg-block">
                        <figure>
                        <?php if (isset($themeconfig->hero) && !empty($themeconfig->hero)): ?>
                            <img src="<?php echo uploads($themeconfig->hero) ?>" alt="<?php echo config("title") ?>" class="img-fluid mw-lg-120 rounded-top zindex-100">
                        <?php else: ?>
                            <img src="<?php echo assets("images/landing.png") ?>" alt="<?php echo config('title') ?>" class="img-fluid mw-lg-120 rounded-top zindex-100">
                        <?php endif ?>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <!----<div class="shape-container shape-line shape-position-bottom zindex-102">
        <svg width="2560px" height="100px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" x="0px" y="0px" viewBox="0 0 2560 100" style="enable-background:new 0 0 2560 100;" xml:space="preserve" class="fill-section-secondary">
            <polygon points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>---->
</section>
<?php if(config('user_history') && !\Core\Auth::logged() && $urls = \Helpers\App::userHistory()): ?>
    <section class="slice pt-md-8 pb-0 bg-section-secondary">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card bg-section-dark">
                        <div class="card-body">
                            <h4 class="text-white mb-5"><?php ee('Your latest links') ?></h4>
                            <?php foreach($urls as $url): ?>
                                <h6><a href="<?php echo $url['url'] ?>" target="_blank" class="text-white"><?php echo $url['meta_title'] ?></a></h6>
                                <a href="<?php echo \Helpers\App::shortRoute($url['domain'], $url['alias'].$url['custom']) ?>"><?php echo \Helpers\App::shortRoute($url['domain'], $url['alias'].$url['custom']) ?></a>
                                <hr class="border-primary opacity-5">
                            <?php endforeach ?>
                            <div class="d-flex mt-5 text-white">
                                <div class="opacity-8">
                                    <?php ee('Want more options to customize the link, QR codes, branding and advanced metrics?') ?>
                                </div>
                                <div class="ml-auto">
                                    <a href="<?php echo route('register') ?>" class="btn btn-primary btn-xs"><?php ee('Get Started') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php \Helpers\App::ads('resp') ?>
                </div>
            </div>
        </div>
    </section>    
<?php endif ?>


<?php if(config('public_dir')): ?>
    <section class="slice pt-md-8 pb-0 bg-section-secondary">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card bg-section-dark">
                        <div class="card-body">
                            <h4 class="text-white mb-5"><?php ee('Latest links') ?></h4>
                            <?php foreach(\Core\DB::url()->where('public', '1')->orderByDesc('date')->limit(15)->findArray() as $url): ?>
                                <h6><a href="<?php echo $url['url'] ?>" target="_blank" class="text-white"><?php echo $url['meta_title'] ?></a></h6>
                                <a href="<?php echo \Helpers\App::shortRoute($url['domain'], $url['alias'].$url['custom']) ?>"><?php echo \Helpers\App::shortRoute($url['domain'], $url['alias'].$url['custom']) ?></a>
                                <hr class="border-primary opacity-5">
                            <?php endforeach ?>                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php \Helpers\App::ads('resp') ?>
                </div>
            </div>
        </div>
    </section> 
<?php else: ?>


    <section class="slice slice-lg bg-section-secondary">    
        <!---<div class="container pt-6 pt-lg-8"-->
        <div class="container">
            <div class="mb-8 text-center">
                <h2><?php ee('One short link, infinite possibilities.') ?></h2>
                <div class="fluid-paragraph mt-3">
                    <p class="lead lh-180">
                        <?php ee('A short link is a powerful marketing tool when you use it carefully. It is not just a link but a medium between your customer and their destination. A short link allows you to collect so much data about your customers and their behaviors.') ?>
                    </p>
                </div>
            </div>            
            <div class="row mx-lg-n5 mt-sm-4">
                <div class="col-md-4 px-lg-5">
                    <div class="card bg-primary hover-translate-y-n10 shadow-none border-0">
                        <div class="card-body">
                            <div class="pb-4">
                                <div class="icon bg-white rounded-circle icon-shape shadow">
                                    <i data-feather="target"></i>
                                </div>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5 class="text-white"><?php ee('Straight link direct') ?></h5>
                                <p class="text-white opacity-8 mb-0">
                                    <?php ee('Route your customers to website straigh with zero load time on our website.') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 px-lg-5">
                    <div class="card bg-dark hover-translate-y-n10 shadow-none border-0">
                        <div class="card-body">
                            <div class="pb-4">
                                <div class="icon bg-white rounded-circle icon-shape shadow">
                                    <i data-feather="bar-chart-2"></i>
                                </div>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5 class="text-white"><?php ee('In-Depth Analytics') ?></h5>
                                <p class="text-white opacity-8 mb-0">
                                    <?php ee("Share your links to your network and measure data to optimize your marketing campaign's performance. Reach an audience that fits your needs.") ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 px-lg-5">
                    <div class="card bg-dark-dark hover-translate-y-n10 shadow-none border-0">
                        <div class="card-body">
                            <div class="pb-4">
                                <div class="icon bg-white rounded-circle icon-shape shadow">
                                    <i data-feather="star"></i>
                                </div>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5 class="text-white"><?php ee('Digital Experience') ?></h5>
                                <p class="text-white opacity-8 mb-0">
                                    <?php ee("Use various powerful tools increase conversion and provide a non-intrusive experience to your customers without disengaging them.") ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="slice">
        <div class="container">
            <div class="section-process-step">
                <div class="row row-grid justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <div class="card mb-0 ml-lg-5">
                            <div class="card-body p-2">
                                <img src="<?php echo assets('images/qrcodes.png') ?>" alt="<?php ee('Powerful tools that work') ?>" class="img-responsive w-100 py-5">
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-5">
                        <h5 class="h3"><?php ee('QR Codes') ?></h5>
                        <p class="lead my-4">
                            <?php ee('Easy to use, dynamic and customizable QR codes for your marketing campaigns. Analyze statistics and optimize your marketing strategy and increase engagement.') ?>
                        </p>                        
                        <a href="<?php echo route('register') ?>" class="btn btn-primary my-3">
                            <?php ee('Get Started') ?>
                        </a>
                    </div>                    
                </div>
            </div>
        </div>
    </section>
    
    <?php if($testimonials = config('testimonials')): ?>
        <section class="slice bg-section-secondary">
            <div class="container">
                <div class="row my-5 justify-content-center text-center">
                    <div class="col-lg-8 col-md-10">
                        <h2 class="mt-4"><?php ee('What our customers say about us') ?></h2>
                    </div>
                </div>
                <div class="row mx-n2">
                    <?php foreach($testimonials as $testimonial): ?>
                        <div class="col-md-4 px-sm-2">
                            <div class="card bg-section-dark shadow border-0 mb-3">
                                <div class="card-body p-3">
                                    <p class="text-white"><?php echo $testimonial->testimonial ?></p>
                                    <div class="d-flex align-items-center mt-3">
                                        <div>
                                            <?php echo $testimonial->email ? '<div class="h-100"><img src="https://www.gravatar.com/avatar/'.md5(trim($testimonial->email)).'?s=64&d=identicon" class="avatar avatar-sm rounded-circle bg-warning text-white" alt="'.$testimonial->name.'"></div>': '' ?>
                                        </div>
                                        <div class="pl-3">
                                            <span class="h6 text-sm mb-0 text-white"><?php echo $testimonial->name ?>  <?php echo $testimonial->job  ? "<br><small class=\"opacity-8\">{$testimonial->job}</small>" : "" ?></span>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </section>   
    <?php endif ?>
    <?php if (config("homepage_stats")): ?>
    <section class="py-lg-6 bg-section-secondary">
        <div class="container pt-4 position-relative zindex-100">        
            <div class="row mt-4">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-lg-4 col-6 mb-5 mb-lg-0">
                            <div class="text-center">
                                <h3 class="h5 text-capitalize text-primary"><?php ee('Powering') ?></h3>
                                <div class="h1 text-primary">
                                    <span class="counter"><?php echo $count->links ?></span>
                                    <span class="counter-extra">+</span>
                                </div>
                                <h3 class="h6 text-capitalize"><?php ee('Links') ?></h3>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-5 mb-lg-0">
                            <div class="text-center">
                                <h3 class="h5 text-capitalize text-primary"><?php ee('Serving') ?></h3>
                                <div class="h1 text-primary">
                                    <span class="counter"><?php echo $count->clicks ?></span>
                                    <span class="counter-extra">+</span>
                                </div>
                                <h3 class="h6 text-capitalize"><?php ee('Clicks') ?></h3>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-5 mb-lg-0">
                            <div class="text-center">
                                <h3 class="h5 text-capitalize text-primary"><?php ee('Trusted by') ?></h3>
                                <div class="h1 text-primary">
                                    <span class="counter"><?php echo $count->users ?></span>
                                    <span class="counter-extra">+</span>
                                </div>
                                <h3 class="h6 text-capitalize"><?php ee('Happy Customers') ?></h3>
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </div>    
    </section>
    <?php endif ?>    
<?php endif ?>