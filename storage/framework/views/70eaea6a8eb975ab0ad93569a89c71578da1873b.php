<?php $__env->startSection('title', __('messages.users')); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><?php echo e(__('messages.users')); ?></h3>
                        <div>
                            <a href="<?php echo e(route('users.export', request()->query())); ?>" class="btn btn-success me-2">
                                <i class="fas fa-file-excel"></i> <?php echo e(__('messages.export_excel')); ?>

                            </a>
                            <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.add_user')); ?>

                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <form method="GET" action="<?php echo e(route('users.index')); ?>" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="<?php echo e(__('messages.search')); ?>..." value="<?php echo e(request('search')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_status')); ?></option>
                                        <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.active')); ?>

                                        </option>
                                        <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.inactive')); ?>

                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="city_id" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_cities')); ?></option>
                                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($city->id); ?>"
                                                <?php echo e(request('city_id') == $city->id ? 'selected' : ''); ?>>
                                                <?php echo e($city->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control"
                                        placeholder="<?php echo e(__('messages.from_date')); ?>" value="<?php echo e(request('date_from')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control"
                                        placeholder="<?php echo e(__('messages.to_date')); ?>" value="<?php echo e(request('date_to')); ?>">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <?php if(request()->hasAny(['search', 'status', 'city_id', 'date_from', 'date_to'])): ?>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> <?php echo e(__('messages.clear_filters')); ?>

                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>

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
                                                    alt="<?php echo e($user->name); ?>" class="rounded-circle" width="50"
                                                    height="50">
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
                                                    <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-info btn-sm">
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
                                                            <i
                                                                class="fas fa-<?php echo e($user->activate == 1 ? 'ban' : 'check'); ?>"></i>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sline\resources\views/admin/users/index.blade.php ENDPATH**/ ?>