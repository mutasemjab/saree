<?php $__env->startSection('title', __('messages.wallets')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.wallets')); ?></h3>
                    <div>
                        <a href="<?php echo e(route('wallets.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_transaction')); ?>

                        </a>
                    </div>
                </div>

                <!-- Search and Filter Form -->
                <div class="card-body border-bottom">
                    <form method="GET" action="<?php echo e(route('wallets.index')); ?>" class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-3">
                            <label for="search" class="form-label"><?php echo e(__('messages.search')); ?></label>
                            <input type="text"
                                   class="form-control"
                                   id="search"
                                   name="search"
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="<?php echo e(__('messages.search_wallet')); ?>">
                        </div>

                        <!-- Owner Type Filter -->
                        <div class="col-md-2">
                            <label for="type" class="form-label"><?php echo e(__('messages.owner_type')); ?></label>
                            <select name="type" id="type" class="form-control">
                                <option value=""><?php echo e(__('messages.all_types')); ?></option>
                                <option value="user" <?php echo e(request('type') == 'user' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.user')); ?>

                                </option>
                                <option value="driver" <?php echo e(request('type') == 'driver' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.driver')); ?>

                                </option>
                           
                            </select>
                        </div>

                        <!-- Balance Status Filter -->
                        <div class="col-md-2">
                            <label for="balance_status" class="form-label"><?php echo e(__('messages.balance_status')); ?></label>
                            <select name="balance_status" id="balance_status" class="form-control">
                                <option value=""><?php echo e(__('messages.all_balances')); ?></option>
                                <option value="positive" <?php echo e(request('balance_status') == 'positive' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.positive')); ?>

                                </option>
                                <option value="negative" <?php echo e(request('balance_status') == 'negative' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.negative')); ?>

                                </option>
                                <option value="zero" <?php echo e(request('balance_status') == 'zero' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.zero')); ?>

                                </option>
                            </select>
                        </div>

                        <!-- Balance Min -->
                        <div class="col-md-2">
                            <label for="balance_min" class="form-label"><?php echo e(__('messages.balance_min')); ?></label>
                            <input type="number"
                                   class="form-control"
                                   id="balance_min"
                                   name="balance_min"
                                   step="0.01"
                                   value="<?php echo e(request('balance_min')); ?>"
                                   placeholder="0.00">
                        </div>

                        <!-- Balance Max -->
                        <div class="col-md-2">
                            <label for="balance_max" class="form-label"><?php echo e(__('messages.balance_max')); ?></label>
                            <input type="number"
                                   class="form-control"
                                   id="balance_max"
                                   name="balance_max"
                                   step="0.01"
                                   value="<?php echo e(request('balance_max')); ?>"
                                   placeholder="1000.00">
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

                        <!-- Sort By -->
                        <div class="col-md-2">
                            <label for="sort_by" class="form-label"><?php echo e(__('messages.sort_by')); ?></label>
                            <select name="sort_by" id="sort_by" class="form-control">
                                <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.date')); ?>

                                </option>
                                <option value="total" <?php echo e(request('sort_by') == 'total' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.balance')); ?>

                                </option>
                                <option value="id" <?php echo e(request('sort_by') == 'id' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.id')); ?>

                                </option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label"><?php echo e(__('messages.order')); ?></label>
                            <select name="sort_order" id="sort_order" class="form-control">
                                <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.descending')); ?>

                                </option>
                                <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.ascending')); ?>

                                </option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.filter')); ?>

                                </button>
                                <a href="<?php echo e(route('wallets.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
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
                                <?php echo e(__('messages.showing')); ?> <?php echo e($wallets->firstItem() ?? 0); ?>

                                <?php echo e(__('messages.to')); ?> <?php echo e($wallets->lastItem() ?? 0); ?>

                                <?php echo e(__('messages.of')); ?> <?php echo e($wallets->total()); ?>

                                <?php echo e(__('messages.results')); ?>

                            </span>
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
                                    <th><?php echo e(__('messages.owner')); ?></th>
                                    <th><?php echo e(__('messages.owner_type')); ?></th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'total', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])); ?>"
                                           class="text-decoration-none text-dark">
                                            <?php echo e(__('messages.total_balance')); ?>

                                            <?php if(request('sort_by') === 'total'): ?>
                                                <i class="fas fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th><?php echo e(__('messages.transactions_count')); ?></th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])); ?>"
                                           class="text-decoration-none text-dark">
                                            <?php echo e(__('messages.created_at')); ?>

                                            <?php if(request('sort_by') === 'created_at' || !request('sort_by')): ?>
                                                <i class="fas fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($wallet->id); ?></td>
                                        <td>
                                            <?php if($wallet->user): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user me-2 text-primary"></i>
                                                    <?php echo e($wallet->user->name); ?>

                                                </div>
                                            <?php elseif($wallet->driver): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-car me-2 text-success"></i>
                                                    <?php echo e($wallet->driver->name); ?>

                                                </div>
                                            <?php elseif($wallet->admin): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-shield me-2 text-warning"></i>
                                                    <?php echo e($wallet->admin->name ?? __('messages.admin')); ?>

                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.no_owner')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($wallet->user): ?>
                                                <span class="badge bg-primary"><?php echo e(__('messages.user')); ?></span>
                                            <?php elseif($wallet->driver): ?>
                                                <span class="badge bg-success"><?php echo e(__('messages.driver')); ?></span>
                                            <?php elseif($wallet->admin): ?>
                                                <span class="badge bg-warning"><?php echo e(__('messages.admin')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e(__('messages.unknown')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-<?php echo e($wallet->total > 0 ? 'success' : ($wallet->total < 0 ? 'danger' : 'secondary')); ?>">
                                                <?php echo e(number_format($wallet->total, 2)); ?> <?php echo e(__('messages.currency')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($wallet->transactions_count ?? 0); ?></span>
                                        </td>
                                        <td><?php echo e($wallet->created_at ? $wallet->created_at->format('Y-m-d H:i') : '-'); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('wallets.show', $wallet)); ?>"
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <?php if(request()->anyFilled(['search', 'type', 'balance_status', 'balance_min', 'balance_max', 'date_from', 'date_to'])): ?>
                                                <?php echo e(__('messages.no_wallets_found_with_filters')); ?>

                                                <br>
                                                <a href="<?php echo e(route('wallets.index')); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                    <?php echo e(__('messages.clear_filters')); ?>

                                                </a>
                                            <?php else: ?>
                                                <?php echo e(__('messages.no_wallets_found')); ?>

                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($wallets->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saree\resources\views/admin/wallets/index.blade.php ENDPATH**/ ?>