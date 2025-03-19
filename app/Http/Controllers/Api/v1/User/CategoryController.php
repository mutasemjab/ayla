<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{

    public function index(Request $request)
    {
        // Check if the user is authenticated
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $cardPackageId = $user->card_package_id;

        $categories = Category::whereNull('category_id')
            ->with([
                'childCategories.products.productImages',
                'childCategories.products.unit',
                'childCategories.products.offers',
                'childCategories.products.cardPackageProducts',
                'childCategories.products.voucherProducts.voucherProductDetails' // Include the relationship
            ])->get();

        $response = $categories->map(function ($category) use ($cardPackageId) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'photo' => $category->photo,
                'is_game' => $category->is_game,
                'childCategories' => $category->childCategories->map(function ($childCategory) use ($cardPackageId) {
                    return [
                        'id' => $childCategory->id,
                        'name' => $childCategory->name,
                        'photo' => $childCategory->photo,
                        'products' => $childCategory->products->map(function ($product) use ($cardPackageId) {
                            $price = $product->cardPackageProducts->where('card_package_id', $cardPackageId)->first();
                            return [
                                'id' => $product->id,
                                'name' => $product->name,
                                'is_favourite' => auth()->user()->favourites()->where('product_id', $product->id)->exists(),
                                'description' => $product->description,
                                'selling_price' => $price ? $price->selling_price : $product->selling_price_for_user,
                                'voucher_products' => $product->voucherProducts->map(function ($voucherProduct) {
    // Get the first detail with status 2
    $firstDetailWithStatusTwo = $voucherProduct->voucherProductDetails->firstWhere('status', 2);

    // Return null if no valid details are found
    if (!$firstDetailWithStatusTwo) {
        return null;
    }

    // Return the voucher product with valid details
    return [
        'id' => $voucherProduct->id,
        'quantity' => $voucherProduct->quantity,
        'purchasing_price' => $voucherProduct->purchasing_price,
        'details' => [
            'id' => $firstDetailWithStatusTwo->id,
            'bin_number' => $firstDetailWithStatusTwo->bin_number,
            'serial_number' => $firstDetailWithStatusTwo->serial_number,
            'expiry_date' => $firstDetailWithStatusTwo->expiry_date,
        ],
    ];
})->filter()->values(),
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json(['data' => $response]);
    }




    public function getProducts($id, Request $request)
    {
        // Check if the user is authenticated
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $cardPackageId = $user->card_package_id;

        $category = Category::with([
            'childCategories.products.productImages',
            'childCategories.products.unit',
            'childCategories.products.offers',
            'childCategories.products.cardPackageProducts',
            'childCategories.products.voucherProducts.voucherProductDetails', // Include the relationship
        ])->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $response = [
            'category' => [
                'name' => $category->name,
                'photo' => $category->photo,
            ],
            'childCategories' => $category->childCategories->map(function ($childCategory) use ($cardPackageId) {
                return [
                    'category' => [
                        'name' => $childCategory->name,
                        'photo' => $childCategory->photo,
                    ],
                    'products' => $childCategory->products->map(function ($product) use ($cardPackageId) {
                        $price = $product->cardPackageProducts->where('card_package_id', $cardPackageId)->first();
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'is_favourite' => auth()->user()->favourites()->where('product_id', $product->id)->exists(),
                            'selling_price' => $price ? $price->selling_price : $product->selling_price_for_user,
                            'voucher_products' => $product->voucherProducts->map(function ($voucherProduct) {
                                // Get the first detail with status 2
                                $firstDetailWithStatusTwo = $voucherProduct->voucherProductDetails->firstWhere('status', 2);

                                // Return null if no valid details are found
                                if (!$firstDetailWithStatusTwo) {
                                    return null;
                                }

                                // Return the voucher product with valid details
                                return [
                                    'id' => $voucherProduct->id,
                                    'quantity' => $voucherProduct->quantity,
                                    'purchasing_price' => $voucherProduct->purchasing_price,
                                    'details' => [
                                        'id' => $firstDetailWithStatusTwo->id,
                                        'bin_number' => $firstDetailWithStatusTwo->bin_number,
                                        'serial_number' => $firstDetailWithStatusTwo->serial_number,
                                        'expiry_date' => $firstDetailWithStatusTwo->expiry_date,
                                    ],
                                ];
                            })->filter()->values(),
                            // other fields
                        ];
                    }),
                ];
            })
        ];

        return response()->json(['data' => $response]);
    }




}
