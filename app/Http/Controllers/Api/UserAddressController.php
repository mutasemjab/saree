<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $address = UserAddress::where('user_id', $user_id)->get();
        return $this->successResponse('Address retrieved successfully', [
                'data' => $address
            ]);
    }



    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $this->validate($request, [
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'street' => 'nullable',
            'building_number' => 'nullable',
            'floor' => 'nullable',
            'apartment' => 'nullable',

        ]);

        DB::beginTransaction();
        try {
           
            $address = new UserAddress();
        
            $address->name = $request->name;
            $address->lat = $request->lat;
            $address->lng = $request->lng;
            $address->street = $request->street;
            $address->building_number = $request->building_number;
            $address->floor = $request->floor;
            $address->apartment = $request->apartment;
            $address->user_id = $user_id;
            $address->save();
            DB::commit();
            return response(['message' => 'Address added'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response(['errors' => ['Something wrong']], 403);
        }
    }

    public function show($id)
    {
    }


    public function edit($id)
    {
    }


    public function update(Request $request, $address_id)
    {

        $userAddress = UserAddress::findOrFail($address_id);
        $userAddress->name = $request->name ?? $userAddress->name;
        $userAddress->lat = $request->lat ?? $userAddress->lat;
        $userAddress->lng = $request->lng ?? $userAddress->lng;
        $userAddress->floor = $request->floor ?? $userAddress->floor;
        $userAddress->apartment = $request->apartment ?? $userAddress->apartment;
        $userAddress->street = $request->street ?? $userAddress->street;
        $userAddress->building_number = $request->building_number ?? $userAddress->building_number;

        if ($userAddress->save()) {
            return response(['message' => ['Your address has been changed']]);
        } else {
            return response(['errors' => ['There is something wrong']], 402);
        }
    }


    public function destroy($id)
    {

        $userAddress = UserAddress::find($id);
        if ($userAddress->delete()) {
            return response(['message' => 'Address is deleted'], 200);
        } else {
            return response(['errors' => ['Something wrong']], 403);
        }
    }
}
