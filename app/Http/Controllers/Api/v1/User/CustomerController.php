<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CardPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    
      public function getCardPackages()
    {
            $data = CardPackage::get();
    
            return response()->json(['data' => $data]);
    }
        
        
      public function search(Request $request)
    {
        $dealerId = auth()->user()->id;
        $query = $request->input('query');

        $customers = User::where('user_type', 1)->where('user_id',$dealerId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->get();

        return response()->json(['data' => $customers]);
    }
    
     public function index()
    {
        $dealerId = auth()->user()->id;
        $data = User::where('user_type',1)->where('user_id',$dealerId)->get();

        return response()->json(['data' => $data]);
    }
    
      public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:3',
            'phone' => 'nullable|string|unique:users,phone',
            'address' => 'nullable|string',
            'card_package_id' => 'nullable|exists:card_packages,id',
            'section_user_id' => 'nullable|exists:section_users,id',
        ]);

        $customer = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'user_type' => 1, // Ensure the user_type is set to 1
            'card_package_id' => $validatedData['card_package_id'],
            'section_user_id' => $validatedData['section_user_id'],
            'user_id' => auth()->user()->id,
            'activate' => 1, // Default to activated
        ]);

        return response()->json(['data' => $customer, 'message' => 'Customer created successfully.'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $customer = User::where('user_type', 1)->findOrFail($id);
        return response()->json(['data' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
     public function update(Request $request, $id)
    {
        $customer = User::where('user_type', 1)->findOrFail($id);
    
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $customer->id,
            'password' => 'sometimes|nullable|string|min:8',
            'phone' => 'sometimes|nullable|string|unique:users,phone,' . $customer->id,
            'address' => 'sometimes|nullable|string',
            'card_package_id' => 'sometimes|nullable|exists:card_packages,id',
            'activate' => 'sometimes|nullable',
        ]);
    
        $updateData = [];
    
        foreach ($validatedData as $key => $value) {
            if ($key === 'password' && $request->filled('password')) {
                $updateData[$key] = Hash::make($value);
            } else {
                $updateData[$key] = $value;
            }
        }
    
        $customer->update($updateData);
    
        return response()->json(['data' => $customer, 'message' => 'Customer updated successfully.']);
    }



}
