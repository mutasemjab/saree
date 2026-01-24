<?php $__env->startSection('title', __('messages.drivers')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.drivers')); ?></h3>
                    <a href="<?php echo e(route('drivers.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_driver')); ?>

                    </a>
                </div>
                
                <!-- Search and Filter Form -->
                <div class="card-body border-bottom">
                    <form method="GET" action="<?php echo e(route('drivers.index')); ?>" class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <label for="search" class="form-label"><?php echo e(__('messages.search')); ?></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="<?php echo e(request('search')); ?>" 
                                   placeholder="<?php echo e(__('messages.search_by_name_phone_id')); ?>">
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label for="status" class="form-label"><?php echo e(__('messages.status')); ?></label>
                            <select class="form-control" id="status" name="status">
                                <option value=""><?php echo e(__('messages.all_status')); ?></option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.active')); ?>

                                </option>
                                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.inactive')); ?>

                                </option>
                            </select>
                        </div>
                        
                        <!-- Date From -->
                        <div class="col-md-2">
                            <label for="date_from" class="form-label"><?php echo e(__('messages.from_date')); ?></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="<?php echo e(request('date_from')); ?>">
                        </div>
                        
                        <!-- Date To -->
                        <div class="col-md-2">
                            <label for="date_to" class="form-label"><?php echo e(__('messages.to_date')); ?></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="<?php echo e(request('date_to')); ?>">
                        </div>
                        
                        <!-- Filter Buttons -->
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.filter')); ?>

                                </button>
                                <a href="<?php echo e(route('drivers.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> <?php echo e(__('messages.clear')); ?>

                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <!-- Results Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted">
                                <?php echo e(__('messages.showing')); ?> <?php echo e($drivers->firstItem() ?? 0); ?> 
                                <?php echo e(__('messages.to')); ?> <?php echo e($drivers->lastItem() ?? 0); ?> 
                                <?php echo e(__('messages.of')); ?> <?php echo e($drivers->total()); ?> 
                                <?php echo e(__('messages.results')); ?>

                            </span>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" 
                                    id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort"></i> <?php echo e(__('messages.sort_by')); ?>

                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => 'asc'])); ?>">
                                        <?php echo e(__('messages.name')); ?> (A-Z)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => 'desc'])); ?>">
                                        <?php echo e(__('messages.name')); ?> (Z-A)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'desc'])); ?>">
                                        <?php echo e(__('messages.newest_first')); ?>

                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'asc'])); ?>">
                                        <?php echo e(__('messages.oldest_first')); ?>

                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo e(__('messages.id')); ?>

                                            <?php if(request('sort_by') === 'id'): ?>
                                                <i class="fas fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th><?php echo e(__('messages.photo')); ?></th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo e(__('messages.name')); ?>

                                            <?php if(request('sort_by') === 'name'): ?>
                                                <i class="fas fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th><?php echo e(__('messages.phone')); ?></th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'activate', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo e(__('messages.status')); ?>

                                            <?php if(request('sort_by') === 'activate'): ?>
                                                <i class="fas fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo e(__('messages.created_at')); ?>

                                            <?php if(request('sort_by') === 'created_at' || !request('sort_by')): ?>
                                                <i class="fas fa-sort-<?php echo e((request('sort_order') === 'asc') ? 'up' : 'down'); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($driver->id); ?></td>
                                        <td>
                                            <img src="<?php echo e(asset('assets/admin/uploads') . '/' . $driver->photo); ?>" 
                                                 alt="<?php echo e($driver->name); ?>" 
                                                 class="rounded-circle" 
                                                 width="50" height="50">
                                        </td>
                                        <td><?php echo e($driver->name); ?></td>
                                        <td><?php echo e($driver->phone); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($driver->activate == 1 ? 'success' : 'danger'); ?>">
                                                <?php echo e($driver->activation_status); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($driver->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('drivers.show', $driver)); ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('drivers.edit', $driver)); ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('drivers.toggle-activation', $driver)); ?>" 
                                                      method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-<?php echo e($driver->activate == 1 ? 'secondary' : 'success'); ?> btn-sm"
                                                            title="<?php echo e($driver->activate == 1 ? __('messages.deactivate') : __('messages.activate')); ?>">
                                                        <i class="fas fa-<?php echo e($driver->activate == 1 ? 'ban' : 'check'); ?>"></i>
                                                    </button>
                                                </form>
                                              
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <?php if(request()->anyFilled(['search', 'status', 'date_from', 'date_to'])): ?>
                                                <?php echo e(__('messages.no_drivers_found_with_filters')); ?>

                                                <br>
                                                <a href="<?php echo e(route('drivers.index')); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                    <?php echo e(__('messages.clear_filters')); ?>

                                                </a>
                                            <?php else: ?>
                                                <?php echo e(__('messages.no_drivers_found')); ?>

                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($drivers->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    // Auto-submit form on status change (optional)
    document.getElementById('status').addEventListener('change', function() {
        // Uncomment the line below if you want auto-submit on status change
        // this.form.submit();
    });
    
    // Clear form function
    function clearFilters() {
        window.location.href = "<?php echo e(route('drivers.index')); ?>";
    }
    
    // Enter key search
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/drivers/index.blade.php ENDPATH**/ ?>