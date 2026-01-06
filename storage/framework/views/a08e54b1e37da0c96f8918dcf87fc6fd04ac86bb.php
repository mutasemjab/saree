<?php $__env->startSection('title', __('messages.driver_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.driver_details')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('drivers.edit', $driver)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <a href="<?php echo e(route('drivers.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="driver-photo mb-3">
                                <img src="<?php echo e(asset('assets/admin/uploads') . '/' . $driver->photo); ?>" 
                                     alt="<?php echo e($driver->name); ?>" 
                                     class="img-fluid rounded-circle border" 
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                            <h4><?php echo e($driver->name); ?></h4>
                            <span class="badge bg-<?php echo e($driver->activate == 1 ? 'success' : 'danger'); ?> fs-6">
                                <?php echo e($driver->activation_status); ?>

                            </span>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.id')); ?></h6>
                                        <p class="h5">#<?php echo e($driver->id); ?></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.phone')); ?></h6>
                                        <p class="h5">
                                            <a href="tel:<?php echo e($driver->phone); ?>" class="text-decoration-none">
                                                <?php echo e($driver->phone); ?>

                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.created_at')); ?></h6>
                                        <p class="h6"><?php echo e($driver->created_at->format('Y-m-d H:i:s')); ?></p>
                                        <small class="text-muted"><?php echo e($driver->created_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.updated_at')); ?></h6>
                                        <p class="h6"><?php echo e($driver->updated_at->format('Y-m-d H:i:s')); ?></p>
                                        <small class="text-muted"><?php echo e($driver->updated_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <form action="<?php echo e(route('drivers.toggle-activation', $driver)); ?>" 
                                          method="POST" 
                                          style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" 
                                                class="btn btn-<?php echo e($driver->activate == 1 ? 'warning' : 'success'); ?>">
                                            <i class="fas fa-<?php echo e($driver->activate == 1 ? 'ban' : 'check'); ?>"></i>
                                            <?php echo e($driver->activate == 1 ? __('messages.deactivate_driver') : __('messages.activate_driver')); ?>

                                        </button>
                                    </form>
                                </div>
                                
                                <div>
                                    <form action="<?php echo e(route('drivers.destroy', $driver)); ?>" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_driver')); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete_driver')); ?>

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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/drivers/show.blade.php ENDPATH**/ ?>