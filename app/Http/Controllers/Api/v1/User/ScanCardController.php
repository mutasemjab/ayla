<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\NoteVoucher;
use App\Models\NoteVoucherType;
use App\Models\Product;
use App\Models\VoucherProduct;
use App\Models\VoucherProductDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ScanCardController extends Controller
{
   public function store(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'note_voucher_type_id' => 'required|integer',
        'date_note_voucher' => 'required|date',
        'fromWarehouse' => 'required|integer',
        'toWarehouse' => 'nullable|integer',
        'note' => 'nullable|string',
        'products' => 'required|array',
        'products.*.name' => 'required|string',
        'products.*.unit' => 'required|integer',
        'products.*.quantity' => 'required|numeric',
        'products.*.purchasing_price' => 'nullable|numeric',
        'products.*.note' => 'nullable|string',
        'details' => 'nullable|array',
        'details.*.*.bin_number' => 'nullable|string',
        'details.*.*.serial_number' => 'nullable|string',
        'details.*.*.expiry_date'=>'nullable'
    ]);

    try {
        // Generate a new number for the note voucher
        $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
        $newNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

        // Create the note voucher
        $noteVoucher = NoteVoucher::create([
            'note_voucher_type_id' => $validatedData['note_voucher_type_id'],
            'date_note_voucher' => $validatedData['date_note_voucher'],
            'number' => $newNumber,
            'from_warehouse_id' => $validatedData['fromWarehouse'],
            'to_warehouse_id' => $validatedData['toWarehouse'] ?? null,
            'note' => $validatedData['note'] ?? null,
        ]);

        // Save the products and update quantities
        foreach ($validatedData['products'] as $productData) {
            $product = Product::where('name_en', $productData['name'])->firstOrFail();

            // Directly create an entry in voucher_products table using a model
            $voucherProduct = new VoucherProduct([
                'note_voucher_id' => $noteVoucher->id,
                'product_id' => $product->id,
                'unit_id' => $productData['unit'],
                'quantity' => $productData['quantity'],
                'purchasing_price' => $productData['purchasing_price'] ?? null,
                'note' => $productData['note']?? null,
            ]);

            $voucherProduct->save();

            // Save product details like bin number, serial number, and expiry date
            if (isset($validatedData['details'][$productData['name']])) {
                foreach ($validatedData['details'][$productData['name']] as $detailData) {
                    VoucherProductDetail::create([
                        'note_voucher_id' => $noteVoucher->id,
                        'voucher_product_id' => $voucherProduct->id,
                        'bin_number' => $detailData['bin_number'] ?? null,
                        'serial_number' => $detailData['serial_number'] ?? null,
                        'expiry_date' => $detailData['expiry_date'] ?? null,
                    ]);
                }
            }
        }

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Note Voucher created successfully!',
            'note_voucher_id' => $noteVoucher->id,
        ], 201);

    } catch (\Exception $e) {
        // Handle any errors that occur during the process
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the Note Voucher.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



}
