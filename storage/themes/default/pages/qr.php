<h1 class="h3 pt-9 pb-5"><div class="text-center"><?php ee('Create QR') ?></div></h1>
<a href="<?php echo route('home') ?>" class="btn btn-white btn-icon-only rounded-circle position-absolute zindex-101 left-4 top-4 d-none d-lg-inline-flex" data-toggle="tooltip" data-placement="right" title="Go back">
    <span class="btn-inner--icon">
        <i data-feather="arrow-left"></i>
    </span>
</a>
<section class="slice slice-lg bg-section-secondary">
    <div class="container">
        <form action="<?php echo route('qr.save') ?>" data-trigger="saveqr" method="post" enctype="multipart/form-data">
            <?php echo csrf() ?>
            <input type="hidden" name="type" value="link">
            <div class="row row-grid">
                <div class="col-lg-3">           
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><?php ee('Basic Features') ?></h5>
                        </div>                
                        <div class="list-group list-group-flush list-group-dynamic">
                            <a class="list-group-item list-group-item-action active" data-bs-toggle="collapse" data-bs-parent="#qrbuilder" href="#link"><i class="me-2" data-feather="link"></i> <?php ee('Link') ?></a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#qrbuilder" href="#file"><i class="me-2 fa fa-file fa-lg"></i> <?php ee('File') ?></a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#qrbuilder" href="#text"><i class="me-2" data-feather="type"></i> <?php ee('Text') ?></a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#qrbuilder" href="#wifi"><i class="me-2" data-feather="wifi"></i> <?php ee('WiFi') ?></a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-parent="#qrbuilder" href="#staticvcard"><i class="me-2" data-feather="user"></i> <?php ee('Static vCard') ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-body">
                        <div class="form-group">
                            <label for="text" class="form-label"><?php ee('QR Code Name') ?></label>
                            <input type="text" class="form-control p-2" name="name" placeholder="e.g. For Something">
                        </div>
                    </div>
                    <div class="card" id="qrbuilder">
                        <div class="collapse" id="text">
                            <div class="card-header">
                                <h5 class="card-title fw-bold"><i class="me-2" data-feather="type"></i> <?php ee('Text') ?></h5>
                            </div>
                            <div class="card-body pb-0 pt-0">
                                <div class="form-group">
                                    <textarea class="form-control" name="text" placeholder="<?php ee('Your Text') ?>"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="link">
                            <div class="card-header">
                                <h5 class="card-title fw-bold"><i class="me-2" data-feather="link"></i> <?php ee('Link') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="text" class="form-label"><?php ee('Your Link') ?></label>
                                    <input type="text" class="form-control p-2" name="link" placeholder="https://">
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="phone">
                            <div class="card-header">
                                <h5 class="card-title fw-bold"><i class="me-2" data-feather="phone"></i> <?php ee('Phone') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="text" class="form-label"><?php ee('Phone Number') ?></label>
                                    <input type="text" class="form-control p-2" name="phone" placeholder="e.g. 123456789">
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="file">
                            <div class="card-header">
                                <h5 class="card-title fw-bold"><i class="me-2 fa fa-file"></i> <?php ee('File Upload (Image or PDF)') ?></h5>
                            </div>
                            <div class="card-body">
                                <p><?php ee('This can be used to upload an image or a PDF. Most common uses are restaurant menu, promotional poster and resume.') ?></p>
                                <div class="form-group mb-3">
                                    <label for="file" class="form-label"><?php ee('File') ?></label>
                                    <input type="file" class="form-control p-2" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf">
                                    <p class="form-text"><?php ee('Acceptable file: jpg, png, gif, pdf. Max 2MB.') ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="staticvcard">
                            <div class="card-header">
                                <h5 class="card-title fw-bold"><i class="me-2" data-feather="user"></i> <?php ee('vCard') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('First Name') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[fname]" placeholder="e.g. John">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Last Name') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[lname]" placeholder="e.g. Doe">
                                </div> 
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Organization') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[org]" placeholder="e.g. Internet Inc">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Phone') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[phone]" placeholder="e.g. +112345689">
                                </div>   
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Email') ?></label>
                                    <input type="email" class="form-control" name="staticvcard[email]" placeholder="e.g. someone@domain.com">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Website') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[site]" placeholder="e.g. https://domain.com">
                                </div> 
                                <div class="btn-group ms-auto">
                                    <button type="button" class="btn btn-primary btn-sm text-white" data-bs-toggle="collapse" data-bs-target="#vcard-address">+ <?php ee('Address') ?></button>
                                </div>
                                <div id="vcard-address" class="collapse">
                                    <hr>
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Street') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[street]" placeholder="e.g. 123 My Street">
                                    </div>    
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('City') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[city]" placeholder="e.g. My City">
                                    </div> 
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('State') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[state]" placeholder="e.g. My State">
                                    </div> 
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Zipcode') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[zip]" placeholder="e.g. 123456">
                                    </div> 
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Country') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[country]" placeholder="e.g. My Country">
                                    </div>
                                </div>
                                <div id="vcard-social" class="collapse">
                                    <hr>
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Facebook') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[facebook]" placeholder="e.g. https://www.facebook.com/myprofile">
                                    </div>    
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Twitter') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[twitter]" placeholder="e.g. https://www.twitter.com/myprofile">
                                    </div> 
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Instagram') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[instagram]" placeholder="e.g. https://www.instagram.com/myprofile">
                                    </div> 
                                    <div class="form-group mb-3">
                                        <label for="text" class="form-label"><?php ee('Linekdin') ?></label>
                                        <input type="text" class="form-control p-2" name="staticvcard[linkedin]" placeholder="e.g. https://www.linkedin.com/myprofile">
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="wifi">
                            <div class="card-header">
                                <h5 class="card-title fw-bold"><i class="me-2" data-feather="wifi"></i> <?php ee('WiFi') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Network SSID') ?></label>
                                    <input type="text" class="form-control p-2" name="wifi[ssid]" placeholder="e.g 123456789">
                                </div>                        
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Password') ?></label>
                                    <input type="text" class="form-control p-2" name="wifi[pass]" placeholder="Optional">
                                </div>
                                <div class="form-group">
                                    <label for="text" class="form-label"><?php ee('Encryption') ?></label>
                                    <select name="wifi[encryption]" class="form-control">
                                        <option value="wep">WEP</option>
                                        <option value="wpa">WPA/WPA2</option>
                                    </select>                        
                                </div>
                            </div>                     
                        </div>
                        <div id="singlecolor" class="collapse hide"><?php ///do fucking not delete ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="bg"><?php ee("Background") ?></label><br>
                                            <input type="text" name="bg" id="bg" value="rgb(255,255,255)">
                                        </div>
                                    </div>	
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="fg"><?php ee("Foreground") ?></label><br>
                                            <input type="text" name="fg" id="fg" value="rgb(0,0,0)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <p class="mt-4 text-center"><button type="submit" class="btn btn-primary"><?php ee('Generate QR') ?></button></p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><?php ee('WiFi') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php ee('Fast connect with your wifi access point') ?>
                        </div>   
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><?php ee('vCard') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php ee('Easy to share your contact') ?>
                        </div>                    
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><?php ee('Text') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php ee('Using plain text format') ?>
                        </div>        
                    </div>
                    <div class="card">           
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><?php ee('Link') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php ee('Share your link') ?>
                        </div>        
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><?php ee('File') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php ee('QR code scan to access your file, accept images and pdf') ?>
                        </div>  
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>