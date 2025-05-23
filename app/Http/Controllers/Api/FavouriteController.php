<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{


    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $cardPackageId = $user->card_package_id;  // Get the user's card package ID

        // Retrieve favourites with necessary relationships
        $favourites = $user->favourites()->with([
            'category',
            'unit',
            'offers',
            'voucherProducts.voucherProductDetails',
            'cardPackageProducts'  // Include card package products relationship
        ])->get();

        $response = $favourites->groupBy('category.id')->map(function ($favouritesByCategory) use ($user, $cardPackageId) {
            $category = $favouritesByCategory->first()->category; // Get category info
            return [
                'id' => $category->id,
                'name' => $category->name,
                'photo' => $category->photo,
                'is_game' => $category->is_game,
                'childCategories' => [
                    [
                        'id' => $category->id,
                        'name' => $category->name,
                        'photo' => $category->photo,
                        'products' => $favouritesByCategory->map(function ($item) use ($user, $cardPackageId) {
                            $item->is_favourite = $user->favourites()->where('product_id', $item->id)->exists() ? 1 : 2;
                            $item->has_offer = $item->offers()->exists();
                            $item->is_game = $item->category->is_game;
                            $item->offer_id = $item->has_offer ? $item->offers()->first()->id : 0;
                            $item->offer_price = $item->has_offer ? $item->offers()->first()->price : 0;

                            // Check if a card package price is available for the user's card package ID
                            $price = $item->cardPackageProducts->where('card_package_id', $cardPackageId)->first();
                            $sellingPrice = $price ? $price->selling_price_for_user : $item->selling_price_for_user;

                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'is_favourite' => $item->is_favourite,
                                'description' => $item->description,
                                'selling_price' => $sellingPrice,
                                'has_offer' => $item->has_offer,
                                'offer_id' => $item->offer_id,
                                'offer_price' => $item->offer_price,
                                'is_game' => $item->is_game,
                                'unit' => $item->unit,
                                'category_id' => $item->category_id,
                                'created_at' => $item->created_at,
                                'updated_at' => $item->updated_at,
                                'voucher_products' => $item->voucherProducts->map(function ($voucherProduct) {
                                    // Get the first detail with status 2
                                    $firstDetailWithStatusTwo = $voucherProduct->voucherProductDetails->firstWhere('status', 2);

                                    return [
                                        'id' => $voucherProduct->id,
                                        'quantity' => $voucherProduct->quantity,
                                        'purchasing_price' => $voucherProduct->purchasing_price,
                                        'details' => $firstDetailWithStatusTwo ? [
                                            'id' => $firstDetailWithStatusTwo->id,
                                            'bin_number' => $firstDetailWithStatusTwo->bin_number,
                                            'serial_number' => $firstDetailWithStatusTwo->serial_number,
                                            'expiry_date' => $firstDetailWithStatusTwo->expiry_date,
                                        ] : null
                                    ];
                                }),
                            ];
                        }),
                    ]
                ],
            ];
        })->values();

        return response()->json(['data' => $response]);
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'product_id'=>'required|exists:products,id'
        ]);

        $favorite = Favourite::where('user_id',$request->user()->id)
            ->where('product_id',$request->product_id)->first();
        if($favorite){
            if ($favorite->delete()) {
                return response(['message' => 'Changed','is_favorite'=>false], 200);
            }else{
                return response(['errors' => ['Something wrong']], 403);
            }
        }
        $favorite = new Favourite();
        $favorite->user_id = $request->user()->id;
        $favorite->product_id = $request->product_id;
        if ($favorite->save()) {
            return response(['message' => 'Changed','is_favorite'=>true], 200);
        }else{
            return response(['errors' => ['Something wrong']], 403);
        }
    }

}
