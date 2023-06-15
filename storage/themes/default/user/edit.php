<form method="post" action="<?php echo route('links.update', [$url->id]) ?>" enctype="multipart/form-data">
    <div class="d-flex mb-5">
        <div>
        <h1 class="h3"><?php ee('Update Link') ?></h1>
        </div>
        <div class="ms-auto">
            <button type="submit" class="btn btn-primary"><?php ee('Update Link') ?></button>
            <a href="<?php echo route('stats', [$url->id]) ?>" class="btn btn-success"><i data-feather="bar-chart-2"></i> <?php ee('Statistics') ?></a>
            <?php if(user()->has('export')): ?>
                <a class="btn btn-success" href="<?php echo route('links.stats.export', [$url->id]) ?>"><i data-feather="download"></i> <?php ee('Export Statistics') ?></a>
            <?php endif ?>
        </div>    
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php echo csrf() ?>
                    <div class="form-group">
                        <label for="url" class="form-label"><?php ee('URL') ?></label>
                        <div class="input-group">
                            <div class="input-group-text  <?php echo user()->pro() ? 'bg-white' : '' ?>"><img src="<?php echo route('link.ico', $url->id) ?>" width="16"></div>
                            <input type="text" class="form-control p-2 border-start-0 ps-0" id="url" name="url" value="<?php echo $url->url ?>" autocomplete="off" <?php echo !user()->pro() ? 'readonly' : '' ?>>
                        </div>
                    </div>  
                    <div id="metatags" class="mt-4 ">
                        <hr>
                        <h4><?php echo e("Meta Tags")?></h4>
                        <div class="row">   
                            <div class="col-lg-4 col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="metaimage" class="form-label"><?php ee('Custom Banner') ?></label>                    
                                    <input type="file" class="form-control p-2" name="metaimage" id="metaimage" placeholder="<?php echo e("Enter your custom meta title")?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="metatitle" class="form-label"><?php ee('Meta Title') ?></label>                    
                                    <input type="text" class="form-control p-2" name="metatitle" id="metatitle" value="<?php echo $url->meta_title ?>" placeholder="<?php echo e("Enter your custom meta title")?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="metadescription" class="form-label"><?php ee('Meta Description') ?></label>                    
                                    <input type="text" class="form-control p-2" name="metadescription" id="metadescription" value="<?php echo $url->meta_description ?>" placeholder="<?php echo e("Enter your custom meta description")?>" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(config("geotarget") && \Core\Auth::user()->has("geo") !== false):?>
                        <div class="mt-4" id="geo">
                            <hr>
                            <div class="d-flex">
                                <h4><?php echo e("Geo Targeting")?></h4>
                                <div class="ms-auto">
                                    <a href="#" class="btn btn-sm btn-primary" data-trigger="addmore" data-for="geo"><?php echo e("+ Add")?></a>
                                </div>
                            </div>
                            <p class="form-text"><?php echo e('If you have different pages for different countries then it is possible to redirect users to that page using the same URL. Simply choose the country and enter the URL.')?></p>                                       
                            <?php foreach($locations as $name => $link):?>
                                <?php $name = explode('-', $name) ?>
                                <div class="row">
                                    <div class="col col-sm-6 mt-3">
                                        <div class="input-group input-select">
                                            <span class="input-group-text bg-white"><i data-feather="globe"></i></span>
                                            <select name="location[]" class="form-control border-start-0 ps-0" data-trigger="getStates" data-toggle="select">
                                                <?php echo \Core\Helper::Country(uppercountryname($name[0]), true) ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-sm-6 mt-3">
                                        <div class="input-group input-select">
                                            <span class="input-group-text bg-white"><i data-feather="globe"></i></span>
                                            <select name="state[]" class="form-control border-start-0 ps-0" data-toggle="select">
                                                <option value="0"><?php ee('All States') ?></option>
                                                <?php foreach(\Helpers\App::states(uppercountryname($name[0])) as $state): ?>
                                                    <option value="<?php echo strtolower($state->name) ?>" <?php echo isset($name[1]) && $state->name == ucwords($name[1]) ? 'selected' : '' ?>><?php echo $state->name ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-sm-12 mt-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i data-feather="link"></i></span>
                                            <input type="text" name="target[]" class="form-control border-start-0 ps-0 p-1" placeholder="<?php echo e("Type the url to redirect user to.")?>" value="<?php echo $link ?>">
                                        </div>
                                    </div>
                                </div>   
                                <p><a href="#" class="btn btn-danger btn-sm mt-1" data-trigger="deletemore"><?php ee('Delete') ?></a></p>
                            <?php endforeach ?>
                            <div class="row d-none" data-toggle="addable" data-label="geo" data-states="<?php echo route('server.states') ?>">
                                <div class="col col-sm-6 mt-3">
                                    <div class="input-group input-select">
                                        <span class="input-group-text bg-white"><i data-feather="globe"></i></span>
                                        <select name="location[]" class="form-control border-start-0 ps-0" data-trigger="getStates" data-toggle="select">
                                            <?php echo \Core\Helper::Country('United States', true) ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col col-sm-6 mt-3">
                                    <div class="input-group input-select">
                                        <span class="input-group-text bg-white"><i data-feather="globe"></i></span>
                                        <select name="state[]" class="form-control border-start-0 ps-0" data-toggle="select">
                                            <option value="0"><?php ee('All States') ?></option>
                                            <?php foreach(\Helpers\App::states('United States') as $state): ?>
                                                <option value="<?php echo strtolower($state->name) ?>"><?php echo $state->name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col col-sm-12 mt-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i data-feather="link"></i></span>
                                        <input type="text" name="target[]" class="form-control border-start-0 ps-0 p-1" placeholder="<?php echo e("Type the url to redirect user to.")?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                    <?php if(config("devicetarget") && \Core\Auth::user()->has("device") !== false):?>
                        <div class="row mt-4" id="device">
                            <hr>
                            <div class="d-flex">
                                <h4><?php echo e("Device Targeting")?></h4>
                                <div class="ms-auto">
                                    <a href="#" class="btn btn-sm btn-primary" data-trigger="addmore" data-for="device"><?php echo e("+ Add")?></a>
                                </div>
                            </div>
                            <p class="form-text">
                                <?php echo e('If you have different pages for different devices (such as mobile, tablet etc) then it is possible to redirect users to that page using the same short URL. Simply choose the device and enter the URL.')?>
                            </p>
                            <?php foreach($url->devices as $name => $link):?>
                                <div class="row">
                                    <div class="col-sm-6 mt-3">
                                        <div class="input-group input-select">
                                            <span class="input-group-text bg-white"><i data-feather="smartphone"></i></span>
                                            <select name="device[]" class="form-control border-start-0 ps-0" data-toggle="select">
                                                <?php echo \Core\Helper::devices($name) ?>
                                            </select>
                                        </div>              
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i data-feather="link"></i></span>
                                            <input type="text" name="dtarget[]" class="form-control border-start-0 ps-0" placeholder="<?php echo e("Type the url to redirect user to.")?>" value="<?php echo $link ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                            <div class="row d-none" data-toggle="addable" data-label="device">
                                    <div class="col-sm-6 mt-3">
                                        <div class="input-group input-select">
                                            <span class="input-group-text bg-white"><i data-feather="smartphone"></i></span>
                                            <select name="device[]" class="form-control border-start-0 ps-0" data-toggle="select">
                                                <?php echo \Core\Helper::devices() ?>
                                            </select>
                                        </div>              
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i data-feather="link"></i></span>
                                            <input type="text" name="dtarget[]" class="form-control border-start-0 ps-0" placeholder="<?php echo e("Type the url to redirect user to.")?>" value="">
                                        </div>
                                    </div>
                                </div>
                        </div>                        
                    <?php endif ?>
                    <?php if( \Core\Auth::user()->has("language") !== false):?>
                        <div class="row mt-4" id="language">
                            <hr>
                            <div class="d-flex">
                                <h4><?php echo e("Language Targeting")?></h4>
                                <div class="ms-auto">
                                    <a href="#" class="btn btn-sm btn-primary" data-trigger="addmore" data-for="language"><?php echo e("+ Add")?></a>
                                </div>
                            </div>
                            <p class="form-text">
                                <?php echo e('If you have different pages for different languages then it is possible to redirect users to that page using the same short URL. Simply choose the language and enter the URL.')?>
                            </p>
                            <?php foreach($url->languages as $name => $link):?>
                                <div class="row">
                                    <div class="col-sm-6 mt-3">
                                        <div class="input-group input-select">
                                            <span class="input-group-text bg-white"><i data-feather="type"></i></span>
                                            <select name="language[]" class="form-control border-start-0 ps-0" data-toggle="select">
                                                <?php echo \Helpers\App::languagelist($name) ?>
                                            </select>
                                        </div>              
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i data-feather="link"></i></span>
                                            <input type="text" name="ltarget[]" class="form-control border-start-0 ps-0" placeholder="<?php echo e("Type the url to redirect user to.")?>" value="<?php echo $link ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                            <div class="row d-none" data-toggle="addable" data-label="language">
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-select">
                                        <span class="input-group-text bg-white"><i data-feather="type"></i></span>
                                        <select name="language[]" class="form-control border-start-0 ps-0" data-toggle="select">
                                            <?php echo \Helpers\App::languagelist($name) ?>
                                        </select>
                                    </div>              
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i data-feather="link"></i></span>
                                        <input type="text" name="ltarget[]" class="form-control border-start-0 ps-0" placeholder="<?php echo e("Type the url to redirect user to.")?>" value="">
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    <?php endif ?>
                    <?php if(\Core\Auth::user()->has("pixels") !== false):?>
                    <div id="pixels" class="mt-4">
                        <hr>
                        <h4><?php echo e("Targeting Pixels")?></h4>
                        <p class="form-text"><?php echo e('Add your targeting pixels below from the list. Please make sure to enable them in the pixels settings.')?></p>
                        <div class="input-group input-select">
                            <span class="input-group-text bg-white"><i data-feather="filter"></i></span>
                            <select name="pixels[]" data-placeholder="Your Pixels" multiple data-toggle="select">
                                <?php foreach(\Core\Auth::user()->pixels() as $type => $pixels): ?>     
                                    <optgroup label="<?php echo ucwords($type) ?>">
                                    <?php foreach($pixels as $pixel): ?>
                                        <option value="<?php echo $pixel->type ?>-<?php echo $pixel->id ?>" <?php echo in_array($pixel->type.'-'.$pixel->id, $url->pixels) ? 'selected': '' ?>><?php echo $pixel->name ?></option>                                
                                    <?php endforeach ?>
                                    </optgroup>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <?php endif ?>
                    <?php if (\Core\Auth::user()->has("parameters") !== false): ?>
                    <div class="mt-4" id="parameters">
                        <hr>
                        <div class="d-flex">
                            <h4><?php echo e("Parameter Builder")?></h4>
                            <div class="ms-auto">
                                <a href="#" class="btn btn-sm btn-primary" data-trigger="addmore" data-for="parameters"><?php echo e("+ Add")?></a>
                            </div>
                        </div>
                        <p class="form-text">
                            <?php echo e("You can add custom parameters like UTM to the link above using this tool. Choose the parameter name and then assign a value. These will be added during redirection.")?>
                        </p>
                        <?php foreach($url->parameters as $name => $value):?>
                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i data-feather="list"></i></span>
                                    <input type="text" name="paramname[]" class="form-control p-2 border-start-0 ps-0" data-trigger="autofillparam" value="<?php echo $name ?>" placeholder="<?php echo e("Parameter name")?>">
                                </div>             
                            </div>             
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i data-feather="edit"></i></span>
                                    <input type="text" name="paramvalue[]" class="form-control p-2 border-start-0 ps-0" value="<?php echo $value ?>" placeholder="<?php echo e("Parameter value")?>">
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                        <div class="row d-none" data-toggle="addable" data-label="parameters">
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i data-feather="list"></i></span>
                                    <input type="text" name="paramname[]" class="form-control p-2 border-start-0 ps-0" data-trigger="autofillparam" placeholder="<?php echo e("Parameter name")?>">
                                </div>             
                            </div>             
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i data-feather="edit"></i></span>
                                    <input type="text" name="paramvalue[]" class="form-control p-2 border-start-0 ps-0" placeholder="<?php echo e("Parameter value")?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif ?>
                    <button type="submit" class="btn btn-primary mt-4"><?php ee('Update Link') ?></button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php if($url->meta_image): ?>
                <div class="card">
                    <img class="card-img-top" src="<?php echo uploads($url->meta_image, 'images') ?>" alt="<?php echo $url->meta_title ?>">
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?php echo $url->meta_title ?></h5>
                        <p class="card-text text-muted"><?php echo $url->meta_description ?></p>
                    </div>
                </div>
            <?php endif ?>            
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <img src="<?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>/qr" alt="<?php echo $url->meta_title ?>" width="60">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <span class="mb-3 d-block"><i class="fa fa-link me-2"></i> <small data-href="<?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>"><?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?></small>
                            <a href="#copy" class="copy inline-copy" data-clipboard-text="<?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>"><small><?php echo e("Copy")?></small></a></span>
                            
                            <i class="fa fa-qrcode me-2"></i> <small data-href="<?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>/qr"><?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>/qr</small>
                            <a href="#copy" class="copy inline-copy" data-clipboard-text="<?php echo Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>/qr"><small><?php echo e("Copy")?></small></a>                       
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">       
                    <?php if($domains = \Helpers\App::domains()): ?>
                        <div class="form-group mb-3 rounded input-select">
                            <label for="domain" class="form-label"><?php ee('Domain') ?></label>
                            <select name="domain" id="domain" class="form-control border-start-0 ps-0" data-toggle="select">
                                <?php foreach($domains as $domain): ?>
                                    <option value="<?php echo $domain ?>" <?php echo $url->domain == $domain ? 'selected' : '' ?>><?php echo $domain ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <?php if($redirects = \Helpers\App::redirects()): ?>
                        <div class="form-group mb-3 rounded input-select">
                            <label for="type" class="form-label"><?php ee('Redirect') ?></label>
                            <select name="type" id="type" class="form-control border-start-0 ps-0" data-toggle="select">
                                <?php foreach($redirects as $name => $redirect): ?>
                                    <optgroup label="<?php echo $name ?>">
                                    <?php foreach($redirect as $id => $name): ?>
                                        <option value="<?php echo $id ?>" <?php echo $url->type == $id ? 'selected' : '' ?>><?php echo $name ?></option>
                                    <?php endforeach ?>
                                    </optgroup>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>      
                    <?php if(!$url->custom):?>             
                    <div class="form-group mb-3">
                        <label for="alias" class="form-label"><?php ee('Alias') ?></label>
                        <div class="input-group input-select">
                            <div class="input-group-text"><i data-feather="globe"></i></div>
                            <input type="text" class="form-control p-2 border-start-0 ps-0" id="alias" value="<?php echo $url->alias ?>" disabled autocomplete="off">
                        </div>
                    </div>
                    <?php endif ?>
                    <?php if (\Core\Auth::user()->has("alias") !== false): ?>
                    <div class="form-group mb-3">
                        <label for="custom" class="form-label"><?php ee('Custom') ?></label>
                        <div class="input-group">
                            <div class="input-group-text bg-white"><i data-feather="globe"></i></div>
                            <input type="text" class="form-control p-2 border-start-0 ps-0" name="custom" id="custom" placeholder="<?php echo e("Type your custom alias here")?>" autocomplete="off" value="<?php echo $url->custom ?>">
                        </div>
                    </div>    
                    <?php endif ?>                  
                    <div class="form-group mb-3">
                        <label for="pass" class="form-label"><?php ee('Password Protection') ?></label>
                        <div class="input-group">
                            <div class="input-group-text bg-white"><i data-feather="lock"></i></div>
                            <input type="text" class="form-control p-2 border-start-0 ps-0" name="pass" id="pass" placeholder="<?php echo e("Type your password here")?>" autocomplete="off" value="<?php echo $url->pass ?>">
                        </div>
                    </div>                                     
                    <div class="form-group mb-3">
                        <label for="expiry" class="form-label"><?php ee('Link Expiration') ?></label>
                        <div class="input-group">
                            <div class="input-group-text bg-white"><i data-feather="lock"></i></div>
                            <input type="text" class="form-control p-2 border-start-0 ps-0" data-toggle="datepicker" name="expiry" id="expiry" placeholder="<?php echo e("MM/DD/YYYY")?>" autocomplete = "off" value="<?php echo $url->expiry ?>">
                        </div>
                    </div>                        
                    <div class="form-group mb-3">
                        <label for="expiry" class="form-label"><?php echo e("Description")?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i data-feather="tag"></i></span>
                            <input type="text" class="form-control p-2 border-start-0 ps-0" name="description" id="description" placeholder="<?php echo e("Type your description here")?>" autocomplete = "off" value="<?php echo $url->description ?>">
                        </div>
                    </div>
                    <div class="form-group mb-3 rounded input-select">
                        <label for="type" class="form-label"><?php ee('Campaign') ?></label>
                        <select name="bundle" id="bundle" class="form-control border-start-0 ps-0" data-toggle="select">
                            <option value="0"><?php echo e('None') ?></option>
                            <?php foreach(\Core\DB::bundle()->where('userid', user()->rID())->find() as $campaign): ?>
                                <option value="<?php echo $campaign->id ?>" <?php echo $url->bundle == $campaign->id ? 'selected' : '' ?>><?php echo $campaign->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <label for="channels" class="form-label d-block mb-2"><?php ee('Channels') ?></label>
                    <div class="form-group rounded input-select">
                        <select name="channels[]" id="channels" class="form-control" multiple data-toggle="select">
                            <?php foreach(\Core\DB::channels()->where('userid', user()->rID())->findArray() as $channel): ?>
                                <option value="<?php echo $channel['id'] ?>" <?php echo in_array($channel['id'], $channels) ? 'selected' : '' ?>><?php echo $channel['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div> 
                </div>
            </div>
        </div>    
    </div>
</form>