<?php $__env->startSection('title'); ?>

edit Admin Login
<?php $__env->stopSection(); ?>




<?php $__env->startSection('contentheaderactive'); ?>
تعديل
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> edit Admin </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

    
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting-table')): ?>
      <form action="<?php echo e(route('admin.login.update',$data['id'])); ?>" method="post" >
        <div class="row">
        <?php echo csrf_field(); ?>



        <div class="col-md-6">
<div class="form-group">
  <label>username</label>
  <input name="username" id="name" class="form-control" value="<?php echo e(old('username',$data['username'])); ?>"    >
  <?php $__errorArgs = ['username'];
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
    <label>   password</label>
    <input name="password" id="email" class="form-control" value=""    >
    <?php $__errorArgs = ['password'];
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
        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> update</button>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-danger">cancel</a>

      </div>
    </div>

  </div>
            </form>
            <?php endif; ?>


            </div>




        </div>
      </div>






<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/auth/edit.blade.php ENDPATH**/ ?>