<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class ReviewController extends Controller
{
    // LIST ALL REVIEWS (normal page)
    public function index()
    {
        $reviews = Review::orderBy('id', 'desc')->get();
        return view('screens.admin.reviews.list', compact('reviews'));
    }

    // CREATE FORM (normal page)
    public function create()
    {
        return view('screens.admin.reviews.create');
    }

    // STORE NEW REVIEW (AJAX)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name'   => 'required|string|max:255',
            'client_role'   => 'required|string|max:255',
            'review_text'   => 'required|string',
            'rating'        => 'required|integer|min:1|max:5',
            'client_image'  => 'required|image',
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

            // Image upload path: public/storage/reviews
            $uploadPath = public_path('storage/reviews');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($request->hasFile('client_image')) {
                $image      = $request->file('client_image');
                $filename   = 'review_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);

                // Only store filename in DB
                $data['client_image'] = $filename;
            }

            Review::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Review added successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Review store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // EDIT FORM (normal page)
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('screens.admin.reviews.edit', compact('review'));
    }

    // UPDATE REVIEW (AJAX)
    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'client_name'   => 'required|string|max:255',
            'client_role'   => 'required|string|max:255',
            'review_text'   => 'required|string',
            'rating'        => 'required|integer|min:1|max:5',
            'client_image'  => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->messages()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data       = $validator->validated();
            $uploadPath = public_path('storage/reviews');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($request->hasFile('client_image')) {

                // Delete old image if exists
                if ($review->client_image) {
                    $oldPath = $uploadPath . '/' . $review->client_image;
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                // Upload new image
                $image    = $request->file('client_image');
                $filename = 'review_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);

                $data['client_image'] = $filename;
            }

            $review->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Review updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Review update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // DELETE REVIEW (normal form submit OK)
    public function destory(Review $review)
    {
        try {
            // delete image also (optional but recommended)
            if ($review->client_image) {
                $path = public_path('storage/reviews/' . $review->client_image);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $review->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Review Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }
}
