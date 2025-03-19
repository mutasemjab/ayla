<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Manager;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $authenticatedUser = auth()->user(); // Get the authenticated user

        $itemlist = Product::with('category', 'productImages', 'unit', 'offers');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $itemlist->where(function ($query) use ($search) {
                $query->where('name_ar', 'like', "%$search%")
                      ->orWhere('name_en', 'LIKE', "%$search%")
                      ->orWhere('number', 'LIKE', "%$search%");
            });
        }

        $itemlist = $itemlist->get();

        foreach ($itemlist as $item) {
            $item->unit_name = $item->unit ? $item->unit->name_ar : null;
            $item->price = $item->selling_price_for_user;
            $item->quantity = $item->available_quantity_for_user;

            // Check if the product is a favorite of the authenticated user
            $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();

            // Check if the product has an offer
            $item->has_offer = $item->offers()->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->first()->price : 0;
        }

        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $itemlist], 200);
    }

    public function productDetails(Request $request, $id)
    {
        $authenticatedUser = auth()->user(); // Get the authenticated user

        $item = Product::with('category', 'productImages','unit', 'offers',)
            ->where('id', $id)
            ->first(); // Use first() to fetch a single product

        if (!$item) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $item->unit_name = $item->unit ? $item->unit->name_ar : null;
        $item->price = $item->selling_price_for_user;
        $item->quantity = $item->available_quantity_for_user;

        // Check if the product is a favorite of the authenticated user
        $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();

        // Check if the product has an offer
        $item->has_offer = $item->offers()->exists();
        $item->offer_id = $item->has_offer ? $item->offers()->first()->id : 0;
        $item->offer_price = $item->has_offer ? $item->offers()->first()->price : 0;

        return response()->json(['data' => $item]);
    }

    public function offers(Request $request)
    {
        $authenticatedUser = auth()->user(); // Get the authenticated user

        $itemList = Product::with('category', 'variations', 'productImages', 'units', 'unit', 'category.countries')
            ->where('status', 1)
            ->whereHas('offers')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($itemList as $item) {
            $item->unit_name = $item->unit ? $item->unit->name_ar : null;
            $item->price = $item->selling_price_for_user;
            $item->quantity = $item->available_quantity_for_user;

            // Check if the product is a favorite of the authenticated user
            $item->is_favourite = $authenticatedUser->favourites()->where('product_id', $item->id)->exists();

            // Check if the product has an offer
            $item->has_offer = $item->offers()->exists();
            $item->offer_id = $item->has_offer ? $item->offers()->first()->id : 0;
            $item->offer_price = $item->has_offer ? $item->offers()->first()->price : 0;
        }

        return response()->json(['data' => $itemList]);
    }
}

