<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.wallets')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.wallets')); ?> </h3>


    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">


            </div>

        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('banner-table')): ?>
            <?php if(@isset($wallets) && !@empty($wallets) && count($wallets) > 0): ?>
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">


                    <th><?php echo e(__('messages.User')); ?></th>
                    <th><?php echo e(__('messages.Total')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>

                </thead>
                <tbody>
                    <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($wallet->user ? $wallet->user->name : $wallet->admin->name); ?></td>
                        <td><?php echo e($wallet->total); ?></td>
                        <td>
                            <a href="<?php echo e(route('wallets.show', $wallet->id)); ?>" class="btn btn-primary">
                                <?php echo e(__('messages.View_Transactions')); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                </tbody>
            </table>
            <br>
            <?php echo e($wallets->links()); ?>

            <?php else: ?>
            <div class="alert alert-danger">
                <?php echo e(__('messages.No_wallets')); ?> </div>
            <?php endif; ?>
            <?php endif; ?>

        </div>



    </div>

</div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/admin/js/sliderss.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/wallets/index.blade.php ENDPATH**/ ?>