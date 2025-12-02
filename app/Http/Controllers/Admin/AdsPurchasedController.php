<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdsPurchased;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AdsPurchasedController extends Controller
{
    // LIST ALL PURCHASED ADS (Ads Calendar list view)
    public function index()
    {
        $adsPurchased = AdsPurchased::with(['advertisement', 'vendor'])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('screens.admin.ads-purchased.list', compact('adsPurchased'));
    }

    // CREATE FORM
    public function create()
    {
        $advertisements = Advertisement::orderBy('name')->get();
        $vendors        = User::orderBy('first_name')->where('role','!=','admin')->get(); // adjust if you have vendor role

        return view('screens.admin.ads-purchased.create', compact('advertisements', 'vendors'));
    }

    // STORE NEW PURCHASE (AJAX)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'add_id'   => 'required|exists:advertisements,id',
            'user_id'  => 'required|exists:users,id',
            'from_date'=> 'required|date',
            'to_date'  => 'required|date|after_or_equal:from_date',
            'month'    => 'required|integer|min:1|max:12',
            'year'     => 'required|integer|min:2000|max:2100',
            'amount'   => 'required|numeric|min:0',
            'tran_id'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->messages()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $validator->validated();

            AdsPurchased::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Ad purchase saved successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('AdsPurchased store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // EDIT FORM
    public function edit($id)
    {
        $adsPurchase    = AdsPurchased::findOrFail($id);
        $advertisements = Advertisement::orderBy('package_name')->get();
        $vendors        = User::orderBy('first_name')->where('role','!=','admin')->get();

        return view('screens.admin.ads-purchased.edit', compact('adsPurchase', 'advertisements', 'vendors'));
    }

    // UPDATE PURCHASE (AJAX)
    public function update(Request $request, AdsPurchased $adsPurchased)
    {
        $validator = Validator::make($request->all(), [
            'add_id'   => 'required|exists:advertisements,id',
            'user_id'  => 'required|exists:users,id',
            'from_date'=> 'required|date',
            'to_date'  => 'required|date|after_or_equal:from_date',
            'month'    => 'required|integer|min:1|max:12',
            'year'     => 'required|integer|min:2000|max:2100',
            'amount'   => 'required|numeric|min:0',
            'tran_id'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->messages()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $validator->validated();

            $adsPurchased->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Ad purchase updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('AdsPurchased update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // DELETE PURCHASE
    public function destory(AdsPurchased $adsPurchased)
    {
        try {
            $adsPurchased->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Ad purchase deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }
}
