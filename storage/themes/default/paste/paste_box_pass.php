
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
                        <div class="form-group">
                            <label class="form-control-label" for="paste-author"><?php ee("Name") ?></label>
                            <input class="form-control form-control-lg" type="text" name="pasteAlias" id="pasteAlias" value="<?php echo $datas->alias ?>" hidden>
                        </div>
                        <form id="form-paste-pass" method="post" action="<?php echo route('paste.paste_pass') ?>" data-trigger="paste-form-pass">
                            <div class="form-group">
                                <label class="form-control-label" for="paste-content"><?php ee("Password") ?></label>
                                <input class="form-control form-control-lg" type="text" id="pastePass" name="pastePass" placeholder="<?php ee("Password protection") ?>">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>