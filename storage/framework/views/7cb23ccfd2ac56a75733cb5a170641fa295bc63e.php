<?php $__env->startSection('title', __('messages.add_setting')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_setting')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <form action="<?php echo e(route('settings.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="key" class="form-label"><?php echo e(__('messages.key')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="key" 
                                           name="key" 
                                           value="<?php echo e(old('key')); ?>" 
                                           placeholder="<?php echo e(__('messages.setting_key_placeholder')); ?>"
                                           required>
                                    <?php $__errorArgs = ['key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted"><?php echo e(__('messages.setting_key_help')); ?></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="value" class="form-label"><?php echo e(__('messages.value')); ?> <span class="text-danger">*</span></label>
                                    <textarea class="form-control <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="value" 
                                              name="value" 
                                              rows="4"
                                              placeholder="<?php echo e(__('messages.setting_value_placeholder')); ?>"
                                              required><?php echo e(old('value')); ?></textarea>
                                    <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted"><?php echo e(__('messages.setting_value_help')); ?></small>
                                </div>
                            </div>
                        </div>

                        <!-- Common Settings Examples -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-lightbulb"></i> <?php echo e(__('messages.common_setting_examples')); ?>

                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><?php echo e(__('messages.app_settings')); ?>:</h6>
                                                <ul class="list-unstyled small">
                                                    <li><code>app_name</code> - <?php echo e(__('messages.application_name')); ?></li>
                                                    <li><code>app_version</code> - <?php echo e(__('messages.application_version')); ?></li>
                                                    <li><code>maintenance_mode</code> - <?php echo e(__('messages.maintenance_mode')); ?></li>
                                                    <li><code>max_users</code> - <?php echo e(__('messages.maximum_users')); ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><?php echo e(__('messages.notification_settings')); ?>:</h6>
                                                <ul class="list-unstyled small">
                                                    <li><code>fcm_server_key</code> - <?php echo e(__('messages.fcm_server_key')); ?></li>
                                                    <li><code>email_notifications</code> - <?php echo e(__('messages.email_notifications')); ?></li>
                                                    <li><code>sms_gateway</code> - <?php echo e(__('messages.sms_gateway')); ?></li>
                                                    <li><code>push_notifications</code> - <?php echo e(__('messages.push_notifications')); ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.save')); ?>

                        </button>
                        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">
                            <?php echo e(__('messages.cancel')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/settings/create.blade.php ENDPATH**/ ?>