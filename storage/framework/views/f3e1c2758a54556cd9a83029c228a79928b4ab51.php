<?php $__env->startSection('content'); ?>
<div class="container">
    <h1><?php echo e(__('messages.Transactions_for_Wallet')); ?>: <?php echo e($wallet->user ? $wallet->user->name : $wallet->admin->name); ?></h1>
    <table class="table">
        <thead>
            <tr>
                <th><?php echo e(__('messages.Deposit')); ?></th>
                <th><?php echo e(__('messages.Withdrawal')); ?></th>
                <th><?php echo e(__('messages.Note')); ?></th>
                <th><?php echo e(__('messages.Date')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($transaction->deposit); ?></td>
                    <td><?php echo e($transaction->withdrawal); ?></td>
                    <td><?php echo e($transaction->note); ?></td>
                    <td><?php echo e($transaction->created_at); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <a href="<?php echo e(route('wallets.index')); ?>" class="btn btn-secondary">Back to Wallets</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/wallets/show.blade.php ENDPATH**/ ?>