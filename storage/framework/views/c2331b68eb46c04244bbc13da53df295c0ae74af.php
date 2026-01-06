<?php $__env->startSection('title', __('messages.user_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.user_details')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="user-photo mb-3">
                                <img src="<?php echo e(asset('assets/admin/uploads') . '/' . $user->photo); ?>" 
                                     alt="<?php echo e($user->name); ?>" 
                                     class="img-fluid rounded-circle border" 
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                            <h4><?php echo e($user->name); ?></h4>
                            <span class="badge bg-<?php echo e($user->activate == 1 ? 'success' : 'danger'); ?> fs-6">
                                <?php echo e($user->activation_status); ?>

                            </span>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.id')); ?></h6>
                                        <p class="h5">#<?php echo e($user->id); ?></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.phone')); ?></h6>
                                        <p class="h5">
                                            <a href="tel:<?php echo e($user->phone); ?>" class="text-decoration-none">
                                                <?php echo e($user->phone); ?>

                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.created_at')); ?></h6>
                                        <p class="h6"><?php echo e($user->created_at->format('Y-m-d H:i:s')); ?></p>
                                        <small class="text-muted"><?php echo e($user->created_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.updated_at')); ?></h6>
                                        <p class="h6"><?php echo e($user->updated_at->format('Y-m-d H:i:s')); ?></p>
                                        <small class="text-muted"><?php echo e($user->updated_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo e(__('messages.location_info')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if($user->lat && $user->lng): ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <strong><?php echo e(__('messages.latitude')); ?>:</strong><br>
                                                <span class="text-muted"><?php echo e(number_format($user->lat, 6)); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <strong><?php echo e(__('messages.longitude')); ?>:</strong><br>
                                                <span class="text-muted"><?php echo e(number_format($user->lng, 6)); ?></span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="https://www.google.com/maps?q=<?php echo e($user->lat); ?>,<?php echo e($user->lng); ?>" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-external-link-alt"></i> <?php echo e(__('messages.view_on_map')); ?>

                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0"><?php echo e(__('messages.location_not_available')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                   
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <form action="<?php echo e(route('users.toggle-activation', $user)); ?>" 
                                          method="POST" 
                                          style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" 
                                                class="btn btn-<?php echo e($user->activate == 1 ? 'warning' : 'success'); ?>">
                                            <i class="fas fa-<?php echo e($user->activate == 1 ? 'ban' : 'check'); ?>"></i>
                                            <?php echo e($user->activate == 1 ? __('messages.deactivate_user') : __('messages.activate_user')); ?>

                                        </button>
                                    </form>
                                </div>
                                
                                <div>
                                    <form action="<?php echo e(route('users.destroy', $user)); ?>" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_user')); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete_user')); ?>

                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('<?php echo e(__('messages.token_copied')); ?>');
    }, function(err) {
        console.error('<?php echo e(__('messages.copy_failed')); ?>', err);
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/users/show.blade.php ENDPATH**/ ?>