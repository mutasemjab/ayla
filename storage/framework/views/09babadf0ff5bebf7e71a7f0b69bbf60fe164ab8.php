<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.Orders For Games')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Show')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>



      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> <?php echo e(__('messages.Orders For Games')); ?> </h3>
          <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">


        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
          <div class="col-md-4">

            

            

                      </div>

                          </div>
               <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">

            <?php if(isset($data) && !$data->isEmpty()): ?>

            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th><?php echo e(__('messages.Status')); ?></th>
                    <th><?php echo e(__('messages.selling_price')); ?></th>
                    <th><?php echo e(__('messages.number_of_game')); ?></th>
                    <th><?php echo e(__('messages.User')); ?></th>
                    <th><?php echo e(__('messages.product')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>

                        <td><?php if($info->order_status==1): ?> Accepted <?php elseif($info->order_status==2): ?> Failed <?php endif; ?></td>
                        <td><?php echo e($info->price); ?></td>
                        <td><?php echo e($info->number_of_game); ?></td>
                        <td><?php echo e($info->user->name); ?></td>
                        <td><?php echo e($info->product->name_ar); ?></td>

                        <td>
                            <a href="<?php echo e(route('orders.charge', $info->id)); ?>" class="btn btn-sm btn-primary">Charge</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="openNotificationModal(<?php echo e($info->user->id); ?>)">Send Notification</a>
                            <form action="<?php echo e(route('orders.destroy', $info->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
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

        </div>

<!-- Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo e(route('orders.notification.send')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="user_id" id="user_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Notification</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" id="body" name="body" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Send Notification</button>
        </div>
      </div>
    </form>
  </div>
</div>


      </div>

        </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
function openNotificationModal(userId) {
    $('#user_id').val(userId);
    $('#sendNotificationModal').modal('show');
}
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/orders/games.blade.php ENDPATH**/ ?>