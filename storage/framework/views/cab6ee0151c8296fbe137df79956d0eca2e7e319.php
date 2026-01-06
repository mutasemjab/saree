<?php $__env->startSection('title'); ?>
notifications
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> Add New notifications   </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('notifications.send')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="form-group mt-0">
                                <label for="title">Title</label>
                                <input type="text" class="form-control <?php if($errors->has('title')): ?> is-invalid <?php endif; ?>" id="title" name="title" value="<?php echo e(old('title')); ?>">
                                <?php if($errors->has('title')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('title')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea name="body" id="body" class="form-control <?php if($errors->has('body')): ?> is-invalid <?php endif; ?>"><?php echo e(old('body')); ?></textarea>
                                <?php if($errors->has('body')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('body')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="type">Notification Type</label>
                            <select name="type" id="type" class="form-control">
                                    <option value="0">All Users and Drivers</option>
                                    <option value="1">Only Users</option>
                                    <option value="2">Only Drivers</option>
                                </select>

                                <?php if($errors->has('type')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('type')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php
                                use App\Models\User;
                                use App\Models\Driver;

                                $users = User::select('id', 'name')->get();
                                $drivers = Driver::select('id', 'name')->get();
                            ?>

                            <!-- Add this after the notification type -->
                            <div class="form-group" id="user-select" style="display: none;">
                                <label for="user_id">Select Specific User (optional)</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">-- All Users --</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group" id="driver-select" style="display: none;">
                                <label for="driver_id">Select Specific Driver (optional)</label>
                                <select name="driver_id" id="driver_id" class="form-control">
                                    <option value="">-- All Drivers --</option>
                                    <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($driver->id); ?>"><?php echo e($driver->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                         

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Send Notification</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




            </div>




        </div>
      </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script>
 document.getElementById('type').addEventListener('change', function () {
     let type = this.value;
     document.getElementById('user-select').style.display = type === '1' ? 'block' : 'none';
     document.getElementById('driver-select').style.display = type === '2' ? 'block' : 'none';
 });
</script>

<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/saree/resources/views/admin/notifications/create.blade.php ENDPATH**/ ?>