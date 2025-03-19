<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.questions')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"><?php echo e(__('messages.questions')); ?></h3>

        <a href="<?php echo e(route('questions.create')); ?>" class="btn btn-sm btn-success"><?php echo e(__('messages.New')); ?> <?php echo e(__('messages.questions')); ?></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="clearfix"></div>

        <div class="col-md-12">
            <?php if(isset($questions) && !empty($questions) && count($questions) > 0): ?>
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="custom_thead">
                        <th><?php echo e(__('messages.question')); ?></th>
                        <th><?php echo e(__('messages.answer')); ?></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($question->question); ?></td>
                                <td><?php echo e($question->answer); ?></td>
                                <td>
                                    <a href="<?php echo e(route('questions.edit', $question->id)); ?>" class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                                    <form action="<?php echo e(route('questions.destroy', $question->id)); ?>" method="POST">
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



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/questions/index.blade.php ENDPATH**/ ?>