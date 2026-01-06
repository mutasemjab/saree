

<?php $__env->startSection('title', __('messages.orders')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.orders')); ?></h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="<?php echo e(route('orders.index')); ?>" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="<?php echo e(__('messages.search_order_number')); ?>" 
                                           value="<?php echo e(request('search')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                        <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.pending')); ?></option>
                                        <option value="2" <?php echo e(request('status') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.accepted')); ?></option>
                                        <option value="3" <?php echo e(request('status') == '3' ? 'selected' : ''); ?>><?php echo e(__('messages.on_the_way')); ?></option>
                                        <option value="4" <?php echo e(request('status') == '4' ? 'selected' : ''); ?>><?php echo e(__('messages.delivered')); ?></option>
                                        <option value="5" <?php echo e(request('status') == '5' ? 'selected' : ''); ?>><?php echo e(__('messages.cancelled_by_user')); ?></option>
                                        <option value="6" <?php echo e(request('status') == '6' ? 'selected' : ''); ?>><?php echo e(__('messages.cancelled_by_driver')); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="payment_type" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_payment_types')); ?></option>
                                        <option value="1" <?php echo e(request('payment_type') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.paid')); ?></option>
                                        <option value="2" <?php echo e(request('payment_type') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.unpaid')); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="payment_method" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_payment_methods')); ?></option>
                                        <option value="1" <?php echo e(request('payment_method') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.cash')); ?></option>
                                        <option value="2" <?php echo e(request('payment_method') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.visa')); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-search"></i> <?php echo e(__('messages.filter')); ?>

                                    </button>
                                    <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> <?php echo e(__('messages.clear')); ?>

                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.order_number')); ?></th>
                                    <th><?php echo e(__('messages.customer')); ?></th>
                                    <th><?php echo e(__('messages.driver')); ?></th>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <th><?php echo e(__('messages.final_price')); ?></th>
                                    <th><?php echo e(__('messages.payment_status')); ?></th>
                                    <th><?php echo e(__('messages.payment_method')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded"><?php echo e($order->number); ?></code>
                                        </td>
                                        <td>
                                            <?php if($order->user): ?>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div><?php echo e($order->user->name); ?></div>
                                                        <small class="text-muted"><?php echo e($order->user->phone); ?></small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.no_customer')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($order->driver): ?>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div><?php echo e($order->driver->name); ?></div>
                                                        <small class="text-muted"><?php echo e($order->driver->phone); ?></small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.no_driver_assigned')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($order->status_color); ?>">
                                                <?php echo e($order->status_text); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($order->final_price): ?>
                                                <span class="fw-bold"><?php echo e(number_format($order->final_price, 2)); ?> <?php echo e(__('messages.currency')); ?></span>
                                                <?php if($order->discount > 0): ?>
                                                    <br><small class="text-muted"><?php echo e(__('messages.discount')); ?>: <?php echo e(number_format($order->discount, 2)); ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.not_set')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($order->payment_type == 1 ? 'success' : 'warning'); ?>">
                                                <?php echo e($order->payment_type == 1 ? __('messages.paid') : __('messages.unpaid')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($order->payment_method == 1 ? 'info' : 'primary'); ?>">
                                                <?php echo e($order->payment_method == 1 ? __('messages.cash') : __('messages.visa')); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($order->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('orders.show', $order)); ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('orders.edit', $order)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                
                                                <?php if(!in_array($order->order_status, [4, 5, 6])): ?>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            onclick="confirmCancelOrder(<?php echo e($order->id); ?>, '<?php echo e($order->number); ?>')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            
                                            
                                            <form id="cancel-form-<?php echo e($order->id); ?>" 
                                                  action="<?php echo e(route('orders.cancel', $order)); ?>" 
                                                  method="POST" 
                                                  style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <?php echo e(__('messages.no_orders_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($orders->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel"><?php echo e(__('messages.cancel_order')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('messages.are_you_sure_cancel_order')); ?></p>
                <p><strong><?php echo e(__('messages.order_number')); ?>:</strong> <span id="orderNumberText"></span></p>
                <p class="text-danger"><?php echo e(__('messages.this_action_cannot_be_undone')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('messages.no_cancel')); ?></button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn"><?php echo e(__('messages.yes_cancel')); ?></button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
let orderToCancel = null;

function confirmCancelOrder(orderId, orderNumber) {
    orderToCancel = orderId;
    document.getElementById('orderNumberText').textContent = orderNumber;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
    modal.show();
}

document.getElementById('confirmCancelBtn').addEventListener('click', function() {
    if (orderToCancel) {
        document.getElementById('cancel-form-' + orderToCancel).submit();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>