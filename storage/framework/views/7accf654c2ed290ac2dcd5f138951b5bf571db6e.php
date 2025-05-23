<?php $__env->startSection('title', __('messages.wallets')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.wallets')); ?></h3>
                    <div>
                        <a href="<?php echo e(route('wallets.statistics')); ?>" class="btn btn-info me-2">
                            <i class="fas fa-chart-bar"></i> <?php echo e(__('messages.statistics')); ?>

                        </a>
                       <a href="<?php echo e(route('wallet-transactions.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_transaction')); ?>

                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                   

                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-3" id="walletTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                                <?php echo e(__('messages.all_wallets')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?php echo e(route('wallets.by-owner-type', 'users')); ?>">
                                <?php echo e(__('messages.user_wallets')); ?>

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?php echo e(route('wallets.by-owner-type', 'drivers')); ?>">
                                <?php echo e(__('messages.driver_wallets')); ?>

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?php echo e(route('wallets.by-owner-type', 'admins')); ?>">
                                <?php echo e(__('messages.admin_wallets')); ?>

                            </a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.id')); ?></th>
                                    <th><?php echo e(__('messages.owner')); ?></th>
                                    <th><?php echo e(__('messages.owner_type')); ?></th>
                                    <th><?php echo e(__('messages.total_balance')); ?></th>
                                    <th><?php echo e(__('messages.transactions_count')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($wallet->id); ?></td>
                                        <td>
                                            <?php if($wallet->user): ?>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo e($wallet->user->photo_url); ?>" 
                                                         alt="<?php echo e($wallet->user->name); ?>" 
                                                         class="rounded-circle me-2" 
                                                         width="30" height="30">
                                                    <?php echo e($wallet->user->name); ?>

                                                </div>
                                            <?php elseif($wallet->driver): ?>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo e($wallet->driver->photo_url); ?>" 
                                                         alt="<?php echo e($wallet->driver->name); ?>" 
                                                         class="rounded-circle me-2" 
                                                         width="30" height="30">
                                                    <?php echo e($wallet->driver->name); ?>

                                                </div>
                                            <?php elseif($wallet->admin): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-shield me-2"></i>
                                                    <?php echo e($wallet->admin->name ?? __('messages.admin')); ?>

                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.no_owner')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($wallet->user): ?>
                                                <span class="badge bg-primary"><?php echo e(__('messages.user')); ?></span>
                                            <?php elseif($wallet->driver): ?>
                                                <span class="badge bg-success"><?php echo e(__('messages.driver')); ?></span>
                                            <?php elseif($wallet->admin): ?>
                                                <span class="badge bg-warning"><?php echo e(__('messages.admin')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e(__('messages.unknown')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-<?php echo e($wallet->total >= 0 ? 'success' : 'danger'); ?>">
                                                <?php echo e(number_format($wallet->total, 2)); ?> <?php echo e(__('messages.currency')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($wallet->transactions_count ?? 0); ?></span>
                                        </td>
                                        <td><?php echo e($wallet->created_at ? $wallet->created_at->format('Y-m-d H:i') : '-'); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('wallets.show', $wallet)); ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('wallet-transactions.by-wallet', $wallet)); ?>" 
                                                   class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                <a href="<?php echo e(route('wallets.edit', $wallet)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('wallets.destroy', $wallet)); ?>" 
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
                                            <?php echo e(__('messages.no_wallets_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($wallets->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saree\resources\views/admin/wallets/index.blade.php ENDPATH**/ ?>