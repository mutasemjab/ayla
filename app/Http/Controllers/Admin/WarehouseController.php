<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public function index()
    {

        $data = Warehouse::paginate(PAGINATION_COUNT);

        return view('admin.warehouses.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('warehouse-add')) {
             $shops = Shop::get();
            return view('admin.warehouses.create',compact('shops'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $warehouse = new Warehouse();

            $warehouse->name = $request->get('name');
            $warehouse->shop_id = $request->get('shop');


            if ($warehouse->save()) {

                return redirect()->route('warehouses.index')->with(['success' => 'warehouse created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        if (auth()->user()->can('warehouse-edit')) {
            $data = Warehouse::findorFail($id);
            $shops = Shop::get();
            return view('admin.warehouses.edit', compact('data','shops'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findorFail($id);
        try {

            $warehouse->name = $request->get('name');
            $warehouse->shop_id = $request->get('shop');


            if ($warehouse->save()) {

                return redirect()->route('warehouses.index')->with(['success' => 'warehouse update']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);



            // Delete the category
            if ($warehouse->delete()) {
                return redirect()->back()->with(['success' => 'warehouse deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }
}
