<?php $__env->startSection('title'); ?>
الرئيسية
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    padding: 20px;
}

.card {
    background-color: #fff;
    border-left: 5px solid #0dcaf0;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    padding: 20px;
    text-align: center;
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
}
.card h2 {
    font-size: 16px;
    color: #6c757d;
}
.card p {
    font-size: 28px;
    font-weight: bold;
    color: #0d6efd;
}
.driver-status {
    margin-top: 30px;
}
.driver-status table {
    width: 100%;
    border-collapse: collapse;
}
.driver-status th, .driver-status td {
    border: 1px solid #dee2e6;
    padding: 10px;
    text-align: center;
}
.driver-status th {
    background-color: #f8f9fa;
}
.status-online {
    color: green;
    font-weight: bold;
}
.status-offline {
    color: red;
    font-weight: bold;
}
</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('admin.dashboard')); ?>"> الرئيسية </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
عرض
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="dashboard">
    <div class="card">
        <h2><?php echo e(__('messages.All Customers')); ?></h2>
        <p><?php echo e($totalCustomers); ?></p>
    </div>
    <div class="card">
        <h2><?php echo e(__('messages.All Drivers')); ?></h2>
        <p><?php echo e($totalDrivers); ?></p>
    </div>
    <div class="card">
        <h2><?php echo e(__('messages.Customers with Orders (This Month)')); ?></h2>
        <p><?php echo e($customersWithOrdersThisMonth); ?></p>
    </div>
    <div class="card">
        <h2><?php echo e(__('messages.New Users This Month')); ?></h2>
        <p><?php echo e($newUsersThisMonth); ?></p>
    </div>
    <div class="card">
        <h2><?php echo e(__('messages.Total Orders')); ?></h2>
        <p><?php echo e($totalOrders); ?></p>
    </div>
</div>

<div class="driver-status">
    <h3>🚗 حالة السائقين (Drivers Status)</h3>

    <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" style="margin-bottom: 15px;">
        <label for="status">تصفية حسب الحالة:</label>
        <select class="form-control" name="status" id="status" onchange="this.form.submit()">
            <option value="">الكل</option>
            <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>متصل</option>
            <option value="2" <?php echo e(request('status') == '2' ? 'selected' : ''); ?>>غير متصل</option>
        </select>
    </form>

    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>رقم الجوال</th>
                <th>الحالة</th>
                <th>المدينة</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($driver->name); ?></td>
                <td><?php echo e($driver->phone); ?></td>
                <td>
                    <span class="<?php echo e($driver->status == 1 ? 'status-online' : 'status-offline'); ?>">
                        <?php echo e($driver->status == 1 ? 'متصل' : 'غير متصل'); ?>

                    </span>
                </td>
                <td><?php echo e($driver->city->name ?? '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4">لا توجد سجلات</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saree\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>