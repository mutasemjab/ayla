<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="<?php echo e(asset('assets/admin/dist/img/AdminLTELogo.png')); ?>" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Ayla</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('assets/admin/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo e(auth()->user()->name); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <?php if(
                $user->can('sectionUser-table') ||
                $user->can('sectionUser-add') ||
                $user->can('sectionUser-edit') ||
                $user->can('sectionUser-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('sectionUsers.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.sectionUsers')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(
                $user->can('customer-table') ||
                $user->can('customer-add') ||
                $user->can('customer-edit') ||
                $user->can('customer-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.customer.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.Customers')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>

              <?php if(
                $user->can('dealer-table') ||
                $user->can('dealer-add') ||
                $user->can('dealer-edit') ||
                $user->can('dealer-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dealers.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.dealers')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>


                <?php if(
                $user->can('cardPackage-table') ||
                $user->can('cardPackage-add') ||
                $user->can('cardPackage-edit') ||
                $user->can('cardPackage-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('cardPackages.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.cardPackages')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>

           <?php if(
                $user->can('category-table') ||
                $user->can('category-add') ||
                $user->can('category-edit') ||
                $user->can('category-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('categories.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.categories')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(
                $user->can('unit-table') ||
                $user->can('unit-add') ||
                $user->can('unit-edit') ||
                $user->can('unit-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('units.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.units')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(
                $user->can('product-table') ||
                $user->can('product-add') ||
                $user->can('product-edit') ||
                $user->can('product-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('products.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.products')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>


                <?php if(
                $user->can('offer-table') ||
                $user->can('offer-add') ||
                $user->can('offer-edit') ||
                $user->can('offer-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('offers.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.offers')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>


                <?php if(
                    $user->can('transfer-table') ||
                        $user->can('transfer-add') ||
                        $user->can('transfer-edit') ||
                        $user->can('transfer-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('transfers.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.transfers')); ?>  </p>
                        </a>
                    </li>
                <?php endif; ?>

            <?php if(
                    $user->can('requestBalance-table') ||
                        $user->can('requestBalance-add') ||
                        $user->can('requestBalance-edit') ||
                        $user->can('requestBalance-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('requestBalances.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.requestBalances')); ?>  </p>
                        </a>
                    </li>
                <?php endif; ?>

            <?php if(
                    $user->can('payInvoice-table') ||
                        $user->can('payInvoice-add') ||
                        $user->can('payInvoice-edit') ||
                        $user->can('payInvoice-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('payInvoices.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.payInvoices')); ?>  </p>
                        </a>
                    </li>
                <?php endif; ?>

            <?php if(
                    $user->can('transferBank-table') ||
                        $user->can('transferBank-add') ||
                        $user->can('transferBank-edit') ||
                        $user->can('transferBank-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('transferBanks.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.transferBanks')); ?>  </p>
                        </a>
                    </li>
                <?php endif; ?>

                   <?php if(
                    $user->can('wallet-table') ||
                        $user->can('wallet-add') ||
                        $user->can('wallet-edit') ||
                        $user->can('wallet-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('wallets.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.wallets')); ?>  </p>
                        </a>
                    </li>
                <?php endif; ?>




                <?php if(
                $user->can('order-table') ||
                $user->can('order-add') ||
                $user->can('order-edit') ||
                $user->can('order-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.Orders')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>
         
                <?php if(
                $user->can('order-table') ||
                $user->can('order-add') ||
                $user->can('order-edit') ||
                $user->can('order-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('orders.game')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.Orders For Games')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>


                <?php if(
                    $user->can('noteVoucherType-table') ||
                        $user->can('noteVoucherType-add') ||
                        $user->can('noteVoucherType-edit') ||
                        $user->can('noteVoucherType-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('noteVoucherTypes.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.noteVoucherTypes')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php
                    $noteVouchertypes = App\Models\NoteVoucherType::get();
                ?>
                <?php $__currentLoopData = $noteVouchertypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noteVouchertype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('noteVouchers.create', ['id' => $noteVouchertype->id])); ?>"
                            class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e($noteVouchertype->name); ?> </p>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if(
                    $user->can('noteVoucher-table') ||
                        $user->can('noteVoucher-add') ||
                        $user->can('noteVoucher-edit') ||
                        $user->can('noteVoucher-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('noteVouchers.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.noteVouchers')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(
                    $user->can('warehouse-table') ||
                        $user->can('warehouse-add') ||
                        $user->can('warehouse-edit') ||
                        $user->can('warehouse-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('warehouses.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.warehouses')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(
                $user->can('notification-table') ||
                $user->can('notification-add') ||
                $user->can('notification-edit') ||
                $user->can('notification-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('notifications.create')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> <?php echo e(__('messages.notifications')); ?> </p>
                    </a>
                </li>
                <?php endif; ?>


                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            <?php echo e(__('messages.reports')); ?>

                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('inventory_report')); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> <?php echo e(__('messages.inventory_report_with_costs')); ?> </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('order_report')); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> <?php echo e(__('messages.order_report')); ?> </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('product_move')); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> <?php echo e(__('messages.product_move')); ?> </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('tax_report')); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> <?php echo e(__('messages.tax_report')); ?> </p>
                            </a>
                        </li>

                    </ul>
                </li>

                <?php if(
                    $user->can('page-table') ||
                        $user->can('page-add') ||
                        $user->can('page-edit') ||
                        $user->can('page-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('pages.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?php echo e(__('messages.Pages')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>
          
                <?php if(
                    $user->can('question-table') ||
                        $user->can('question-add') ||
                        $user->can('question-edit') ||
                        $user->can('question-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('questions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?php echo e(__('messages.questions')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>

                <?php if(
                    $user->can('banner-table') ||
                        $user->can('banner-add') ||
                        $user->can('banner-edit') ||
                        $user->can('banner-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('banners.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?php echo e(__('messages.banners')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php if(
                    $user->can('receivable-table') ||
                        $user->can('receivable-add') ||
                        $user->can('receivable-edit') ||
                        $user->can('receivable-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('receivables.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?php echo e(__('messages.receivables')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>

                <?php if(
                    $user->can('categorySubscription-table') ||
                        $user->can('categorySubscription-add') ||
                        $user->can('categorySubscription-edit') ||
                        $user->can('categorySubscription-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('categorySubscriptions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?php echo e(__('messages.categorySubscriptions')); ?> </p>
                        </a>
                    </li>
                    <?php endif; ?>

                <li class="nav-item">
                    <a href="<?php echo e(route('admin.setting.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p><?php echo e(__('messages.Settings')); ?> </p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="<?php echo e(route('admin.login.edit',auth()->user()->id)); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p><?php echo e(__('messages.Admin_account')); ?> </p>
                    </a>
                </li>

                <?php if($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') ||
                $user->can('role-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.role.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <span><?php echo e(__('messages.Roles')); ?> </span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(
                $user->can('employee-table') ||
                $user->can('employee-add') ||
                $user->can('employee-edit') ||
                $user->can('employee-delete')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.employee.index')); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <span> <?php echo e(__('messages.Employee')); ?> </span>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/includes/sidebar.blade.php ENDPATH**/ ?>