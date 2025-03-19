@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>{{ __('messages.Edit') }} {{$noteVoucher->noteVoucherType->in_out_type == 1 ? 'ادخال' : 'اخراج' }}</h2>
        <form action="{{ route('noteVouchers.update', $noteVoucher->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="redirect_to" id="redirect_to" value="index">

            <button type="submit" class="btn btn-primary" onclick="setRedirect('index')">{{ __('messages.Submit') }}</button>
            <button type="submit" class="btn btn-primary" onclick="setRedirect('show')">{{ __('messages.Save_Print') }}</button>

            <input type="hidden" name="note_voucher_type_id" value="{{ $noteVoucher->note_voucher_type_id }}" class="form-control" required>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_note_voucher"> {{ __('messages.Date') }}</label>
                    <input type="date" name="date_note_voucher" class="form-control" value="{{ $noteVoucher->date_note_voucher }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mt-3">
                    <label for="warehouse">{{ __('messages.fromWarehouse') }}</label>
                    <select name="fromWarehouse" class="form-control" required>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $noteVoucher->from_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Modal for product details -->
            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel">{{ __('messages.Product Details') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="detailsInputs"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                            <button type="button" class="btn btn-primary" id="saveDetails">{{ __('messages.Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="note">{{ __('messages.Note') }}</label>
                    <textarea name="note" class="form-control">{{ $noteVoucher->note }}</textarea>
                </div>
            </div>

            <br>
            <table class="table table-bordered" id="products_table">
                <thead>
                    <tr>
                        <th>{{ __('messages.product') }}</th>
                        <th>{{ __('messages.unit') }}</th>
                        <th>{{ __('messages.quantity') }}</th>
                        @if($noteVoucher->noteVoucherType->have_price == 1)
                            <th>{{ __('messages.purchasing_Price') }}</th>
                        @endif
                        <th>{{ __('messages.Note') }}</th>
                        <th>{{ __('messages.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($noteVoucher->voucherProducts as $key => $voucherProduct)
                        <tr>
                            <td>
                                <input type="text" class="form-control product-search" name="products[{{ $key }}][name]" value="{{ $voucherProduct->product->name_ar }}" />
                            </td>
                            <td>
                                <select class="form-control product-unit" name="products[{{ $key }}][unit]">
                                    <option value="">Select Unit</option>
                                    @if ($voucherProduct->unit)
                                        <option value="{{ $voucherProduct->unit->id }}"
                                                {{ $voucherProduct->unit_id == $voucherProduct->unit->id ? 'selected' : '' }}>
                                            {{ $voucherProduct->unit->name_ar }}
                                        </option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="products[{{ $key }}][quantity]" value="{{ $voucherProduct->quantity }}" />
                            </td>
                            @if($noteVoucher->noteVoucherType->have_price == 1)
                                <td>
                                    <input type="number" class="form-control" name="products[{{ $key }}][purchasing_price]" value="{{ $voucherProduct->purchasing_price }}" step="any"/>
                                </td>
                            @endif
                            <td>
                                <input type="text" class="form-control" name="products[{{ $key }}][note]" value="{{ $voucherProduct->note }}" />
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button>
                                <button type="button" class="btn btn-warning open-popup" data-details="{{ json_encode($voucherProduct->voucherProductDetails) }}">{{ __('messages.Details') }}</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <button type="button" class="btn btn-primary" id="add_row">{{ __('messages.Add_Row') }}</button>

        </form>
    </div>
@endsection

@section('js')

<script type="text/javascript">
    function setRedirect(value) {
        document.getElementById('redirect_to').value = value;
    }

    $(document).ready(function() {
        let rowIdx = {{ $noteVoucher->voucherProducts->count() }};

        function initializeProductSearch() {
            $('.product-search').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '{{ route("products.search") }}',
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
                    @if($noteVoucher->noteVoucherType->have_price == 1)
                    <td><input type="number" class="form-control" name="products[${rowIdx}][purchasing_price]" step="any" /></td>
                    @endif
                    <td><input type="text" class="form-control" name="products[${rowIdx}][note]" /></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button>
                        <button type="button" class="btn btn-warning open-popup">{{ __('messages.Details') }}</button>
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

@endsection
