<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorySubscription;
use Illuminate\Http\Request;

class CategorySubscriptionController extends Controller
{

    public function index()
    {

        $data = CategorySubscription::paginate(PAGINATION_COUNT);

        return view('admin.categorySubscriptions.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('categorySubscription-add')) {
            return view('admin.categorySubscriptions.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $categorySubscription = new CategorySubscription();

            $categorySubscription->name_en = $request->get('name_en');
            $categorySubscription->name_ar = $request->get('name_ar');

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $categorySubscription->photo = $the_file_path;
            }

            if ($categorySubscription->save()) {

                return redirect()->route('categorySubscriptions.index')->with(['success' => 'category Subscription created']);
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
        if (auth()->user()->can('categorySubscription-edit')) {
            $data = CategorySubscription::findorFail($id);
            return view('admin.categorySubscriptions.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $categorySubscription = CategorySubscription::findorFail($id);
        try {

            $categorySubscription->name_en = $request->get('name_en');
            $categorySubscription->name_ar = $request->get('name_ar');


            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $categorySubscription->photo = $the_file_path;
            }
            if ($categorySubscription->save()) {

                return redirect()->route('categorySubscriptions.index')->with(['success' => 'categorySubscription update']);
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
            $categorySubscription = CategorySubscription::findOrFail($id);


            // Delete the category
            if ($categorySubscription->delete()) {
                return redirect()->back()->with(['success' => 'categorySubscription deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}

