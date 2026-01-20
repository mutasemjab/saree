<?php $__env->startSection('title', __('messages.users')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.users')); ?></h3>
                    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_user')); ?>

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
                                    <th><?php echo e(__('messages.location')); ?></th>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($user->id); ?></td>
                                        <td>
                                            <img src="<?php echo e(asset('assets/admin/uploads') . '/' . $user->photo); ?>" 
                                                 alt="<?php echo e($user->name); ?>" 
                                                 class="rounded-circle" 
                                                 width="50" height="50">
                                        </td>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->phone); ?></td>
                                        <td>
                                            <?php if($user->lat && $user->lng): ?>
                                                <small>
                                                    <?php echo e(__('messages.lat')); ?>: <?php echo e(number_format($user->lat, 6)); ?><br>
                                                    <?php echo e(__('messages.lng')); ?>: <?php echo e(number_format($user->lng, 6)); ?>

                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.not_available')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($user->activate == 1 ? 'success' : 'danger'); ?>">
                                                <?php echo e($user->activation_status); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($user->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('users.show', $user)); ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('users.edit', $user)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('users.toggle-activation', $user)); ?>" 
                                                      method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-<?php echo e($user->activate == 1 ? 'secondary' : 'success'); ?> btn-sm"
                                                            title="<?php echo e($user->activate == 1 ? __('messages.deactivate') : __('messages.activate')); ?>">
                                                        <i class="fas fa-<?php echo e($user->activate == 1 ? 'ban' : 'check'); ?>"></i>
                                                    </button>
                                                </form>
                                             
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <?php echo e(__('messages.no_users_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($users->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/users/index.blade.php ENDPATH**/ ?>