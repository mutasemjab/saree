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
                    <!-- Driver Profile Section -->
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="driver-photo mb-3">
                                <img src="<?php echo e(asset('assets/admin/uploads') . '/' . $driver->photo); ?>"
                                     alt="<?php echo e($driver->name); ?>"
                                     class="img-fluid rounded-circle border"
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                            <h4><?php echo e($driver->name); ?></h4>
                            <span class="badge bg-<?php echo e($driver->activate == 1 ? 'success' : 'danger'); ?> fs-6 mb-2">
                                <?php echo e($driver->activation_status); ?>

                            </span>
                            <br>
                            <span class="badge bg-<?php echo e($driver->status == 1 ? 'success' : 'secondary'); ?> fs-6">
                                <?php echo e($driver->status == 1 ? __('messages.online') : __('messages.offline')); ?>

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
                                        <h6 class="text-muted"><?php echo e(__('messages.identity_number')); ?></h6>
                                        <p class="h5"><?php echo e($driver->identity_number); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.plate_number')); ?></h6>
                                        <p class="h5"><?php echo e($driver->plate_number ?? __('messages.not_available')); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.Car type')); ?></h6>
                                        <p class="h5">
                                            <span class="badge bg-primary">
                                                <?php echo e($driver->car_type == 1 ? __('messages.car') : __('messages.motosycle')); ?>

                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted"><?php echo e(__('messages.City')); ?></h6>
                                        <p class="h5"><?php echo e($driver->city->name ?? __('messages.not_available')); ?></p>
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

                    <!-- Statistics Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3"><?php echo e(__('messages.statistics')); ?></h5>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['total_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.total_orders')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['pending_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.pending')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['completed_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.completed')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e($statistics['cancelled_orders']); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.cancelled')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e(number_format($statistics['total_earnings'], 2)); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.total_earnings')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo e(number_format($statistics['total_distance'], 2)); ?></h3>
                                    <p class="mb-0"><?php echo e(__('messages.total_km')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Wallet Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3"><?php echo e(__('messages.wallet_information')); ?></h5>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-muted"><?php echo e(__('messages.wallet_id')); ?></h6>
                                    <p class="h4">#<?php echo e($driver->wallet->id ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-muted"><?php echo e(__('messages.current_balance')); ?></h6>
                                    <p class="h4 text-success"><?php echo e(number_format($driver->wallet->total ?? 0, 2)); ?> <?php echo e(__('messages.currency')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <a href="<?php echo e(route('wallets.show', $driver->wallet->id ?? 0)); ?>" class="btn btn-primary">
                                        <i class="fas fa-wallet"></i> <?php echo e(__('messages.view_wallet')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Recent Transactions -->
                    <?php if($driver->wallet && $driver->wallet->transactions->count() > 0): ?>
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
                                        <?php $__currentLoopData = $driver->wallet->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                            <th><?php echo e(__('messages.customer')); ?></th>
                                            <th><?php echo e(__('messages.status')); ?></th>
                                            <th><?php echo e(__('messages.price')); ?></th>
                                            <th><?php echo e(__('messages.driver_earnings')); ?></th>
                                            <th><?php echo e(__('messages.payment_method')); ?></th>
                                            <th><?php echo e(__('messages.date')); ?></th>
                                            <th><?php echo e(__('messages.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>#<?php echo e($order->number); ?></td>
                                            <td><?php echo e($order->user->name ?? __('messages.not_available')); ?></td>
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
                                            <td><?php echo e(number_format($order->driver_earnings, 2)); ?></td>
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
                                            <td colspan="8" class="text-center"><?php echo e(__('messages.no_orders_found')); ?></td>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\sline\resources\views/admin/drivers/show.blade.php ENDPATH**/ ?>