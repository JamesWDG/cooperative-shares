<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class ServiceController extends Controller
{
    // LIST ALL SERVICES (normal page)
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->get();
        return view('screens.admin.services.list', compact('services'));
    }

    // CREATE FORM (normal page)
    public function create()
    {
        return view('screens.admin.services.create');
    }

    // STORE NEW SERVICE (AJAX)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'short_des'      => 'required|string',
            'long_des'       => 'nullable|string',
            'featured_img'   => 'required|image',
            'background_img' => 'required|image',
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

            // Image upload path: public/storage/services
            $uploadPath = public_path('storage/services');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Upload featured image
            if ($request->hasFile('featured_img')) {
                $image    = $request->file('featured_img');
                $filename = 'service_featured_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);
                $data['featured_img'] = $filename;
            }

            // Upload background image (optional)
            if ($request->hasFile('background_img')) {
                $bgImage    = $request->file('background_img');
                $bgFilename = 'service_bg_' . time() . '.' . $bgImage->extension();
                $bgImage->move($uploadPath, $bgFilename);
                $data['background_img'] = $bgFilename;
            }

            Service::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Service added successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Service store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // EDIT FORM (normal page)
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('screens.admin.services.edit', compact('service'));
    }

    // UPDATE SERVICE (AJAX)
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'short_des'      => 'required|string',
            'long_des'       => 'nullable|string',
            'featured_img'   => 'nullable|image',
            'background_img' => 'nullable|image',
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
            $uploadPath = public_path('storage/services');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Update featured image if new one uploaded
            if ($request->hasFile('featured_img')) {
                if ($service->featured_img) {
                    $oldPath = $uploadPath . '/' . $service->featured_img;
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $image    = $request->file('featured_img');
                $filename = 'service_featured_' . time() . '.' . $image->extension();
                $image->move($uploadPath, $filename);
                $data['featured_img'] = $filename;
            }

            // Update background image if new one uploaded
            if ($request->hasFile('background_img')) {
                if ($service->background_img) {
                    $oldBgPath = $uploadPath . '/' . $service->background_img;
                    if (File::exists($oldBgPath)) {
                        File::delete($oldBgPath);
                    }
                }

                $bgImage    = $request->file('background_img');
                $bgFilename = 'service_bg_' . time() . '.' . $bgImage->extension();
                $bgImage->move($uploadPath, $bgFilename);
                $data['background_img'] = $bgFilename;
            }

            $service->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Service updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Service update failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // DELETE SERVICE
    public function destory(Service $service)
    {
        try {
            $uploadPath = public_path('storage/services');

            // delete images also
            if ($service->featured_img) {
                $path = $uploadPath . '/' . $service->featured_img;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            if ($service->background_img) {
                $bgPath = $uploadPath . '/' . $service->background_img;
                if (File::exists($bgPath)) {
                    File::delete($bgPath);
                }
            }

            $service->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Service Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }
}
