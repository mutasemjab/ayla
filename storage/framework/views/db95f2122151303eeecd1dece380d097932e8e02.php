<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/libs/dropify/dropify.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a
                                    href="<?php echo e(route('admin.employee.index')); ?>">Employee</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Employee</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.employee.store')); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control <?php if($errors->has('name')): ?> is-invalid <?php endif; ?>"
                                        id="name" placeholder="Name" value="<?php echo e(old('name')); ?>" name="name">
                                    <?php if($errors->has('name')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('name')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">username<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="username"
                                        class="form-control <?php if($errors->has('username')): ?> is-invalid <?php endif; ?>"
                                        id="username" placeholder="username" value="<?php echo e(old('username')); ?>" name="username">
                                    <?php if($errors->has('username')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('username')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control <?php if($errors->has('password')): ?> is-invalid <?php endif; ?>"
                                        id="password" placeholder="password" value="<?php echo e(old('password')); ?>" name="password">
                                    <?php if($errors->has('password')): ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($errors->first('password')); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                             <div class="my-3">
                               <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <br>
                                    <input <?php echo e(in_array( $role->id,old('roles')? old('roles'): []) ? 'checked':''); ?> class="ml-5" type="checkbox" name="roles[]" id="role_<?php echo e($role->id); ?>" value="<?php echo e($role->id); ?>">
                                    <label for="role_<?php echo e($role->id); ?>"> <?php echo e($role->name); ?>. </label>
                                    <br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="row" id="permissions">
                                <?php $__errorArgs = ['perms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <span class="emsg text-danger"></span>
                            </div>


                            <div class="text-right">
                                <button type="submit"
                                    class="btn btn-success waves-effect waves-light">Save</button>
                                <a type="button" href="<?php echo e(route('admin.employee.index')); ?>"
                                    class="btn btn-danger waves-effect waves-light m-l-10">Cancel
                                </a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/libs/dropzone/dropzone.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/dropify/dropify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/form-fileuploads.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.admin", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/employee/create.blade.php ENDPATH**/ ?>