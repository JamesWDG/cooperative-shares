<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AdvertisementController extends Controller
{
    // LIST ALL ADS
    public function index()
    {
        $ads = Advertisement::orderBy('id', 'desc')->get();
        return view('screens.admin.advertisements.list', compact('ads'));
    }

    // EDIT FORM
    public function edit($id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('screens.admin.advertisements.edit', compact('ad'));
    }

    // UPDATE AD (AJAX)
    public function update(Request $request, Advertisement $advertisement)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
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

            $advertisement->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Advertisement updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Advertisement update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }
}
