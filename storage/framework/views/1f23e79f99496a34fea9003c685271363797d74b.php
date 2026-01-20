<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Pages')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"><?php echo e(__('messages.Pages')); ?></h3>

        <a href="<?php echo e(route('pages.create')); ?>" class="btn btn-sm btn-success"><?php echo e(__('messages.New')); ?> <?php echo e(__('messages.Pages')); ?></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="clearfix"></div>

        <div class="col-md-12">
            <?php if(isset($pages) && !empty($pages) && count($pages) > 0): ?>
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="custom_thead">
                        <th><?php echo e(__('messages.Type')); ?></th>
                        <th><?php echo e(__('messages.Title')); ?></th>
                        <th><?php echo e(__('messages.Content')); ?></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                     <?php if($page->type ==1 ): ?>
                                     about us
                                     <?php elseif($page->type ==2 ): ?>
                                     Terms and Conditions
                                     <?php elseif($page->type ==3 ): ?>
                                     Privacy Policy
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($page->title); ?></td>
                                <td><?php echo e($page->content); ?></td>
                                <td>
                                    <a href="<?php echo e(route('pages.edit', [ 'id' => $page->id])); ?>" class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                                    <form action="<?php echo e(route('pages.destroy', ['id' => $page->id])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger"><?php echo e(__('messages.Delete')); ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-danger"><?php echo e(__('messages.No_data')); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/admin/js/sliderss.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/pages/index.blade.php ENDPATH**/ ?>