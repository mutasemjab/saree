<?php $__env->startSection('title', __('messages.order_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.order_details')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('orders.edit', $order)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Order Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 me-3"><?php echo e($order->number); ?></h4>
                                <span class="badge bg-<?php echo e($order->status_color); ?> fs-6">
                                    <?php echo e($order->status_text); ?>

                                </span>
                            </div>
                            <small class="text-muted"><?php echo e(__('messages.created_at')); ?>: <?php echo e($order->created_at->format('Y-m-d H:i:s')); ?></small>
                        </div>
                        <div class="col-md-6 text-end">
                            <?php if($order->final_price): ?>
                                <h3 class="text-primary mb-0"><?php echo e(number_format($order->final_price, 2)); ?> <?php echo e(__('messages.currency')); ?></h3>
                                <small class="text-muted"><?php echo e(__('messages.final_price')); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user"></i> <?php echo e(__('messages.customer_information')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if($order->user): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="<?php echo e(asset('assets/admin/uploads/' . $order->user->photo)); ?>" 
                                                 alt="<?php echo e($order->user->name); ?>" 
                                                 class="rounded-circle me-3" 
                                                 width="60" height="60">
                                            <div>
                                                <h6 class="mb-0"><?php echo e($order->user->name); ?></h6>
                                                <p class="text-muted mb-0"><?php echo e($order->user->phone); ?></p>
                                                <span class="badge bg-<?php echo e($order->user->activate == 1 ? 'success' : 'danger'); ?>">
                                                    <?php echo e($order->user->activate == 1 ? __('messages.active') : __('messages.inactive')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted"><?php echo e(__('messages.no_customer_assigned')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-car"></i> <?php echo e(__('messages.driver_information')); ?>

                                    </h5>
                                    <?php if(!$order->driver): ?>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignDriverModal">
                                            <i class="fas fa-plus"></i> <?php echo e(__('messages.assign_driver')); ?>

                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <?php if($order->driver): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="<?php echo e(asset('assets/admin/uploads/' . $order->driver->photo)); ?>" 
                                                 alt="<?php echo e($order->driver->name); ?>" 
                                                 class="rounded-circle me-3" 
                                                 width="60" height="60">
                                            <div>
                                                <h6 class="mb-0"><?php echo e($order->driver->name); ?></h6>
                                                <p class="text-muted mb-0"><?php echo e($order->driver->phone); ?></p>
                                                <span class="badge bg-<?php echo e($order->driver->activate == 1 ? 'success' : 'danger'); ?>">
                                                    <?php echo e($order->driver->activate == 1 ? __('messages.active') : __('messages.inactive')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted"><?php echo e(__('messages.no_driver_assigned')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Order Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.order_information')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong><?php echo e(__('messages.price')); ?>:</strong><br>
                                            <span class="text-muted"><?php echo e($order->price ? number_format($order->price, 2) . ' ' . __('messages.currency') : __('messages.not_set')); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <strong><?php echo e(__('messages.discount')); ?>:</strong><br>
                                            <span class="text-muted"><?php echo e($order->discount ? number_format($order->discount, 2) . ' ' . __('messages.currency') : __('messages.no_discount')); ?></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong><?php echo e(__('messages.total_distance')); ?>:</strong><br>
                                            <span class="text-muted"><?php echo e($order->total_distance ? number_format($order->total_distance, 2) . ' ' . __('messages.km') : __('messages.not_set')); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <strong><?php echo e(__('messages.total_time')); ?>:</strong><br>
                                            <span class="text-muted"><?php echo e($order->total_time ?? __('messages.not_set')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-credit-card"></i> <?php echo e(__('messages.payment_information')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong><?php echo e(__('messages.payment_status')); ?>:</strong><br>
                                            <span class="badge bg-<?php echo e($order->payment_type == 1 ? 'success' : 'warning'); ?>">
                                                <?php echo e($order->payment_type == 1 ? __('messages.paid') : __('messages.unpaid')); ?>

                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <strong><?php echo e(__('messages.payment_method')); ?>:</strong><br>
                                            <span class="badge bg-<?php echo e($order->payment_method == 1 ? 'info' : 'primary'); ?>">
                                                <?php echo e($order->payment_method == 1 ? __('messages.cash') : __('messages.visa')); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                        <i class="fas fa-sync"></i> <?php echo e(__('messages.update_status')); ?>

                                    </button>
                                </div>
                                
                                <div>
                                    <form action="<?php echo e(route('orders.destroy', $order)); ?>" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_order')); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete_order')); ?>

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

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('orders.update-status', $order)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('messages.update_order_status')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_order_status" class="form-label"><?php echo e(__('messages.order_status')); ?></label>
                        <select class="form-select" id="modal_order_status" name="order_status" required>
                            <option value="1" <?php echo e($order->order_status == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.pending')); ?></option>
                            <option value="2" <?php echo e($order->order_status == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.accepted')); ?></option>
                            <option value="3" <?php echo e($order->order_status == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.on_the_way')); ?></option>
                            <option value="4" <?php echo e($order->order_status == 4 ? 'selected' : ''); ?>><?php echo e(__('messages.delivered')); ?></option>
                            <option value="5" <?php echo e($order->order_status == 5 ? 'selected' : ''); ?>><?php echo e(__('messages.cancelled_by_user')); ?></option>
                            <option value="6" <?php echo e($order->order_status == 6 ? 'selected' : ''); ?>><?php echo e(__('messages.cancelled_by_driver')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('messages.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.update')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Driver Modal -->
<div class="modal fade" id="assignDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('orders.assign-driver', $order)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('messages.assign_driver')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_driver_id" class="form-label"><?php echo e(__('messages.driver')); ?></label>
                        <select class="form-select" id="modal_driver_id" name="driver_id" required>
                            <option value=""><?php echo e(__('messages.select_driver')); ?></option>
                            <?php $__currentLoopData = \App\Models\Driver::active()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($driver->id); ?>"><?php echo e($driver->name); ?> (<?php echo e($driver->phone); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('messages.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.assign')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>