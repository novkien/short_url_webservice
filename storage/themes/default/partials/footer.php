<footer class="position-relative" id="footer-main">
    <div class="footer pt-lg-7 footer-dark bg-section-dark">                               
        <div class="container pt-4">
            <hr class="divider divider-fade divider-dark my-5">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <p class="mt-4 text-sm opacity-8 pr-lg-4"><?php echo config('description') ?></p>
                    <ul class="nav mt-4">
                        <?php if($facebook = config('facebook')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $facebook ?>" target="_blank">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if($twitter = config('twitter')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $twitter ?>" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if($instagram = config('sociallinks')->instagram): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $instagram ?>" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if($linkedin = config('sociallinks')->linkedin): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $linkedin ?>" target="_blank">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>
                <div class="col-lg-4 col-6 col-sm-6 ml-lg-auto mb-5 mb-lg-0">
                    <h6 class="heading mb-3"><?php ee('Solutions') ?></h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo route('page.qr') ?>"><?php ee('QR Codes') ?></a></li>
                        <?php ///tính năng menu ?>

                    </ul>
                </div>
                <div class="col-lg-4 col-6 col-sm-6 mb-5 mb-lg-0">
                    <h6 class="heading mb-3"><?php ee('Company') ?></h6>
                    <ul class="list-unstyled">
                        <li class="nav-item"><a class="nav-link" href="<?php echo route('faq') ?>"><?php ee('Help') ?></a></li>
                        <?php if(config('contact')): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo route('contact') ?>"><?php ee('Contact Us') ?></a></li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
            <hr class="divider divider-fade divider-dark my-4">
            <div class="row align-items-center justify-content-md-between pb-4">
                <div class="col-md-4">
                    <div class="copyright text-sm font-weight-bold text-center text-md-left">                                
                        &copy; <?php echo date("Y") ?> <a href="<?php echo config('url') ?>" class="font-weight-bold"><?php echo config('title') ?></a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>