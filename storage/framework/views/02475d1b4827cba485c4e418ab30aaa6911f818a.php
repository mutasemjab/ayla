<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Receivables')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

<div class="container">
    <h1>Receivables</h1>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Total Amount</th>
                <th>Remaining Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $receivables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receivable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($receivable->user->name); ?></td>
                <td><?php echo e($receivable->total_amount); ?></td>
                <td><?php echo e($receivable->remaining_amount); ?></td>
                <td>
                    <a href="<?php echo e(route('receivables.edit', $receivable->id)); ?>" class="btn btn-primary">Edit</a>
                    <a href="<?php echo e(route('receivables.show', $receivable->user_id)); ?>" class="btn btn-info">Show</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/receivables/index.blade.php ENDPATH**/ ?>