<?php $__env->startSection('title', __('messages.drivers')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.drivers')); ?></h3>
                    <a href="<?php echo e(route('drivers.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_driver')); ?>

                    </a>
                </div>
                
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.id')); ?></th>
                                    <th><?php echo e(__('messages.photo')); ?></th>
                                    <th><?php echo e(__('messages.name')); ?></th>
                                    <th><?php echo e(__('messages.phone')); ?></th>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($driver->id); ?></td>
                                        <td>
                                            <img src="<?php echo e($driver->photo_url); ?>" 
                                                 alt="<?php echo e($driver->name); ?>" 
                                                 class="rounded-circle" 
                                                 width="50" height="50">
                                        </td>
                                        <td><?php echo e($driver->name); ?></td>
                                        <td><?php echo e($driver->phone); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($driver->activate == 1 ? 'success' : 'danger'); ?>">
                                                <?php echo e($driver->activation_status); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($driver->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('drivers.show', $driver)); ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('drivers.edit', $driver)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('drivers.toggle-activation', $driver)); ?>" 
                                                      method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-<?php echo e($driver->activate == 1 ? 'secondary' : 'success'); ?> btn-sm"
                                                            title="<?php echo e($driver->activate == 1 ? __('messages.deactivate') : __('messages.activate')); ?>">
                                                        <i class="fas fa-<?php echo e($driver->activate == 1 ? 'ban' : 'check'); ?>"></i>
                                                    </button>
                                                </form>
                                                <form action="<?php echo e(route('drivers.destroy', $driver)); ?>" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <?php echo e(__('messages.no_drivers_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($drivers->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saree\resources\views/admin/drivers/index.blade.php ENDPATH**/ ?>