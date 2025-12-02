<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorBlog;
use App\Models\VendorSubscription; // ⬅️ already there
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class VendorBlogController extends Controller
{
    /**
     * Check if current vendor's active plan allows Co-Op
     * - If not, redirect (normal page)
     * - Or return JSON error (AJAX)
     */
    protected function checkCoopAccess(bool $forAjax = false)
    {
        $vendor = Auth::user();

        $subscription = VendorSubscription::active()   // uses your existing scope
            ->where('vendor_id', $vendor->id)
            ->with('plan')
            ->first();

        // No active sub OR no plan OR plan doesn't allow Co-Op
        if (!$subscription || !$subscription->plan || !$subscription->plan->allow_coop) {

            $message = 'Your current subscription does not include Co-Op. Please upgrade to the Premium plan to use Co-Op.';

            if ($forAjax) {
                return response()->json([
                    'status'       => false,
                    'msg'          => $message,
                    'redirect_url' => route('vendor.subscription.plans'),
                ], 403);
            }

            return redirect()
                ->route('vendor.subscription.plans')
                ->with('error', $message);
        }

        // allowed
        return null;
    }

    /**
     * Generate a unique slug per vendor.
     * If slug exists, auto-append -1, -2, -3, ...
     */
    protected function generateUniqueSlug(string $title, int $vendorId, int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug     = $baseSlug;
        $counter  = 1;

        do {
            $query = VendorBlog::where('vendor_id', $vendorId)
                ->where('slug', $slug);

            if (!is_null($ignoreId)) {
                $query->where('id', '!=', $ignoreId);
            }

            $exists = $query->exists();

            if ($exists) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
        } while ($exists);

        return $slug;
    }

    // LIST ALL CO-OP ITEMS (normal page)
    public function index()
    {
        if ($resp = $this->checkCoopAccess(false)) {
            return $resp;
        }

        $vendor = Auth::user();
        $blogs = VendorBlog::where('vendor_id', $vendor->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('screens.vendor.blogs.list', compact('blogs'));
    }

    // CREATE FORM (normal page)
    public function create()
    {
        if ($resp = $this->checkCoopAccess(false)) {
            return $resp;
        }

        return view('screens.vendor.blogs.create');
    }

    // STORE NEW CO-OP (AJAX)
    public function store(Request $request)
    {
        if ($resp = $this->checkCoopAccess(true)) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'title'           => 'required|string|max:255',
            'short_des'       => 'required|string',
            'long_des'        => 'nullable|string',
            'read_in_minutes' => 'nullable|integer|min:1',
            'featured_img'    => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->messages()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data   = $validator->validated();
            $vendor = Auth::user();

            // ✅ Unique slug per vendor (auto-append -1, -2, ...)
            $data['slug'] = $this->generateUniqueSlug($data['title'], $vendor->id);

            // Image upload path: public/storage/vendor-blogs
            $uploadPath = public_path('storage/vendor-blogs');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($request->hasFile('featured_img')) {
                $image    = $request->file('featured_img');
                $filename = 'blog_featured_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);
                $data['featured_img'] = $filename;
            }

            $data['vendor_id'] = $vendor->id;

            VendorBlog::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Co-Op added successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Co-Op store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // EDIT FORM (normal page)
    public function edit($id)
    {
        if ($resp = $this->checkCoopAccess(false)) {
            return $resp;
        }

        $blog = VendorBlog::findOrFail($id);
        return view('screens.vendor.blogs.edit', compact('blog'));
    }

    // UPDATE CO-OP (AJAX)
    public function update(Request $request, VendorBlog $blog)
    {
        if ($resp = $this->checkCoopAccess(true)) {
            return $resp;
        }

        $validator = Validator::make($request->all(), [
            'title'           => 'required|string|max:255',
            'short_des'       => 'required|string',
            'long_des'        => 'nullable|string',
            'read_in_minutes' => 'nullable|integer|min:1',
            'featured_img'    => 'nullable|image',
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
            $uploadPath = public_path('storage/vendor-blogs');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // ✅ Unique slug per vendor on update too
            // (ignore current blog id so same title keeps same slug)
            $data['slug'] = $this->generateUniqueSlug(
                $data['title'],
                $blog->vendor_id,
                $blog->id
            );

            // Update featured image if new one uploaded
            if ($request->hasFile('featured_img')) {
                if ($blog->featured_img) {
                    $oldPath = $uploadPath . '/' . $blog->featured_img;
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $image    = $request->file('featured_img');
                $filename = 'blog_featured_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);
                $data['featured_img'] = $filename;
            }

            $blog->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Co-Op updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Co-Op update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // DELETE CO-OP
    public function destory(VendorBlog $blog)
    {
        if ($resp = $this->checkCoopAccess(true)) {
            return $resp;
        }

        try {
            $uploadPath = public_path('storage/vendor-blogs');

            // delete image also
            if ($blog->featured_img) {
                $path = $uploadPath . '/' . $blog->featured_img;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $blog->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Co-Op Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }
}
