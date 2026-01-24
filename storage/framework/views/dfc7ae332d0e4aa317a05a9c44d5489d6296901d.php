<?php $__env->startSection('title', __('messages.edit_user')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.edit_user')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                
                <form action="<?php echo e(route('users.update', $user)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label"><?php echo e(__('messages.name')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo e(old('name', $user->name)); ?>" 
                                           required>
                                    <?php $__errorArgs = ['name'];
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
                                    <label for="phone" class="form-label"><?php echo e(__('messages.phone')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?php echo e(old('phone', $user->phone)); ?>" 
                                           required>
                                    <?php $__errorArgs = ['phone'];
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
                                    <label for="password" class="form-label"><?php echo e(__('messages.password')); ?></label>
                                    <input type="password" 
                                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="password" 
                                           name="password">
                                    <small class="form-text text-muted"><?php echo e(__('messages.leave_blank_to_keep_current')); ?></small>
                                    <?php $__errorArgs = ['password'];
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
                                <label><?php echo e(__('messages.City')); ?></label>
                                <select name="city_id" id="city" class="form-control">
                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($city->id); ?>"
                                            <?php echo e(old('city_id', isset($user) ? $user->city_id : '') == $city->id ? 'selected' : ''); ?>>
                                            <?php echo e($city->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
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
                                    <label for="photo" class="form-label"><?php echo e(__('messages.photo')); ?></label>
                                    <?php if($user->photo): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo e(asset('assets/admin/uploads') . '/' . $user->photo); ?>" 
                                                 alt="<?php echo e($user->name); ?>" 
                                                 class="img-thumbnail" 
                                                 width="100">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" 
                                           class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*">
                                    <?php $__errorArgs = ['photo'];
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
                                    <label for="activate" class="form-label"><?php echo e(__('messages.status')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['activate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="activate" 
                                            name="activate" 
                                            required>
                                        <option value=""><?php echo e(__('messages.select_status')); ?></option>
                                        <option value="1" <?php echo e(old('activate', $user->activate) == '1' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.active')); ?>

                                        </option>
                                        <option value="2" <?php echo e(old('activate', $user->activate) == '2' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.inactive')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['activate'];
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
                            <i class="fas fa-save"></i> <?php echo e(__('messages.update')); ?>

                        </button>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                            <?php echo e(__('messages.cancel')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/users/edit.blade.php ENDPATH**/ ?>