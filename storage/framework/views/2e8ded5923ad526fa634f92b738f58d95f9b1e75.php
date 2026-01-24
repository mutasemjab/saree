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

                <form action="<?php echo e(route('wallets.store')); ?>" method="POST" id="transactionForm">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <!-- Step 1: Select Owner Type -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3"><?php echo e(__('messages.step')); ?> 1: <?php echo e(__('messages.select_owner_type')); ?></h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="owner_type" class="form-label"><?php echo e(__('messages.owner_type')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['owner_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="owner_type"
                                            name="owner_type"
                                            required>
                                        <option value=""><?php echo e(__('messages.select_owner_type')); ?></option>
                                        <option value="user" <?php echo e(old('owner_type') == 'user' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.user')); ?>

                                        </option>
                                        <option value="driver" <?php echo e(old('owner_type') == 'driver' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.driver')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['owner_type'];
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

                        <hr>

                        <!-- Step 2: Select User/Driver -->
                        <div id="ownerSelectionSection" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3"><?php echo e(__('messages.step')); ?> 2: <span id="ownerTypeLabel"></span></h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="owner_id" class="form-label">
                                            <span id="ownerLabel"></span> <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2 <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['driver_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="owner_id"
                                                name="owner_id"
                                                data-placeholder="<?php echo e(__('messages.search_select_owner')); ?>"
                                                required>
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

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><?php echo e(__('messages.current_balance')); ?></label>
                                        <div class="alert alert-info" id="currentBalance">
                                            <i class="fas fa-wallet"></i>
                                            <span id="balanceAmount">0.00</span> <?php echo e(__('messages.currency')); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden wallet_id field -->
                            <input type="hidden" name="wallet_id" id="wallet_id">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="driver_id" id="driver_id">

                            <hr>

                            <!-- Step 3: Transaction Details -->
                            <div id="transactionSection" style="display: none;">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="mb-3"><?php echo e(__('messages.step')); ?> 3: <?php echo e(__('messages.transaction_details')); ?></h5>
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
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    let ownerType = '';

    // When owner type is selected
    $('#owner_type').on('change', function() {
        ownerType = $(this).val();

        if (ownerType) {
            // Show owner selection section
            $('#ownerSelectionSection').slideDown();

            // Update labels
            if (ownerType === 'user') {
                $('#ownerTypeLabel').text("<?php echo e(__('messages.select_user')); ?>");
                $('#ownerLabel').text("<?php echo e(__('messages.user')); ?>");
            } else {
                $('#ownerTypeLabel').text("<?php echo e(__('messages.select_driver')); ?>");
                $('#ownerLabel').text("<?php echo e(__('messages.driver')); ?>");
            }

            // Reset and reinitialize Select2
            $('#owner_id').val(null).trigger('change');
            $('#transactionSection').hide();
            $('#submitBtn').hide();
            $('#currentBalance').hide();

            // Initialize Select2 with AJAX
            initializeOwnerSelect2(ownerType);
        } else {
            $('#ownerSelectionSection').slideUp();
            $('#transactionSection').hide();
            $('#submitBtn').hide();
        }
    });

    function initializeOwnerSelect2(type) {
        // Destroy existing Select2 instance
        if ($('#owner_id').hasClass("select2-hidden-accessible")) {
            $('#owner_id').select2('destroy');
        }

        // Clear existing options
        $('#owner_id').empty();

        // Initialize new Select2 with AJAX
        $('#owner_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "<?php echo e(__('messages.search_select_owner')); ?>",
            allowClear: true,
            ajax: {
                url: "<?php echo e(route('wallets.get-owners')); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        type: type
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            language: {
                noResults: function() {
                    return "<?php echo e(__('messages.no_results_found')); ?>";
                },
                searching: function() {
                    return "<?php echo e(__('messages.searching')); ?>...";
                },
                inputTooShort: function() {
                    return "<?php echo e(__('messages.type_to_search')); ?>";
                }
            }
        });
    }

    // When owner is selected
    $('#owner_id').on('select2:select', function (e) {
        const data = e.params.data;

        // Set wallet_id
        $('#wallet_id').val(data.wallet_id);

        // Set user_id or driver_id
        if (ownerType === 'user') {
            $('#user_id').val(data.id);
            $('#driver_id').val('');
        } else {
            $('#driver_id').val(data.id);
            $('#user_id').val('');
        }

        // Show current balance
        $('#balanceAmount').text(data.balance);
        $('#currentBalance').slideDown();

        // Show transaction section
        $('#transactionSection').slideDown();
        $('#submitBtn').show();
    });

    $('#owner_id').on('select2:clear', function (e) {
        $('#wallet_id').val('');
        $('#user_id').val('');
        $('#driver_id').val('');
        $('#currentBalance').hide();
        $('#transactionSection').slideUp();
        $('#submitBtn').hide();
    });

    // Ensure only deposit OR withdrawal is filled
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saree\resources\views/admin/wallets/create.blade.php ENDPATH**/ ?>