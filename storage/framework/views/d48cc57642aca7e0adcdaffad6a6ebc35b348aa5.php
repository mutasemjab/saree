<?php $__env->startSection('title', __('messages.settings')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.settings')); ?></h3>
                    <a href="<?php echo e(route('settings.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_setting')); ?>

                    </a>
                </div>
                
                <div class="card-body">
              

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.id')); ?></th>
                                    <th><?php echo e(__('messages.key')); ?></th>
                                    <th><?php echo e(__('messages.value')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($setting->id); ?></td>
                                        <td>
                                            <span class="bg-light px-2 py-1 rounded"><?php echo e($setting->key); ?></span>
                                        </td>
                                        <td>
                                            <div class="setting-value" style="max-width: 300px;">
                                                <?php if(strlen($setting->value) > 50): ?>
                                                    <span class="text-truncate d-block" title="<?php echo e($setting->value); ?>">
                                                        <?php echo e(Str::limit($setting->value, 50)); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <?php echo e($setting->value); ?>

                                                <?php endif; ?>
                                            </div>
                                        </td>
                                       
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('settings.show', $setting)); ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('settings.edit', $setting)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <?php echo e(__('messages.no_settings_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($settings->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.setting-value {
    word-break: break-word;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u919910150/domains/slinejo.com/public_html/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>