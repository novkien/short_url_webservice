<h1 class="h3 pt-10 pb-5"><div class="text-center"><?php ee('Edit QR') ?></div></h1>
<?php sleep(3) ?>
<section class="slice slice-lg bg-section-dark p-7">
    <form action="<?php echo route('qr.update', [$qr->id]) ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf() ?>
        <input type="hidden" name="type" value="<?php echo $qr->data->type ?>">
        <div class="row">        
            <div class="col-md-9">
                <div class="card card-body">
                    <div class="form-group">
                        <label for="text" class="form-label"><?php ee('QR Code Name') ?></label>
                        <input type="text" class="form-control p-2" name="name" value="<?php echo $qr->name ?>" placeholder="e.g. For Instagram">
                    </div>
                    <?php /* if($domains): ?>
                    <div class="form-group input-select mt-4">
                        <label class="form-label"><?php ee('Domain') ?> <small class="text-danger"><?php ee('If you change the domain name, the QR code will change!') ?></small></label>
                        <div class="d-flex">
                            <div>
                                <select name="domain" id="domain" class="form-control p-2" data-toggle="select">
                                    <?php foreach($domains as $domain): ?>
                                        <option value="<?php echo $domain ?>" <?php echo $url && $url->domain == $domain ? 'selected' : '' ?>><?php echo $domain ?></option>
                                    <?php endforeach ?>
                                </select>
                                <p class="form-text"><?php ee('Choose domain to generate the link with when using dynamic QR codes. Not applicable for static QR codes.') ?></p>
                            </div>
                        </div>
                    </div>       
                    <?php endif */ ?>         
                </div>                  
                <div class="card" id="qrbuilder">
                    <?php if($qr->data->type == 'text'): ?>
                    <div id="text">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><i class="me-2" data-feather="type"></i> <?php ee('Text') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="text" class="form-label"><?php ee('Your Text') ?></label>
                                <textarea class="form-control" name="text" placeholder="<?php ee('Your Text') ?>"><?php echo $qr->data->data ?></textarea>
                            </div>
                        </div>
                    </div>
                    <?php endif ?>
                    <?php if($qr->data->type == 'link'): ?>
                    <div id="link">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><i class="me-2" data-feather="link"></i> <?php ee('Link') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="text" class="form-label"><?php ee('Your Link') ?></label>
                                <input type="text" class="form-control p-2" name="link" value="<?php echo $qr->data->data ?>" placeholder="https://"></input>
                            </div>
                        </div>
                    </div>  
                    <?php endif ?>
                    <?php if($qr->data->type == 'staticvcard'): ?>
                    <div id="staticvcard">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><i class="me-2" data-feather="user"></i> <?php ee('vCard') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('First Name') ?></label>
                                <input type="text" class="form-control p-2" name="staticvcard[fname]" value="<?php echo $qr->data->data->fname ?>" placeholder="e.g. John"></input>
                            </div>
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Last Name') ?></label>
                                <input type="text" class="form-control p-2" name="staticvcard[lname]" value="<?php echo $qr->data->data->lname ?>" placeholder="e.g. Doe"></input>
                            </div> 
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Organization') ?></label>
                                <input type="text" class="form-control p-2" name="staticvcard[org]" value="<?php echo $qr->data->data->org ?>" placeholder="e.g. Internet Inc"></input>
                            </div>
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Phone') ?></label>
                                <input type="text" class="form-control p-2" name="staticvcard[phone]" value="<?php echo $qr->data->data->phone ?>" placeholder="e.g. +112345689"></input>
                            </div>   
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Email') ?></label>
                                <input type="email" class="form-control" name="staticvcard[email]" value="<?php echo $qr->data->data->email ?>" placeholder="e.g. someone@domain.com"></input>
                            </div>
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Website') ?></label>
                                <input type="text" class="form-control p-2" name="staticvcard[site]" value="<?php echo $qr->data->data->site ?>" placeholder="e.g. https://domain.com"></input>
                            </div> 
                            <div class="btn-group ms-auto">
                                <button type="button" class="btn btn-primary btn-sm text-white" data-bs-toggle="collapse" data-bs-target="#staticvcard-address">+ <?php ee('Address') ?></button>
                                <button type="button" class="btn btn-primary btn-sm text-white" data-bs-toggle="collapse" data-bs-target="#staticvcard-social">+ <?php ee('Social') ?></button>
                            </div>
                            <div id="staticvcard-address" class="collapse">
                                <hr>
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Street') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[street]" value="<?php echo $qr->data->data->street ?>" placeholder="e.g. 123 My Street"></input>
                                </div>    
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('City') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[city]" value="<?php echo $qr->data->data->city ?>" placeholder="e.g. My City"></input>
                                </div> 
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('State') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[state]" value="<?php echo $qr->data->data->state ?>" placeholder="e.g. My State"></input>
                                </div> 
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Zipcode') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[zip]" value="<?php echo $qr->data->data->zip ?>" placeholder="e.g. 123456"></input>
                                </div> 
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Country') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[country]" value="<?php echo $qr->data->data->country ?>" placeholder="e.g. My Country"></input>
                                </div>
                            </div>
                            <div id="staticvcard-social" class="collapse">
                                <hr>
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Facebook') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[facebook]" value="<?php echo $qr->data->data->facebook ?>" placeholder="e.g. https://www.facebook.com/myprofile"></input>
                                </div>    
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Twitter') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[twitter]" value="<?php echo $qr->data->data->twitter ?>" placeholder="e.g. https://www.twitter.com/myprofile"></input>
                                </div> 
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Instagram') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[instagram]" value="<?php echo $qr->data->data->instagram ?>" placeholder="e.g. https://www.instagram.com/myprofile"></input>
                                </div> 
                                <div class="form-group mb-3">
                                    <label for="text" class="form-label"><?php ee('Linekdin') ?></label>
                                    <input type="text" class="form-control p-2" name="staticvcard[linkedin]" value="<?php echo $qr->data->data->linkedin ?>" placeholder="e.g. https://www.linkedin.com/myprofile"></input>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <?php endif ?>
                    <?php if($qr->data->type == 'wifi'): ?>
                    <div id="wifi">
                        <div class="card-header">
                            <h5 class="card-title fw-bold"><i class="me-2" data-feather="wifi"></i> <?php ee('WiFi') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Network SSID') ?></label>
                                <input type="text" class="form-control p-2" name="wifi[ssid]" placeholder="e.g 123456789" value="<?php echo $qr->data->data->ssid ?>"></input>
                            </div>                        
                            <div class="form-group mb-3">
                                <label for="text" class="form-label"><?php ee('Password') ?></label>
                                <input type="text" class="form-control p-2" name="wifi[pass]" placeholder="Optional" value="<?php echo $qr->data->data->pass ?>"></input>
                            </div>
                            <div class="form-group">
                                <label for="text" class="form-label"><?php ee('Encryption') ?></label>
                                <select name="wifi[encryption]" class="form-control">
                                    <option value="wep" <?php echo $qr->data->data->encryption == "wep" ? 'selected' : '' ?>>WEP</option>
                                    <option value="wpa" <?php echo $qr->data->data->encryption == "wpa" ? 'selected' : '' ?>>WPA/WPA2</option>
                                </select>                        
                            </div>
                        </div>                     
                    </div>    
                    <?php endif ?>
                    <?php if($qr->data->type =='file'): ?>
                        <div class="card-header">
                            <div class="d-flex">
                                <h5 class="card-title fw-bold"><i class="me-2 fa fa-file"></i> <?php ee('File Upload (Image or PDF)') ?></h5>
                                <div class="ms-auto">
                                    <a href="<?php echo uploads('files/'.$qr->data->data, 'qr') ?>" class="btn btn-primary" target="_blank"><?php ee('View File') ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><?php ee('This can be used to upload an image or a PDF. Most common uses are restaurant menu, promotional poster and resume.') ?></p>
                            <div class="form-group mb-3">
                                <label for="file" class="form-label"><?php ee('File') ?></label>
                                <input type="file" class="form-control p-2" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf">
                                <p class="form-text"><?php ee('Acceptable file: jpg, png, gif, pdf. Max 2MB.') ?></p>
                            </div>
                        </div>                    
                    <?php endif ?>
                </div>
                <div class="card">
                    <div class="card-header mt-2">
                        <h5 class="card-title fw-bold"><i data-feather="plus-circle" class="me-2"></i> <a href="" class="align-middle" data-bs-toggle="collapse" role="button" data-bs-target="#colors"><?php ee('Colors') ?></a></h5>
                    </div>				
                    <div class="card-body collapse" id="colors">
                        <div id="singlecolor" class="collapse <?php echo isset($qr->data->color) ? 'hide' : '' ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="bg"><?php ee("Background") ?></label><br>
                                        <input type="text" name="bg" id="bg" value="<?php echo isset($qr->data->color) ? $qr->data->color->bg : '' ?>">
                                    </div>
                                </div>	
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="fg"><?php ee("Foreground") ?></label><br>
                                        <input type="text" name="fg" id="fg" value="<?php echo isset($qr->data->color) ? $qr->data->color->fg : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php route('qr.generate', [$qr->alias])  ?>
            <div class="col-md-3 justify-content-right">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold"><?php ee('QR Code') ?></h5>
                    </div>
                    <div class="card-body">
                        <div id="return-ajax">
                            <img src="<?php echo route('qr.generate', [$qr->alias]) ?>" class="img-responsive w-100">
                        </div>    
                        <button type="submit" class="btn btn-primary mt-3"><?php ee('Update') ?></button>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="form-text">
                        <?php ee("You will be able to download the QR code in PDF or SVG after it has been generated.") ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>