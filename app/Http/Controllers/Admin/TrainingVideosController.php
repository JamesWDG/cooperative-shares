<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class TrainingVideosController extends Controller
{
    // LIST ALL Training Videos
    public function index()
    {
        $videos = TrainingVideo::orderBy('id', 'desc')->get();
        return view('screens.admin.training-videos.list', compact('videos'));
    }

    // CREATE FORM
    public function create()
    {
        return view('screens.admin.training-videos.create');
    }

    // STORE NEW Training Video
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,avi,mov|max:20480', // Max 20MB
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

            // Video upload path
            $uploadPath = public_path('storage/training-videos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Upload video file
            if ($request->hasFile('video')) {
                $video    = $request->file('video');
                $filename = 'training_video_' . time() . '.' . $video->extension();
                $video->move($uploadPath, $filename);
                $data['video'] = $filename;
            }

            TrainingVideo::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Training Video added successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Training Video store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ]);
        }
    }

    // EDIT FORM
    public function edit($id)
    {
        $video = TrainingVideo::findOrFail($id);
        return view('screens.admin.training-videos.edit', compact('video'));
    }

    // UPDATE Training Video
public function update(Request $request, TrainingVideo $video)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'name'  => 'required|string|max:255',
        'video' => 'nullable|file|mimes:mp4,avi,mov|max:20480', // Max 20MB for video
    ]);

    DB::beginTransaction();

    try {
        // Prepare data for update
        $data = [
            'name' => $validatedData['name'], // Directly using validated name
        ];

        // Handle video file upload if a new file is uploaded
        if ($request->hasFile('video')) {
            // Path where the videos will be stored
            $uploadPath = public_path('storage/training-videos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete the old video if it exists
            if ($video->video) {
                $oldPath = $uploadPath . '/' . $video->video;
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Process the new video
            $videoFile = $request->file('video');
            $filename = 'training_video_' . time() . '.' . $videoFile->extension();
            $videoFile->move($uploadPath, $filename);
            $data['video'] = $filename; // Set the new video filename
        }

        // Update the video record with the new data
        $video->update($data);

        DB::commit();

        return response()->json([
            'status' => true,
            'msg'    => 'Training Video updated successfully!',
        ]);
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Training Video update failed', ['error' => $e->getMessage()]);

        return response()->json([
            'status' => false,
            'msg'    => 'Something went wrong! ' . $e->getMessage(),
        ]);
    }
}


    // DELETE Training Video
    public function destroy(TrainingVideo $video)
    {
        try {
            $uploadPath = public_path('storage/training-videos');

            // Delete video file
            if ($video->video) {
                $path = $uploadPath . '/' . $video->video;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $video->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Training Video Deleted Successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg'    => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }
    }
}
