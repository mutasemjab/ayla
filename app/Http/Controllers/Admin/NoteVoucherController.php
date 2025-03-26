<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NoteVoucherExport;
use App\Http\Controllers\Controller;
use App\Imports\NoteVoucherImport;
use App\Models\NoteVoucher;
use App\Models\NoteVoucherType;
use App\Models\Product;
use App\Models\Shop;
use App\Models\VoucherProduct;
use App\Models\VoucherProductDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NoteVoucherController extends Controller
{

    public function importForm()
    {
        if (auth()->user()->can('noteVoucher-add')) {
            $warehouses = Warehouse::all();
            $note_voucher_types = NoteVoucherType::all();
            return view('admin.noteVouchers.import', compact('warehouses', 'note_voucher_types'));
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }
    
    // Process Excel import
    public function importExcel(Request $request)
    {
        if (auth()->user()->can('noteVoucher-add')) {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls,csv',
                'note_voucher_type_id' => 'required|exists:note_voucher_types,id',
                'fromWarehouse' => 'required|exists:warehouses,id',
                'toWarehouse' => 'nullable|exists:warehouses,id',
                'date_note_voucher' => 'required|date',
            ]);

            try {
                // Prepare note voucher data for the import
                $noteVoucherData = [
                    'note_voucher_type_id' => $request->note_voucher_type_id,
                    'date_note_voucher' => $request->date_note_voucher,
                    'fromWarehouse' => $request->fromWarehouse,
                    'toWarehouse' => $request->toWarehouse,
                    'note' => $request->note,
                ];

                // Import the Excel file
                $import = new NoteVoucherImport($noteVoucherData);
                Excel::import($import, $request->file('excel_file'));

                // Get the created note voucher
                $noteVoucher = $import->getNoteVoucher();

                if ($request->input('redirect_to') == 'show') {
                    return redirect()->route('noteVouchers.show', $noteVoucher->id)->with('success', 'Note Voucher imported successfully!');
                } else {
                    return redirect()->route('noteVouchers.index')->with('success', 'Note Voucher imported successfully!');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }

    // Download template
    public function downloadTemplate()
    {
        return Excel::download(new NoteVoucherExport, 'note_voucher_template.xlsx');
    }

    public function index()
    {

        $data = NoteVoucher::paginate(PAGINATION_COUNT);

        return view('admin.noteVouchers.index', ['data' => $data]);
    }

    public function create(Request $request)
    {
        if (auth()->user()->can('noteVoucher-add')) {

            $note_voucher_type_id = $request->query('id');
            $warehouses = Warehouse::get();
            $note_voucher_type = NoteVoucherType::findOrFail($note_voucher_type_id);

            return view('admin.noteVouchers.create',compact('note_voucher_type_id','warehouses','note_voucher_type'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {
        $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
        $newNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

        // Create the note voucher
        $noteVoucher = NoteVoucher::create([
            'note_voucher_type_id' => $request['note_voucher_type_id'],
            'date_note_voucher' => $request['date_note_voucher'],
            'number' => $newNumber,
            'from_warehouse_id' => $request['fromWarehouse'],
            'to_warehouse_id' => $request['toWarehouse'] ?? null,
            'note' => $request['note'],
        ]);

        // Save the products and update quantities
        foreach ($request['products'] as $productIndex => $productData) {
            $product = Product::where('name_ar', $productData['name'])->firstOrFail();

            // Directly create an entry in voucher_products table using a model
            $voucherProduct = new VoucherProduct([
                'note_voucher_id' => $noteVoucher->id,
                'product_id' => $product->id,
                'unit_id' => $productData['unit'],
                'quantity' => $productData['quantity'],
                'purchasing_price' => $productData['purchasing_price'] ?? null,
                'note' => $productData['note'],
            ]);

            $voucherProduct->save(); // Save the voucher product and get the ID

            // Save product details like bin number, serial number, and expiry date using the voucherProduct instance
            if (isset($request['details'][$productData['name']])) {
                foreach ($request['details'][$productData['name']] as $detailData) {
                    VoucherProductDetail::create([
                        'note_voucher_id' => $noteVoucher->id,
                        'voucher_product_id' => $voucherProduct->id, // Now correctly using the voucher_product's ID
                        'bin_number' => $detailData['bin_number'] ?? null,
                        'serial_number' => $detailData['serial_number'] ?? null,
                        'expiry_date' => $detailData['expiry_date'] ?? null,
                    ]);
                }
            }
        }

        if ($request->input('redirect_to') == 'show') {
            return redirect()->route('noteVouchers.show', $noteVoucher->id)->with('success', 'Note Voucher created successfully!');
        } else {
            return redirect()->route('noteVouchers.index')->with('success', 'Note Voucher created successfully!');
        }
    }







    public function show($id)
    {
        $noteVoucher = NoteVoucher::with([
            'fromWarehouse',
            'toWarehouse',
            'voucherProducts',
            'voucherProducts.units',
            'noteVoucherType' // Include the related noteVoucherType
        ])->findOrFail($id);

        return view('admin.noteVouchers.show', compact('noteVoucher'));
    }
    public function edit($id)
    {
        $noteVoucher = NoteVoucher::with([
            'noteVoucherType',
            'voucherProducts',
            'voucherProducts.unit',
            'voucherProducts.voucherProductDetails'
        ])->findOrFail($id);

        $products = Product::all();
        $warehouses = Warehouse::all();

        // Pass the note voucher, products, and warehouses to the view
        return view('admin.noteVouchers.edit', compact('noteVoucher', 'products', 'warehouses'));
    }




    public function update(Request $request, $id)
{
    // Find the existing NoteVoucher
    $noteVoucher = NoteVoucher::findOrFail($id);

    // Update the note voucher fields
    $noteVoucher->update([
        'note_voucher_type_id' => $request['note_voucher_type_id'],
        'date_note_voucher' => $request['date_note_voucher'],
        'from_warehouse_id' => $request['fromWarehouse'],
        'to_warehouse_id' => $request['toWarehouse'] ?? null,
        'note' => $request['note'],
    ]);

    // Remove existing voucher products that are not in the update request
    $existingProductIds = $noteVoucher->voucherProducts->pluck('id')->toArray();
    $requestProductIds = collect($request['products'])->pluck('id')->toArray();

    $productsToDelete = array_diff($existingProductIds, $requestProductIds);
    if (!empty($productsToDelete)) {
        VoucherProduct::whereIn('id', $productsToDelete)->delete();
    }

    // Update or create voucher products and details
    foreach ($request['products'] as $productData) {
        $product = Product::where('name_ar', $productData['name'])->firstOrFail();

        // Check if the voucher product already exists
        $voucherProduct = VoucherProduct::firstOrNew([
            'note_voucher_id' => $noteVoucher->id,
            'product_id' => $product->id
        ]);

        // Update or set new values for voucher product
        $voucherProduct->unit_id = $productData['unit'];
        $voucherProduct->quantity = $productData['quantity'];
        $voucherProduct->purchasing_price = $productData['purchasing_price'] ?? null;
        $voucherProduct->note = $productData['note'];
        $voucherProduct->save();

        // Update or create voucher product details
        if (isset($request['details'][$productData['name']])) {
            // Delete existing details for this product
            VoucherProductDetail::where('voucher_product_id', $voucherProduct->id)->delete();

            foreach ($request['details'][$productData['name']] as $detailData) {
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

    if ($request->input('redirect_to') == 'show') {
        return redirect()->route('noteVouchers.show', $noteVoucher->id)->with('success', 'Note Voucher updated successfully!');
    } else {
        return redirect()->route('noteVouchers.index')->with('success', 'Note Voucher updated successfully!');
    }
}




    public function destroy($id)
    {
        try {
            $noteVoucher = NoteVoucher::findOrFail($id);



            // Delete the category
            if ($noteVoucher->delete()) {
                return redirect()->back()->with(['success' => 'noteVoucher deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }
}
