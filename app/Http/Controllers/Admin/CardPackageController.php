<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardPackage;
use Illuminate\Http\Request;

class CardPackageController extends Controller
{
    public function index()
    {

        $data = CardPackage::paginate(PAGINATION_COUNT);

        return view('admin.cardPackages.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('cardPackage-add')) {

            return view('admin.cardPackages.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $cardPackage = new CardPackage();

            $cardPackage->name_en = $request->get('name_en');
            $cardPackage->name_ar = $request->get('name_ar');

            if($request->activate){
                $cardPackage->activate = $request->get('activate');
            }
            if ($cardPackage->save()) {

                return redirect()->route('cardPackages.index')->with(['success' => 'Card Package created']);
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
        if (auth()->user()->can('cardPackage-edit')) {
            $data = CardPackage::findorFail($id);
            return view('admin.cardPackages.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $cardPackage = CardPackage::findorFail($id);
        try {

            $cardPackage->name_en = $request->get('name_en');
            $cardPackage->name_ar = $request->get('name_ar');

            if($request->activate){
                $cardPackage->activate = $request->get('activate');
            }
            if ($cardPackage->save()) {

                return redirect()->route('cardPackages.index')->with(['success' => 'Card Package update']);
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
            $cardPackage = CardPackage::findOrFail($id);

            // Delete the category
            if ($cardPackage->delete()) {
                return redirect()->back()->with(['success' => 'cardPackage deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}
