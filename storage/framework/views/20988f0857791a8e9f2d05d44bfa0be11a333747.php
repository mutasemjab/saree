<?php $__env->startSection('title', __('messages.add_transaction')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_transaction')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('wallets.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <form action="<?php echo e(route('wallets.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="wallet_id" class="form-label"><?php echo e(__('messages.wallet')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['wallet_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="wallet_id" 
                                            name="wallet_id" 
                                            required>
                                        <option value=""><?php echo e(__('messages.select_wallet')); ?></option>
                                        <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($wallet->id); ?>" <?php echo e(old('wallet_id') == $wallet->id ? 'selected' : ''); ?>>
                                                #<?php echo e($wallet->id); ?> - 
                                                <?php if($wallet->user): ?>
                                                    <?php echo e($wallet->user->name); ?> (<?php echo e(__('messages.user')); ?>)
                                                <?php elseif($wallet->driver): ?>
                                                    <?php echo e($wallet->driver->name); ?> (<?php echo e(__('messages.driver')); ?>)
                                                <?php elseif($wallet->admin): ?>
                                                    <?php echo e($wallet->admin->name ?? __('messages.admin')); ?> (<?php echo e(__('messages.admin')); ?>)
                                                <?php endif; ?>
                                                - <?php echo e(number_format($wallet->total, 2)); ?> <?php echo e(__('messages.currency')); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['wallet_id'];
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
                            <div class="col-12">
                                <h5 class="mb-3"><?php echo e(__('messages.transaction_type')); ?></h5>
                                <p class="text-muted"><?php echo e(__('messages.select_transaction_type_help')); ?></p>
                                
                                <?php $__errorArgs = ['transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="alert alert-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="deposit" class="form-label"><?php echo e(__('messages.deposit')); ?></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['deposit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="deposit" 
                                               name="deposit" 
                                               value="<?php echo e(old('deposit', 0)); ?>" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text"><?php echo e(__('messages.currency')); ?></span>
                                    </div>
                                    <?php $__errorArgs = ['deposit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-success"><?php echo e(__('messages.deposit_help')); ?></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="withdrawal" class="form-label"><?php echo e(__('messages.withdrawal')); ?></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['withdrawal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="withdrawal" 
                                               name="withdrawal" 
                                               value="<?php echo e(old('withdrawal', 0)); ?>" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text"><?php echo e(__('messages.currency')); ?></span>
                                    </div>
                                    <?php $__errorArgs = ['withdrawal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-danger"><?php echo e(__('messages.withdrawal_help')); ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="note" class="form-label"><?php echo e(__('messages.note')); ?></label>
                                    <textarea class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="note" 
                                              name="note" 
                                              rows="3"
                                              placeholder="<?php echo e(__('messages.transaction_note_placeholder')); ?>"><?php echo e(old('note')); ?></textarea>
                                    <?php $__errorArgs = ['note'];
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
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.save')); ?>

                        </button>
                        <a href="<?php echo e(route('wallets.index')); ?>" class="btn btn-secondary">
                            <?php echo e(__('messages.cancel')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Ensure only deposit OR withdrawal is filled
document.addEventListener('DOMContentLoaded', function() {
    const depositInput = document.getElementById('deposit');
    const withdrawalInput = document.getElementById('withdrawal');
    
    depositInput.addEventListener('input', function() {
        if (this.value > 0) {
            withdrawalInput.value = 0;
        }
    });
    
    withdrawalInput.addEventListener('input', function() {
        if (this.value > 0) {
            depositInput.value = 0;
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u919910150/domains/slinejo.com/public_html/resources/views/admin/wallets/create.blade.php ENDPATH**/ ?>