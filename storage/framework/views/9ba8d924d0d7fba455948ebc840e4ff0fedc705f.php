<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.transferBanks')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.transferBanks')); ?> </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <!-- You can add filters or other controls here if needed -->
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transferBank-table')): ?>
            <?php if(@isset($data) && !@empty($data) && count($data) > 0): ?>
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <tr>
                        <th><?php echo e(__('messages.User')); ?></th>
                        <th><?php echo e(__('messages.Amount')); ?></th>
                        <th><?php echo e(__('messages.Status')); ?></th>
                        <th><?php echo e(__('messages.Actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($info->user->name); ?></td>
                        <td><?php echo e($info->amount); ?></td>
                        <td>
                            <?php if($info->status == 1): ?>
                            <?php echo e(__('messages.Approved')); ?>

                            <?php elseif($info->status == 2): ?>
                            <?php echo e(__('messages.Pending')); ?>

                            <?php elseif($info->status == 3): ?>
                            <?php echo e(__('messages.Rejected')); ?>

                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($info->status == 2): ?> <!-- Only show approve/reject buttons if pending -->
                            <a href="<?php echo e(route('transferBanks.approve', $info->id)); ?>" class="btn btn-success"><?php echo e(__('messages.Approve')); ?></a>
                            <a href="<?php echo e(route('transferBanks.reject', $info->id)); ?>" class="btn btn-danger"><?php echo e(__('messages.Reject')); ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <br>
            <?php echo e($data->links()); ?>

            <?php else: ?>
            <div class="alert alert-danger">
                <?php echo e(__('messages.No_data')); ?>

            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/transferBanks/index.blade.php ENDPATH**/ ?>