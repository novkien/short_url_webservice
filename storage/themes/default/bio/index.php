<div class="d-flex">
    <div>
        <h1 class="h3 mb-5"><?php ee('Bio Pages') ?></h1>
    </div>
    <div class="ms-auto">
        <?php if(user()->teamPermission('bio.create')): ?>
            <a href="<?php echo route('bio.create') ?>" class="btn btn-primary"><i data-feather="plus"></i> <?php ee('Create Bio') ?></a>
        <?php endif ?>
    </div>    
</div>
<div class="row">
    <div class="col-md-9">
        <?php if($bios): ?>
            <div class="row">                    
                <?php foreach($bios as $bio): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card flex-fill h-100">
                            <div class="card-body position-relative">
                                <div class="position-absolute top-0 end-0">
                                    <button type="button" class="btn btn-default bg-white" data-bs-toggle="dropdown" aria-expanded="false"><i data-feather="more-vertical"></i></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?php echo route('stats', [$bio->urlid]) ?>"><i data-feather="bar-chart-2"></i> <?php ee('Statistics') ?></span></a></li>
                                        <?php if(user()->teamPermission('bio.edit')): ?>
                                        <li><a class="dropdown-item" href="<?php echo route('bio.edit', [$bio->id]) ?>"><i data-feather="edit"></i> <?php ee('Edit Bio') ?></a></li>
                                        <?php endif ?>

                                        <li><a class="dropdown-item" href="<?php echo $bio->url ?>"><i data-feather="eye"></i> <?php ee('View Bio') ?></a></li>
                                        <?php if(user()->defaultbio != $bio->id): ?>
                                        <li><a class="dropdown-item" href="<?php echo route('bio.default', [$bio->id]) ?>"><i data-feather="check-circle"></i> <?php ee('Set as Default') ?></a></li>
                                        <?php endif ?>
                                        <?php if(user()->teamPermission('bio.edit')): ?>
                                            <li><a class="dropdown-item" href="#" data-id="<?php echo $bio->id ?>" data-bs-toggle="modal" data-trigger="modalopen" data-bs-target="#channelModal" data-toggle="addtochannel"><i data-feather="package"></i> <?php ee('Add to Channel') ?></a></li>                                            
                                            <li><a class="dropdown-item" href="<?php echo route('links.reset', [$bio->urlid, \Core\Helper::nonce('link.reset')]) ?>" data-bs-toggle="modal" data-trigger="modalopen" data-bs-target="#resetModal"><i data-feather="rotate-ccw"></i> <?php ee('Reset Stats') ?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo route('bio.duplicate', [$bio->id]) ?>"><i data-feather="copy"></i> <?php ee('Duplicate') ?></a></li>
                                        <?php endif ?>
                                        <?php if(user()->teamPermission('bio.delete')): ?>
                                        <li class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-trigger="modalopen" data-bs-target="#deleteModal" href="<?php echo route('bio.delete', [$bio->id, \Core\Helper::nonce('bio.delete')]) ?>"><i data-feather="trash"></i> <?php ee('Delete') ?></a></li>
                                        <?php endif ?>
                                    </ul>
                                </div>
                                <strong><?php echo $bio->name ?: 'n\a' ?></strong> <?php echo (user()->defaultbio == $bio->id ? '<span class="badge bg-primary">'.e('Default').'</span>' : '') ?><?php if($bio->status == '0') : ?>
                                    <small class="badge bg-danger"><?php ee('Disabled') ?></small>
                                <?php endif ?>
                                <p class="mt-2">
                                    <small class="text-muted" data-href="<?php echo $bio->url ?>"><?php echo $bio->url ?></small>
                                    <a href="#copy" class="copy inline-copy" data-clipboard-text="<?php echo $bio->url ?>"><small><?php echo e("Copy")?></small></a>
                                </p>
                                <?php if($channels = $bio->channels): ?>
                                    <div class="mb-2">
                                    <?php foreach($channels as $channel): ?>
                                        <small class="badge text-xs me-2" style="background-color: <?php echo $channel->color ?>"><?php echo $channel->name ?> <a href="<?php echo route('channel.removefrom', [$channel->id, 'bio', $bio->id]) ?>" class="ms-2 text-light" data-bs-toggle="modal" data-trigger="modalopen" data-bs-target="#deleteModal"><span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php ee('Remove from channel') ?>">X</a></small>
                                    <?php endforeach ?>
                                    </div>
                                <?php endif ?>                                
                                <?php if(isset($bio->views)):?>
                                    <small class="text-navy"><?php echo $bio->views .' '.e('Views') ?></small> -
                                <?php endif ?>
                                <small class="text-navy"><?php echo \Core\Helper::timeago($bio->created_at) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php else: ?>            
            <div class="card flex-fill">         
                <div class="card-body text-center">
                    <p><?php ee('No content found. You can create some.') ?></p>
                    <?php if(user()->teamPermission('bio.create')): ?>
                        <a href="<?php echo route('bio.create') ?>" class="btn btn-primary"><?php ee('Create Bio') ?></a>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>
        <?php echo pagination() ?>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><?php ee('Bio Pages') ?></h5>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $total == 0 ? 100 : round($count*100/$total) ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo $count ?> / <?php echo $total == 0 ? e('Unlimited') : $total ?></div>
                </div>            
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex">
                    <h5 class="card-title mb-0"><?php ee('What are Bio Pages?') ?></h5>
                </div>
            </div>
            <div class="card-body">
                <p> <?php echo ee('A bio page allows you to create a trackable and customizable landing page where you can add links to your social network pages.') ?></p>
                <p> <?php echo ee('You can set a bio page as default and access them via your profile page.') ?>
            </div>
        </div>
        <?php plug('sidebar.bio') ?>
    </div>
</div>
<?php if(user()->teamPermission('bio.delete')): ?>
<div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php ee('Are you sure you want to delete this?') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php ee('You are trying to delete a record. This action is permanent and cannot be reversed.') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php ee('Cancel') ?></button>
        <a href="#" class="btn btn-danger" data-trigger="confirm"><?php ee('Confirm') ?></a>
      </div>
    </div>
  </div>
</div>
<?php endif ?>
<div class="modal fade" id="resetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php ee('Are you sure you want to reset this?') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php ee('You are trying to reset all statistic data for this link. This action is permanent and cannot be reversed.') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php ee('Cancel') ?></button>
        <a href="#" class="btn btn-danger" data-trigger="confirm"><?php ee('Confirm') ?></a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="channelModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">    
    <div class="modal-content">
      <form action="<?php echo route('channel.addto', ['bio', null]) ?>" data-trigger="server-form">
        <?php echo csrf() ?>
        <div class="modal-header">
            <h5 class="modal-title"><?php ee('Add to Channels') ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <label for="channels" class="form-label d-block mb-2"><?php ee('Channels') ?></label>
            <div class="form-group rounded input-select">
                <select name="channels[]" id="channels" class="form-control" multiple data-toggle="select">
                    <?php foreach(\Core\DB::channels()->where('userid', user()->rID())->findArray() as $channel): ?>
                        <option value="<?php echo $channel['id'] ?>"><?php echo $channel['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <input type="hidden" name="channelids" id="channelids" value="">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php ee('Cancel') ?></button>
            <button type="submit" class="btn btn-success" class="btn btn-success" data-bs-dismiss="modal" data-trigger="addtocampaign"><?php ee('Add') ?></button>
        </div>          
      </form>
    </div>
  </div>
</div>