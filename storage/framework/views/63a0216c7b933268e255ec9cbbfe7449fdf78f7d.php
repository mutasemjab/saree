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
                    <!-- User Profile Section -->
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
                                        <h6 class="text-muted"><?php echo e(__('messages.City')); ?></h6>
                                        <p class="h5"><?php echo e($user->city->name ?? __('messages.not_available')); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.total_addresses')); ?></h6>
                                        <p class="h5"><?php echo e($statistics['total_addresses']); ?></p>
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

                    <!-- Statistics Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3"><?php echo e(__('messages.statistics')); ?></h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['total_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.total_orders')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['pending_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.pending')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['completed_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.completed')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['cancelled_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.cancelled')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Location and Wallet Section -->
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

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-wallet"></i> <?php echo e(__('messages.wallet_information')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if($user->wallet): ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <strong><?php echo e(__('messages.wallet_id')); ?>:</strong><br>
                                                <span class="text-muted">#<?php echo e($user->wallet->id); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <strong><?php echo e(__('messages.current_balance')); ?>:</strong><br>
                                                <span class="text-success h5"><?php echo e(number_format($user->wallet->total, 2)); ?> <?php echo e(__('messages.currency')); ?></span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="<?php echo e(route('wallets.show', $user->wallet->id)); ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> <?php echo e(__('messages.view_wallet')); ?>

                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0"><?php echo e(__('messages.no_wallet')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- User Addresses -->
                    <?php if($user->addresses && $user->addresses->count() > 0): ?>
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3"><?php echo e(__('messages.saved_addresses')); ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('messages.id')); ?></th>
                                            <th><?php echo e(__('messages.title')); ?></th>
                                            <th><?php echo e(__('messages.address')); ?></th>
                                            <th><?php echo e(__('messages.location')); ?></th>
                                            <th><?php echo e(__('messages.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $user->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($address->id); ?></td>
                                            <td><?php echo e($address->title ?? '-'); ?></td>
                                            <td><?php echo e($address->address ?? '-'); ?></td>
                                            <td>
                                                <?php if($address->lat && $address->lng): ?>
                                                    <a href="https://www.google.com/maps?q=<?php echo e($address->lat); ?>,<?php echo e($address->lng); ?>"
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-map-marker-alt"></i> <?php echo e(__('messages.view_on_map')); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo e($address->created_at->format('Y-m-d')); ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endif; ?>

                    <!-- Recent Transactions -->
                    <?php if($user->wallet && $user->wallet->transactions->count() > 0): ?>
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3"><?php echo e(__('messages.recent_transactions')); ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('messages.id')); ?></th>
                                            <th><?php echo e(__('messages.type')); ?></th>
                                            <th><?php echo e(__('messages.amount')); ?></th>
                                            <th><?php echo e(__('messages.note')); ?></th>
                                            <th><?php echo e(__('messages.date')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $user->wallet->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($transaction->id); ?></td>
                                            <td>
                                                <?php if($transaction->deposit > 0): ?>
                                                    <span class="badge bg-success"><?php echo e(__('messages.deposit')); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?php echo e(__('messages.withdrawal')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($transaction->deposit > 0): ?>
                                                    <span class="text-success">+<?php echo e(number_format($transaction->deposit, 2)); ?></span>
                                                <?php else: ?>
                                                    <span class="text-danger">-<?php echo e(number_format($transaction->withdrawal, 2)); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($transaction->note ?? '-'); ?></td>
                                            <td><?php echo e($transaction->created_at->format('Y-m-d H:i')); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endif; ?>

                    <!-- Recent Orders -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3"><?php echo e(__('messages.recent_orders')); ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('messages.order_number')); ?></th>
                                            <th><?php echo e(__('messages.driver')); ?></th>
                                            <th><?php echo e(__('messages.status')); ?></th>
                                            <th><?php echo e(__('messages.price')); ?></th>
                                            <th><?php echo e(__('messages.payment_method')); ?></th>
                                            <th><?php echo e(__('messages.date')); ?></th>
                                            <th><?php echo e(__('messages.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>#<?php echo e($order->number); ?></td>
                                            <td><?php echo e($order->driver->name ?? __('messages.not_assigned')); ?></td>
                                            <td>
                                                <?php
                                                    $statusColors = [
                                                        1 => 'warning',
                                                        2 => 'info',
                                                        3 => 'primary',
                                                        4 => 'success',
                                                        5 => 'danger',
                                                        6 => 'danger'
                                                    ];
                                                    $statusLabels = [
                                                        1 => __('messages.pending'),
                                                        2 => __('messages.accepted'),
                                                        3 => __('messages.on_the_way'),
                                                        4 => __('messages.delivered'),
                                                        5 => __('messages.cancelled_by_user'),
                                                        6 => __('messages.cancelled_by_driver')
                                                    ];
                                                ?>
                                                <span class="badge bg-<?php echo e($statusColors[$order->order_status] ?? 'secondary'); ?>">
                                                    <?php echo e($statusLabels[$order->order_status] ?? __('messages.unknown')); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e(number_format($order->final_price, 2)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($order->payment_method == 1 ? 'success' : 'primary'); ?>">
                                                    <?php echo e($order->payment_method == 1 ? __('messages.cash') : __('messages.visa')); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($order->created_at->format('Y-m-d H:i')); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center"><?php echo e(__('messages.no_orders_found')); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                <?php echo e($orders->links()); ?>

                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sline\resources\views/admin/users/show.blade.php ENDPATH**/ ?>