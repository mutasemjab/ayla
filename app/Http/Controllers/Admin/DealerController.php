<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\Wallet;

class DealerController extends Controller
{

    public function index(Request $request)
    {
        if ($request->search) {
            $data = User::where('user_type',2)->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`, `email`, `phone`)'), 'like', '%' . $request->search . '%');
            })->paginate(PAGINATION_COUNT);
        } else {
            $data = User::where('user_type',2)->paginate(PAGINATION_COUNT);
        }

        $searchQuery = $request->search;

        return view('admin.dealers.index', compact('data', 'searchQuery'));
    }

    public function create()
    {
     if (auth()->user()->can('dealer-add')) {
        return view('admin.dealers.create');
     }else{
        return redirect()->back()
        ->with('error', "Access Denied");
     }
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->search), 'users.xlsx');
    }

    public function store(Request $request)
    {
      try{
          $dealer = new User();
          $dealer->name = $request->get('name');
          $dealer->email = $request->get('email');
          $dealer->phone = $request->get('phone');
          $dealer->address = $request->get('address');
           $dealer->user_type = 2;
           if($request->activate){
              $dealer->activate = $request->get('activate');
          }
          $dealer->password = Hash::make($request->password);

          if ($request->has('photo')) {
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
            $dealer->photo = $the_file_path;
         }
          if($dealer->save()){
            $wallet = new Wallet();
            $wallet->total = 0; // Set initial total to 0
            $wallet->user_id = $dealer->id;
            $wallet->save();
           return redirect()->route('admin.dealers.index')->with(['success' => 'Dealer created']);

          }else{
              return redirect()->back()->with(['error' => 'Something wrong']);
          }

      }catch(\Exception $ex){
          return redirect()->back()
          ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
          ->withInput();
      }

    }


    public function show($id)
    {
        $data = User::where('user_type',2)->findOrFail($id);
        return view('admin.dealers.show',compact('data',));
    }


    public function edit($id)
    {
        if (auth()->user()->can('dealer-edit')) {
            $data = User::where('user_type',2)->findorFail($id);
            return view('admin.dealers.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

       public function update(Request $request,$id)
       {
         $dealer=User::where('user_type',2)->findorFail($id);
         try{

             $dealer->name = $request->get('name');
             if($request->password){
                $dealer->password = Hash::make($request->password);
             }
             $dealer->email = $request->get('email');
             $dealer->phone = $request->get('phone');
             $dealer->address = $request->get('address');
             if($request->activate){
                $dealer->activate = $request->get('activate');
            }
            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $dealer->photo = $the_file_path;
             }
             if($dealer->save()){
                 return redirect()->route('admin.dealers.index')->with(['success' => 'Dealers update']);

             }else{
                 return redirect()->back()->with(['error' => 'Something wrong']);
             }

         }catch(\Exception $ex){
             return redirect()->back()
             ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
             ->withInput();
         }

      }

     //  public function delete($id)
    //     {
    //         try {

    //             $item_row = User::select("name")->where('id','=',$id)->first();

    //             if (!empty($item_row)) {

    //         $flag = User::where('id','=',$id)->delete();;

    //         if ($flag) {
    //             return redirect()->back()
    //             ->with(['success' => '   Delete Succefully   ']);
    //             } else {
    //             return redirect()->back()
    //             ->with(['error' => '   Something Wrong']);
    //             }

    //             } else {
    //             return redirect()->back()
    //             ->with(['error' => '   cant reach fo this data   ']);
    //             }

    //       } catch (\Exception $ex) {

    //             return redirect()->back()
    //             ->with(['error' => ' Something Wrong   ' . $ex->getMessage()]);
    //             }
    //     }


    //   public function ajax_search(Request $request)
    //   {
    //       if ($request->ajax()) {


    //       $search_by_text = $request->search_by_text;
    //       $searchbyradio = $request->searchbyradio;

    //       if ($search_by_text != '') {
    //       if ($searchbyradio == 'employeeApp_code') {
    //       $field1 = "employeeApp_code";
    //       $operator1 = "=";
    //       $value1 = $search_by_text;
    //       } elseif ($searchbyradio == 'account_number') {
    //       $field1 = "account_number";
    //       $operator1 = "=";
    //       $value1 = $search_by_text;
    //       } else {
    //       $field1 = "name";
    //       $operator1 = "like";
    //       $value1 = "%{$search_by_text}%";
    //       }
    //       } else {
    //       //true
    //       $field1 = "id";
    //       $operator1 = ">";
    //       $value1 = 0;
    //       }


    //       $data = User::where($field1, $operator1, $value1)->orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);

    //       return view('admin.dealers.ajax_search', ['data' => $data]);
    //       }
    //       }



}
