<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SectionUser;
use Illuminate\Http\Request;


class SectionUserController extends Controller
{

  public function index()
  {

    $data = SectionUser::paginate(PAGINATION_COUNT);

    return view('admin.sectionUsers.index', ['data' => $data]);
  }

  public function create()
  {
    if (auth()->user()->can('sectionUser-add')) {
    return view('admin.sectionUsers.create');
    } else {
        return redirect()->back()
            ->with('error', "Access Denied");
    }
  }



  public function store(Request $request)
  {

    try{
        $sectionUser = new SectionUser();

        $sectionUser->name_en = $request->input('name_en');
        $sectionUser->name_ar = $request->input('name_ar');

        if($sectionUser->save()){

            return redirect()->route('sectionUsers.index')->with(['success' => 'section User created']);

        }else{
            return redirect()->back()->with(['error' => 'Something wrong']);
        }

    }catch(\Exception $ex){
        return redirect()->back()
        ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
        ->withInput();
    }

  }

  public function edit($id)
  {
    if (auth()->user()->can('sectionUser-edit')) {
    $data=SectionUser::findorFail($id);
    return view('admin.sectionUsers.edit',compact('data'));
    } else {
        return redirect()->back()
            ->with('error', "Access Denied");
    }
  }

  public function update(Request $request,$id)
  {
    $sectionUser=SectionUser::findorFail($id);
    try{
       
        $sectionUser->name_en = $request->input('name_en');
        $sectionUser->name_ar = $request->input('name_ar');

        if($sectionUser->save()){
            return redirect()->route('sectionUsers.index')->with(['success' => 'section User update']);

        }else{
            return redirect()->back()->with(['error' => 'Something wrong']);
        }

    }catch(\Exception $ex){
        return redirect()->back()
        ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
        ->withInput();
    }

  }

  public function delete($id)
    {
        try {

            $item_row = SectionUser::select("id")->where('id','=',$id)->first();

            if (!empty($item_row)) {

        $flag = SectionUser::where('id','=',$id)->delete();;

        if ($flag) {
            return redirect()->back()
            ->with(['success' => '   Delete Succefully   ']);
            } else {
            return redirect()->back()
            ->with(['error' => '   Something Wrong']);
            }

            } else {
            return redirect()->back()
            ->with(['error' => '   cant reach fo this data   ']);
            }

       } catch (\Exception $ex) {

            return redirect()->back()
            ->with(['error' => ' Something Wrong   ' . $ex->getMessage()]);
            }
    }


}
