<?php $__env->startSection('title', __('messages.wallet_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Wallet Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-wallet"></i>
                        <?php echo e(__('messages.wallet_details')); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('wallets.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i>
                            <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><?php echo e(__('messages.wallet_id')); ?>:</strong></td>
                                    <td>#<?php echo e($wallet->id); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo e(__('messages.current_balance')); ?>:</strong></td>
                                    <td>
                                        <span class="badge badge-success badge-lg">
                                            <?php echo e(number_format($wallet->total, 2)); ?> <?php echo e(__('messages.currency')); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo e(__('messages.wallet_owner')); ?>:</strong></td>
                                    <td>
                                        <?php if($wallet->user): ?>
                                            <span class="badge badge-info"><?php echo e(__('messages.user')); ?></span>
                                            <?php echo e($wallet->user->name); ?>

                                        <?php elseif($wallet->driver): ?>
                                            <span class="badge badge-warning"><?php echo e(__('messages.driver')); ?></span>
                                            <?php echo e($wallet->driver->name); ?>

                                        <?php elseif($wallet->admin): ?>
                                            <span class="badge badge-danger"><?php echo e(__('messages.admin')); ?></span>  
                                            <?php echo e($wallet->admin->name); ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo e(__('messages.created_at')); ?>:</strong></td>
                                    <td><?php echo e($wallet->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.total_deposits')); ?></span>
                                            <span class="info-box-number"><?php echo e(number_format($totalDeposits, 2)); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger">
                                            <i class="fas fa-arrow-down"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.total_withdrawals')); ?></span>
                                            <span class="info-box-number"><?php echo e(number_format($totalWithdrawals, 2)); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-list"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.transactions')); ?></span>
                                            <span class="info-box-number"><?php echo e($transactionCount); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt"></i>
                        <?php echo e(__('messages.wallet_transactions')); ?>

                    </h3>
                </div>
                <div class="card-body">
                    <?php if($wallet->transactions->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('messages.id')); ?></th>
                                        <th><?php echo e(__('messages.type')); ?></th>
                                        <th><?php echo e(__('messages.amount')); ?></th>
                                        <th><?php echo e(__('messages.note')); ?></th>
                                        <th><?php echo e(__('messages.processed_by')); ?></th>
                                        <th><?php echo e(__('messages.date')); ?></th>
                                        <th><?php echo e(__('messages.actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $wallet->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($transaction->id); ?></td>
                                            <td>
                                                <?php if($transaction->deposit > 0): ?>
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-arrow-up"></i>
                                                        <?php echo e(__('messages.deposit')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-arrow-down"></i>
                                                        <?php echo e(__('messages.withdrawal')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($transaction->deposit > 0): ?>
                                                    <span class="text-success">
                                                        +<?php echo e(number_format($transaction->deposit, 2)); ?> <?php echo e(__('messages.currency')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-danger">
                                                        -<?php echo e(number_format($transaction->withdrawal, 2)); ?> <?php echo e(__('messages.currency')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($transaction->note): ?>
                                                    <span class="text-muted"><?php echo e(Str::limit($transaction->note, 50)); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted"><?php echo e(__('messages.no_note')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($transaction->admin): ?>
                                                    <span class="badge badge-danger"><?php echo e(__('messages.admin')); ?></span>
                                                    <?php echo e($transaction->admin->name); ?>

                                                <?php elseif($transaction->user): ?>
                                                    <span class="badge badge-info"><?php echo e(__('messages.user')); ?></span>
                                                    <?php echo e($transaction->user->name); ?>

                                                <?php elseif($transaction->driver): ?>
                                                    <span class="badge badge-warning"><?php echo e(__('messages.driver')); ?></span>
                                                    <?php echo e($transaction->driver->name); ?>

                                                <?php else: ?>
                                                    <span class="text-muted"><?php echo e(__('messages.system')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($transaction->created_at->format('Y-m-d H:i:s')); ?></td>
                                            <td>
                                                 <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('transactions.edit', $transaction)); ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="<?php echo e(__('messages.edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('transactions.destroy', $transaction)); ?>" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('<?php echo e(__('messages.delete_transaction_confirmation')); ?>')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="<?php echo e(__('messages.delete')); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted"><?php echo e(__('messages.no_transactions')); ?></h4>
                            <p class="text-muted"><?php echo e(__('messages.no_transactions_description')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/slinejo/public_html/resources/views/admin/wallets/show.blade.php ENDPATH**/ ?>