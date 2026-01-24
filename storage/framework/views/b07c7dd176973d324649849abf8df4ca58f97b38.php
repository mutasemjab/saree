<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">

                            <li class="breadcrumb-item"><a
                                    href="<?php echo e(route('admin.role.index')); ?>">Role</a>
                            </li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Role</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.role.store')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="my-3">
                                <input type="text"
                                    class="form-control <?php if($errors->has('name')): ?> is-invalid <?php endif; ?>" id="name"
                                    placeholder=" Name" value="<?php echo e(old('name')); ?>" name="name">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <span class="emsg text-danger"></span>
                            </div>
                            <h1>Permission</h1>
                            <div class="my-3">
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <br>
                                    <input <?php echo e(in_array( $value->id,old('perms')? old('perms'): []) ? 'checked':''); ?> class="ml-5" type="checkbox" name="perms[]" id="perm_<?php echo e($value->id); ?>" value="<?php echo e($value->id); ?>">
                                    <label for="perm_<?php echo e($value->id); ?>"> <?php echo e($value->name); ?>. </label>
                                    <br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="row" id="permissions">
                                <?php $__errorArgs = ['perms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <span class="emsg text-danger"></span>
                            </div>




                            <div class="text-right">
                                <button type="submit"
                                    class="btn btn-success waves-effect waves-light">Save</button>
                                <a type="button" href="<?php echo e(route('admin.role.index')); ?>"
                                    class="btn btn-danger waves-effect waves-light m-l-10">Cancel
                                </a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $('#guard_name').change(function(e) {
            guard_name = $('#guard_name').val();
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "/admin" + '/permissions/' + guard_name,
                success: function(response) {
                    $('#permissions').empty();
                    $.each(response, function(i, val) {
                        $('#permissions').append(
                            '<div class="col-8"><input type="checkbox" class="mx-2" name="permissions[]" value=' +
                            val.id + '>' + val.name + '</div>');
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/roles/create.blade.php ENDPATH**/ ?>