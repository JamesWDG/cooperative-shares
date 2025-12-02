<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    public $viewFolderPath = 'screens.vendor.gallery.'; 
    protected $uploadPath;

    public function __construct()
    {
        $this->uploadPath = public_path('gallery');

        if (!is_dir($this->uploadPath)) {
            @mkdir($this->uploadPath, 0775, true);
        }
    }

    public function index()
    {
        $vendor = Auth::user();

        $galleries = Gallery::where('vendor_id', $vendor->id)
            ->orderByDesc('id')
            ->get();

        // Yahan JS ke liye clean array bana do
        $galleryData = $galleries->map(function ($g) {
            return [
                'id'         => $g->id,
                'image'      => $g->image,
                'url'        => $g->url,
                'created_at' => optional($g->created_at)->toDateTimeString(),
            ];
        })->values();

        return view($this->viewFolderPath . 'list', [
            'galleries'    => $galleries,
            'galleryData'  => $galleryData,
        ]);
    }


    public function create()
    {
        return view($this->viewFolderPath . 'create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'images'   => 'required|array|min:1',
                'images.*' => 'image|max:5120', // 5MB
            ]);

            if (!is_dir($this->uploadPath) && !@mkdir($this->uploadPath, 0755, true)) {
                throw new \RuntimeException("Cannot create upload dir: {$this->uploadPath}");
            }
            if (!is_writable($this->uploadPath)) {
                throw new \RuntimeException("Upload dir not writable: {$this->uploadPath}");
            }

            $vendor = Auth::user();
            $saved  = [];

            foreach ($request->file('images', []) as $file) {
                $ext  = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                $name = uniqid('g_', true) . '.' . $ext;

                $file->move($this->uploadPath, $name);

                $gallery = Gallery::create([
                    'vendor_id' => $vendor->id,
                    'image'     => $name,
                ]);

                $saved[] = [
                    'id'  => $gallery->id,
                    'url' => $gallery->url,
                ];
            }

            return response()->json([
                'success'      => true,
                'message'      => 'Images uploaded successfully.',
                'items'        => $saved,
                'redirect_url' => route('vendor.gallery'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Vendor gallery upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'vendor_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destory(Gallery $gallery)
    {
        try {
            $vendor = Auth::user();

            // Safety: ensure vendor owns this gallery image
            if ((int) $gallery->vendor_id !== (int) $vendor->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to delete this image.',
                ], 403);
            }

            $path = $this->uploadPath . DIRECTORY_SEPARATOR . $gallery->image;
            if (is_file($path)) {
                @unlink($path);
            }

            $gallery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Vendor gallery delete failed', [
                'error'      => $e->getMessage(),
                'gallery_id' => $gallery->id ?? null,
                'vendor_id'  => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
