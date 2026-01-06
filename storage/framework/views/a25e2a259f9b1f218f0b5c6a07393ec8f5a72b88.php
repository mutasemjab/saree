<?php $__env->startSection('title', __('messages.edit_order')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.edit_order')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <form action="<?php echo e(route('orders.update', $order)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="number" class="form-label"><?php echo e(__('messages.order_number')); ?></label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="number" 
                                           name="number" 
                                           value="<?php echo e(old('number', $order->number)); ?>">
                                    <?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="order_status" class="form-label"><?php echo e(__('messages.order_status')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['order_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="order_status" 
                                            name="order_status" 
                                            required>
                                        <option value=""><?php echo e(__('messages.select_status')); ?></option>
                                        <option value="1" <?php echo e(old('order_status', $order->order_status) == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.pending')); ?></option>
                                        <option value="2" <?php echo e(old('order_status', $order->order_status) == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.accepted')); ?></option>
                                        <option value="3" <?php echo e(old('order_status', $order->order_status) == '3' ? 'selected' : ''); ?>><?php echo e(__('messages.on_the_way')); ?></option>
                                        <option value="4" <?php echo e(old('order_status', $order->order_status) == '4' ? 'selected' : ''); ?>><?php echo e(__('messages.delivered')); ?></option>
                                        <option value="5" <?php echo e(old('order_status', $order->order_status) == '5' ? 'selected' : ''); ?>><?php echo e(__('messages.cancelled_by_user')); ?></option>
                                        <option value="6" <?php echo e(old('order_status', $order->order_status) == '6' ? 'selected' : ''); ?>><?php echo e(__('messages.cancelled_by_driver')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['order_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="user_id" class="form-label"><?php echo e(__('messages.customer')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="user_id" 
                                            name="user_id" 
                                            required>
                                        <option value=""><?php echo e(__('messages.select_customer')); ?></option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id', $order->user_id) == $user->id ? 'selected' : ''); ?>>
                                                <?php echo e($user->name); ?> (<?php echo e($user->phone); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="driver_id" class="form-label"><?php echo e(__('messages.driver')); ?></label>
                                    <select class="form-control <?php $__errorArgs = ['driver_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="driver_id" 
                                            name="driver_id">
                                        <option value=""><?php echo e(__('messages.select_driver')); ?></option>
                                        <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($driver->id); ?>" <?php echo e(old('driver_id', $order->driver_id) == $driver->id ? 'selected' : ''); ?>>
                                                <?php echo e($driver->name); ?> (<?php echo e($driver->phone); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['driver_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label"><?php echo e(__('messages.price')); ?></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="price" 
                                               name="price" 
                                               value="<?php echo e(old('price', $order->price)); ?>" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text"><?php echo e(__('messages.currency')); ?></span>
                                    </div>
                                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="discount" class="form-label"><?php echo e(__('messages.discount')); ?></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="discount" 
                                               name="discount" 
                                               value="<?php echo e(old('discount', $order->discount)); ?>" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text"><?php echo e(__('messages.currency')); ?></span>
                                    </div>
                                    <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="final_price" class="form-label"><?php echo e(__('messages.final_price')); ?></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['final_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="final_price" 
                                               name="final_price" 
                                               value="<?php echo e(old('final_price', $order->final_price)); ?>" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text"><?php echo e(__('messages.currency')); ?></span>
                                    </div>
                                    <?php $__errorArgs = ['final_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted"><?php echo e(__('messages.auto_calculated_if_empty')); ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_distance" class="form-label"><?php echo e(__('messages.total_distance')); ?></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['total_distance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="total_distance" 
                                               name="total_distance" 
                                               value="<?php echo e(old('total_distance', $order->total_distance)); ?>" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text"><?php echo e(__('messages.km')); ?></span>
                                    </div>
                                    <?php $__errorArgs = ['total_distance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_time" class="form-label"><?php echo e(__('messages.total_time')); ?></label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['total_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="total_time" 
                                           name="total_time" 
                                           value="<?php echo e(old('total_time', $order->total_time)); ?>" 
                                           placeholder="<?php echo e(__('messages.time_format_example')); ?>">
                                    <?php $__errorArgs = ['total_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_type" class="form-label"><?php echo e(__('messages.payment_status')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="payment_type" 
                                            name="payment_type" 
                                            required>
                                        <option value=""><?php echo e(__('messages.select_payment_status')); ?></option>
                                        <option value="1" <?php echo e(old('payment_type', $order->payment_type) == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.paid')); ?></option>
                                        <option value="2" <?php echo e(old('payment_type', $order->payment_type) == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.unpaid')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label"><?php echo e(__('messages.payment_method')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="payment_method" 
                                            name="payment_method" 
                                            required>
                                        <option value=""><?php echo e(__('messages.select_payment_method')); ?></option>
                                        <option value="1" <?php echo e(old('payment_method', $order->payment_method) == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.cash')); ?></option>
                                        <option value="2" <?php echo e(old('payment_method', $order->payment_method) == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.visa')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Order History -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-history"></i> <?php echo e(__('messages.order_history')); ?>

                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong><?php echo e(__('messages.created_at')); ?>:</strong><br>
                                                <span class="text-muted"><?php echo e($order->created_at->format('Y-m-d H:i:s')); ?></span><br>
                                                <small class="text-muted"><?php echo e($order->created_at->diffForHumans()); ?></small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong><?php echo e(__('messages.updated_at')); ?>:</strong><br>
                                                <span class="text-muted"><?php echo e($order->updated_at->format('Y-m-d H:i:s')); ?></span><br>
                                                <small class="text-muted"><?php echo e($order->updated_at->diffForHumans()); ?></small>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong><?php echo e(__('messages.current_status')); ?>:</strong><br>
                                                <span class="badge bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_text); ?></span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong><?php echo e(__('messages.current_final_price')); ?>:</strong><br>
                                                <span class="text-success fw-bold"><?php echo e($order->formatted_final_price); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.update')); ?>

                        </button>
                        <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-info">
                            <i class="fas fa-eye"></i> <?php echo e(__('messages.view_details')); ?>

                        </a>
                        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                            <?php echo e(__('messages.cancel')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto calculate final price
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const discountInput = document.getElementById('discount');
    const finalPriceInput = document.getElementById('final_price');
    
    function calculateFinalPrice() {
        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const finalPrice = price - discount;
        
        if (price > 0) {
            // Only auto-calculate if final price is empty or user wants to recalculate
            if (!finalPriceInput.value || confirm('<?php echo e(__('messages.recalculate_final_price')); ?>')) {
                finalPriceInput.value = finalPrice.toFixed(2);
            }
        }
    }
    
    priceInput.addEventListener('input', calculateFinalPrice);
    discountInput.addEventListener('input', calculateFinalPrice);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/orders/edit.blade.php ENDPATH**/ ?>