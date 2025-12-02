<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class BlogController extends Controller
{
    /**
     * Generate a unique slug for admin blogs.
     * If slug exists, auto-append -1, -2, -3, ...
     */
    protected function generateUniqueSlug(string $title, int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug     = $baseSlug;
        $counter  = 1;

        do {
            $query = Blog::where('slug', $slug);

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

    // LIST ALL BLOGS (normal page)
    public function index()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        return view('screens.admin.blogs.list', compact('blogs'));
    }

    // CREATE FORM (normal page)
    public function create()
    {
        return view('screens.admin.blogs.create');
    }

    // STORE NEW BLOG (AJAX)
    public function store(Request $request)
    {
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
            $data = $validator->validated();

            // ✅ Auto-generate unique slug (global)
            $data['slug'] = $this->generateUniqueSlug($data['title']);

            // Image upload path: public/storage/blogs
            $uploadPath = public_path('storage/blogs');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if ($request->hasFile('featured_img')) {
                $image    = $request->file('featured_img');
                $filename = 'blog_featured_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);
                $data['featured_img'] = $filename;
            }

            Blog::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Blog added successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Blog store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // EDIT FORM (normal page)
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('screens.admin.blogs.edit', compact('blog'));
    }

    // UPDATE BLOG (AJAX)
    public function update(Request $request, Blog $blog)
    {
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
            $uploadPath = public_path('storage/blogs');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // ✅ Auto-generate unique slug on update
            // ignore current blog id so same title keeps same slug
            $data['slug'] = $this->generateUniqueSlug(
                $data['title'],
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
                'msg'    => 'Blog updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Blog update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // DELETE BLOG
    public function destory(Blog $blog)
    {
        try {
            $uploadPath = public_path('storage/blogs');

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
                'msg'    => 'Blog Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }
}
