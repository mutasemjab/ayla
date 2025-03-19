<?php $__env->startSection('content'); ?>
    <div class="container">
        <h2><?php echo e(__('messages.Edit')); ?> <?php echo e($noteVoucher->noteVoucherType->in_out_type == 1 ? 'ادخال' : 'اخراج'); ?></h2>
        <form action="<?php echo e(route('noteVouchers.update', $noteVoucher->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input type="hidden" name="redirect_to" id="redirect_to" value="index">

            <button type="submit" class="btn btn-primary" onclick="setRedirect('index')"><?php echo e(__('messages.Submit')); ?></button>
            <button type="submit" class="btn btn-primary" onclick="setRedirect('show')"><?php echo e(__('messages.Save_Print')); ?></button>

            <input type="hidden" name="note_voucher_type_id" value="<?php echo e($noteVoucher->note_voucher_type_id); ?>" class="form-control" required>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_note_voucher"> <?php echo e(__('messages.Date')); ?></label>
                    <input type="date" name="date_note_voucher" class="form-control" value="<?php echo e($noteVoucher->date_note_voucher); ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse"><?php echo e(__('messages.fromWarehouse')); ?></label>
                    <select name="fromWarehouse" class="form-control" required>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($warehouse->id); ?>" <?php echo e($noteVoucher->from_warehouse_id == $warehouse->id ? 'selected' : ''); ?>><?php echo e($warehouse->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Modal for product details -->
            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel"><?php echo e(__('messages.Product Details')); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="detailsInputs"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                            <button type="button" class="btn btn-primary" id="saveDetails"><?php echo e(__('messages.Save')); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="note"><?php echo e(__('messages.Note')); ?></label>
                    <textarea name="note" class="form-control"><?php echo e($noteVoucher->note); ?></textarea>
                </div>
            </div>

            <br>
            <table class="table table-bordered" id="products_table">
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.product')); ?></th>
                        <th><?php echo e(__('messages.unit')); ?></th>
                        <th><?php echo e(__('messages.quantity')); ?></th>
                        <?php if($noteVoucher->noteVoucherType->have_price == 1): ?>
                            <th><?php echo e(__('messages.purchasing_Price')); ?></th>
                        <?php endif; ?>
                        <th><?php echo e(__('messages.Note')); ?></th>
                        <th><?php echo e(__('messages.Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $noteVoucher->voucherProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $voucherProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <input type="text" class="form-control product-search" name="products[<?php echo e($key); ?>][name]" value="<?php echo e($voucherProduct->product->name_ar); ?>" />
                            </td>
                            <td>
                                <select class="form-control product-unit" name="products[<?php echo e($key); ?>][unit]">
                                    <option value="">Select Unit</option>
                                    <?php if($voucherProduct->unit): ?>
                                        <option value="<?php echo e($voucherProduct->unit->id); ?>"
                                                <?php echo e($voucherProduct->unit_id == $voucherProduct->unit->id ? 'selected' : ''); ?>>
                                            <?php echo e($voucherProduct->unit->name_ar); ?>

                                        </option>
                                    <?php endif; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="products[<?php echo e($key); ?>][quantity]" value="<?php echo e($voucherProduct->quantity); ?>" />
                            </td>
                            <?php if($noteVoucher->noteVoucherType->have_price == 1): ?>
                                <td>
                                    <input type="number" class="form-control" name="products[<?php echo e($key); ?>][purchasing_price]" value="<?php echo e($voucherProduct->purchasing_price); ?>" step="any"/>
                                </td>
                            <?php endif; ?>
                            <td>
                                <input type="text" class="form-control" name="products[<?php echo e($key); ?>][note]" value="<?php echo e($voucherProduct->note); ?>" />
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row"><?php echo e(__('messages.Delete')); ?></button>
                                <button type="button" class="btn btn-warning open-popup" data-details="<?php echo e(json_encode($voucherProduct->voucherProductDetails)); ?>"><?php echo e(__('messages.Details')); ?></button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

            </table>

            <button type="button" class="btn btn-primary" id="add_row"><?php echo e(__('messages.Add_Row')); ?></button>

        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script type="text/javascript">
    function setRedirect(value) {
        document.getElementById('redirect_to').value = value;
    }

    $(document).ready(function() {
        let rowIdx = <?php echo e($noteVoucher->voucherProducts->count()); ?>;

        function initializeProductSearch() {
            $('.product-search').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '<?php echo e(route("products.search")); ?>',
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            if (data.length === 0) {
                                response([{ label: 'Not Found', value: '' }]);
                            } else {
                                response($.map(data, function(item) {
                                    return {
                                        label: item.name,
                                        value: item.name,
                                        units: item.units,
                                        unit: item.unit,
                                        id: item.id
                                    };
                                }));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    if (ui.item.value === '') {
                        event.preventDefault();
                    } else {
                        const selectedRow = $(this).closest('tr');
                        const unitDropdown = selectedRow.find('.product-unit');
                        unitDropdown.empty();

                        // Add main unit as the first option
                        if (ui.item.unit) {
                            unitDropdown.append(`<option value="${ui.item.unit.id}">${ui.item.unit.name}</option>`);
                        }

                        // Add other units
                        if (ui.item.units) {
                            $.each(ui.item.units, function(index, unit) {
                                unitDropdown.append(`<option value="${unit.id}">${unit.name}</option>`);
                            });
                        }
                    }
                }
            });
        }

         $('#add_row').on('click', function() {
            $('#products_table tbody').append(`
                <tr>
                    <td><input type="text" class="form-control product-search" name="products[${rowIdx}][name]" /></td>
                    <td>
                        <select class="form-control product-unit" name="products[${rowIdx}][unit]">
                            <option value="">Select Unit</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control" name="products[${rowIdx}][quantity]" /></td>
                    <?php if($noteVoucher->noteVoucherType->have_price == 1): ?>
                    <td><input type="number" class="form-control" name="products[${rowIdx}][purchasing_price]" step="any" /></td>
                    <?php endif; ?>
                    <td><input type="text" class="form-control" name="products[${rowIdx}][note]" /></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row"><?php echo e(__('messages.Delete')); ?></button>
                        <button type="button" class="btn btn-warning open-popup"><?php echo e(__('messages.Details')); ?></button>
                    </td>
                </tr>
            `);
            rowIdx++;
            initializeProductSearch();
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Handle opening the popup with existing details
        $(document).on('click', '.open-popup', function() {
            const selectedRow = $(this).closest('tr');
            const quantity = selectedRow.find('input[name*="[quantity]"]').val();
            const productId = selectedRow.find('input[name*="[name]"]').val();
            const details = $(this).data('details');

            if (!quantity || quantity <= 0) {
                alert('Please enter a valid quantity.');
                return;
            }

            // Generate inputs based on quantity
            let inputsHtml = '';
            for (let i = 0; i < quantity; i++) {
                const detail = details ? details[i] : {};
                inputsHtml += `
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="details[${productId}][${i}][bin_number]" placeholder="Bin Number" value="${detail.bin_number || ''}" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="details[${productId}][${i}][serial_number]" placeholder="Serial Number" value="${detail.serial_number || ''}" />
                        </div>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="details[${productId}][${i}][expiry_date]" placeholder="Expiry Date" value="${detail.expiry_date || ''}" />
                        </div>
                    </div>
                `;
            }

            $('#detailsInputs').html(inputsHtml);
            $('#detailsModal').modal('show');
        });

        // Handle saving details (you may want to store them in the row or process them before form submission)
        $('#saveDetails').on('click', function() {
            $('#detailsModal').modal('hide');
        });


        initializeProductSearch();
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/ayla/resources/views/admin/noteVouchers/edit.blade.php ENDPATH**/ ?>