<section class="slice slice-lg bg-section-secondary" id="sct-form-paste">
    <div class="container position-relative zindex-100">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form id="form-paste" method="post" action="<?php echo route('paste_send') ?>" data-trigger="paste-form">
                            <div class="form-group">
                                <label class="form-control-label" for="paste-author"><?php ee("Name") ?></label>
                                <input class="form-control form-control-lg" type="text" placeholder="<?php ee("Name") ?>" id="paste-author" name="name" value="<?php echo \Core\Auth::logged() ? \Core\Auth::user()->username : '' ?>" data-error="<?php ee('Please enter a valid name.') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="paste-content"><span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg" type="text" placeholder="<?php ee("Email") ?>" name="content" id="paste-content" value="" data-error="<?php ee('Please enter a valid email.') ?>" required>
                            </div>                   
                            <div class="form-group">
                                <label class="form-control-label" for="contact-message"><?php ee("Message") ?> <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-lg" placeholder="<?php ee('If you have any questions, feel free to contact us so we can help you') ?>" rows="10" min="10" data-error="<?php ee('The message is empty or too short.') ?>" id="content-message" name="message" required></textarea>
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