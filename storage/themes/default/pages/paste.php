
<a href="<?php echo route('home') ?>" class="btn btn-white btn-icon-only rounded-circle zindex-101 left-4 d-none d-lg-inline-flex" style="top: -3rem!important;" data-toggle="tooltip" data-placement="right" title="Go back">
    <span class="btn-inner--icon">
        <i data-feather="arrow-left"></i>
    </span>
</a>
<section class="slice pb-0 slice-lg <?php echo \Helpers\App::themeConfig('homestyle', 'light', 'bg-white', 'bg-section-dark') ?>" <?php echo themeSettings::config('homecolor') ?>>
    <div class="container d-flex align-items-center" data-offset-top="#navbar-main">
        <div class="col py-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-7 col-lg-7 text-center">
                    <h1 class="display-4 <?php echo \Helpers\App::themeConfig('homestyle', 'light', 'text-dark', 'text-white') ?> mb-2"><?php ee('Paste box') ?></h1>
                    <p class="lh-190 <?php echo \Helpers\App::themeConfig('homestyle', 'light', 'text-dark', 'text-white') ?>"><?php ee('Paste box help you to save a huge of text, easy to share.') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="slice slice-lg pt-0 bg-section-secondary" id="sct-form-paste">
    <div class="container position-relative zindex-100">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form id="form-paste" method="post" action="<?php echo route('paste.paste_send') ?>" data-trigger="paste-form">
                            <div class="form-group">
                                <label class="form-control-label" for="paste-author"><?php ee("Name") ?></label>
                                <input class="form-control form-control-lg" type="text" name="pasteAuthor" placeholder="<?php ee("Name") ?>" id="pasteAuthor" name="name" value="<?php echo \Core\Auth::logged() ? \Core\Auth::user()->username : '' ?>">
                            </div>
<!--                             <div class="form-group">
                                <label class="form-control-label" for="paste-content"><span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg" type="text" placeholder="<?php ee("Email") ?>" name="paste-content" id="paste-content" value="" data-error="<?php ee('Please enter a valid email.') ?>" required>
                            </div>    -->
                            <div class="form-group">
                                <label class="form-control-label" for="paste-content"><?php ee("Content") ?> <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-lg" placeholder="<?php ee('If you have any questions, feel free to contact us so we can help you') ?>" rows="10" id="pasteContent" name="pasteContent" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="paste-life"><?php ee("Life time") ?></label>
                                <select class="form-control form-control-lg" name="pasteLife" id="pasteLife">
                                    <option value="forever"><?php ee("Forever") ?></option>
                                    <option value="onehour"><?php ee("1 Hour") ?></option>
                                    <option value="oneday"><?php ee("1 Day") ?></option>
                                    <option value="oneweek"><?php ee("1 Week") ?></option>
                                    <option value="onemonth"><?php ee("1 Month") ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="paste-pass"><?php ee("Password") ?></label>
                                <input class="form-control form-control-lg" type="text" name="pastePass" id="pastePass" value="" placeholder="<?php ee("Empty for no password protection") ?>">
                            </div>




                            <?php echo \Helpers\Captcha::display() ?>
                            <div class="text-center">
                                <?php echo csrf() ?>
                                <button type="submit" class="btn btn-block btn-lg btn-primary mt-4"><?php ee('Send') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>