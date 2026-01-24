questions@extends('layouts.admin')
<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Pages')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.Add_New')); ?> <?php echo e(__('messages.Pages')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="<?php echo e(route('pages.store')); ?>" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> <?php echo e(__('messages.Title')); ?></label>
                            <input name="title" id="title" class="form-control" value="<?php echo e(old('title')); ?>">
                            <?php $__errorArgs = ['title'];
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> <?php echo e(__('messages.Content')); ?></label>
                            <textarea name="content" id="content" class="form-control" rows="12"></textarea>
                            <?php $__errorArgs = ['content'];
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


                    <div class="col-md-6">
                        <div class="form-group">
                          <label>  <?php echo e(__('messages.Type')); ?></label>
                          <select name="type" id="type" class="form-control">
                           <option value=""> select</option>
                          <option   <?php if(old('type')==1  || old('type')=="" ): ?> selected="selected"  <?php endif; ?> value="1"> About Us</option>
                           <option <?php if( (old('type')==2 and old('type')!="")): ?> selected="selected"  <?php endif; ?>   value="2"> Terms & Condition</option>
                           <option <?php if( (old('type')==3 and old('type')!="")): ?> selected="selected"  <?php endif; ?>   value="3"> Privacy & Policy</option>
                          </select>
                          <?php $__errorArgs = ['type'];
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



                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> <?php echo e(__('messages.Submit')); ?></button>
                            <a href="<?php echo e(route('pages.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php /**PATH /home/slinejo/public_html/resources/views/admin/pages/create.blade.php ENDPATH**/ ?>