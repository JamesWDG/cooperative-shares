<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class AboutController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.about.';
    protected $pageKey      = 'about'; // CmsPage.page_key = 'about'

    /** =======================
     * 🔹 List All Sections
     * ======================= */
    public function aboutList()
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            /**
             * Expected JSON structure in `cms_pages.content`:
             *
             * {
             *   "about_main": { ... },
             *   "loop_slider": {
             *      "items": [
             *          { "text": "...", "image": "..." },
             *          ...
             *      ]
             *   },
             *   "our_story": { ... },
             *   "explore_properties": { ... }
             * }
             */

            // Ensure we always show these sections in this specific order
            $expectedKeys = [
                'about_main',
                'loop_slider',
                'our_story',
                'explore_properties',
            ];

            $sections = collect($expectedKeys)->map(function ($key) use ($content) {
                $section = $content[$key] ?? [];

                return [
                    'name' => $this->getSectionLabel($key, $section),
                    // URL param (hyphen instead of underscore)
                    'type' => str_replace('_', '-', $key),
                ];
            })->values();

            $pageTitle = $page->title ?? 'About Page Content';
            $routeName = 'admin.cms.about.page';
            $subtitle  = 'Manage About page sections such as main intro, loop slider, story, and explore properties.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load About page sections.');
        }
    }

    /**
     * Map section keys to readable labels.
     */
    protected function getSectionLabel(string $key, $section = null): string
    {
        // Normalize to array
        $sectionArr = [];
        if (is_array($section)) {
            $sectionArr = $section;
        } elseif (is_object($section)) {
            $sectionArr = (array) $section;
        }

        return match ($key) {

            // 1️⃣ About Main
            'about_main' => ($sectionArr['heading_prefix'] ?? null)
                ? trim(($sectionArr['heading_prefix'] ?? '') . ' ' . ($sectionArr['heading_highlight'] ?? ''))
                : 'About Main Section',

            // 2️⃣ Loop Slider
            'loop_slider' => isset($sectionArr['items'][0]['text'])
                ? 'Loop Slider – ' . $sectionArr['items'][0]['text']
                : 'Loop Slider',

            // 3️⃣ Our Story & Why Choose Us
            'our_story' => $sectionArr['our_story_heading'] ?? 'Our Story & Why Choose Us',

            // 4️⃣ Explore Properties
            'explore_properties' => $sectionArr['heading'] ?? 'Explore Co-op Properties',

            default => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form for a Section
     * ======================= */
    public function showAboutPage(string $type)
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            // URL param (hyphen) → JSON key (underscore)
            $cleanType = str_replace('-', '_', $type);

            // If not present yet, initialize empty array so form still loads
            $sectionData = $content[$cleanType] ?? [];

            $pageTitle = 'Edit: ' . $this->getSectionLabel($cleanType, $sectionData);

            $viewName = match ($cleanType) {
                'about_main'         => 'about-main',
                'loop_slider'        => 'loop-slider',
                'our_story'          => 'our-story',
                'explore_properties' => 'explore-properties',
                default              => 'about-generic',
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,
                'sectionData' => $sectionData,
                'pageTitle'   => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error showing about section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =======================
     * 🔹 Update Section (AJAX)
     * ======================= */
    public function updateAboutSection(Request $request)
    {
        try {
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // about_main | loop_slider | our_story | explore_properties
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {

                // =====================================
                // ABOUT MAIN
                // =====================================
                case 'about_main':
                    $data = $request->validate([
                        'heading_prefix'    => 'nullable|string|max:255',
                        'heading_highlight' => 'nullable|string|max:255',
                        'tagline'           => 'required|string',
                        'paragraph_1'       => 'nullable|string',
                        'paragraph_2'       => 'nullable|string',
                        'paragraph_3'       => 'nullable|string',

                        // Box 1
                        'box_1_title'       => 'required|string|max:255',
                        'box_1_description' => 'nullable|string',
                        'box_1_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        // Box 2
                        'box_2_title'       => 'required|string|max:255',
                        'box_2_description' => 'nullable|string',
                        'box_2_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        // Main section images
                        'main_image_1'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                        'main_image_2'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                        'main_image_3'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                    ]);

                    // Base text data
                    $section = [
                        'heading_prefix'    => $data['heading_prefix'] ?? '',
                        'heading_highlight' => $data['heading_highlight'] ?? '',
                        'tagline'           => $data['tagline'],
                        'paragraph_1'       => $data['paragraph_1'] ?? '',
                        'paragraph_2'       => $data['paragraph_2'] ?? '',
                        'paragraph_3'       => $data['paragraph_3'] ?? '',
                        'box_1'             => [
                            'title'       => $data['box_1_title'],
                            'description' => $data['box_1_description'] ?? '',
                        ],
                        'box_2'             => [
                            'title'       => $data['box_2_title'],
                            'description' => $data['box_2_description'] ?? '',
                        ],
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Main images (image_1, image_2, image_3)
                    for ($i = 1; $i <= 3; $i++) {
                        $fieldName = 'main_image_' . $i;
                        $jsonKey   = 'image_' . $i;

                        if ($request->hasFile($fieldName)) {
                            $existingImage = $content[$key][$jsonKey] ?? null;
                            if ($existingImage) {
                                $oldPath = $uploadPath . '/' . $existingImage;
                                if (file_exists($oldPath)) {
                                    @unlink($oldPath);
                                }
                            }

                            $image    = $request->file($fieldName);
                            $filename = 'about_' . $key . '_' . $i . '_' . time() . '.' . $image->extension();
                            $image->move($uploadPath, $filename);

                            $section[$jsonKey] = $filename;
                        } else {
                            if (isset($content[$key][$jsonKey])) {
                                $section[$jsonKey] = $content[$key][$jsonKey];
                            }
                        }
                    }

                    // Box logos (box_1.logo and box_2.logo)
                    foreach ([1, 2] as $boxIndex) {
                        $fieldName = 'box_' . $boxIndex . '_logo';
                        $jsonKey   = 'box_' . $boxIndex;

                        // Keep existing box data from $section
                        $boxData = $section[$jsonKey] ?? [];

                        if ($request->hasFile($fieldName)) {
                            $existingLogo = $content[$key][$jsonKey]['logo'] ?? null;
                            if ($existingLogo) {
                                $oldPath = $uploadPath . '/' . $existingLogo;
                                if (file_exists($oldPath)) {
                                    @unlink($oldPath);
                                }
                            }

                            $image    = $request->file($fieldName);
                            $filename = 'about_box_' . $boxIndex . '_' . time() . '.' . $image->extension();
                            $image->move($uploadPath, $filename);

                            $boxData['logo'] = $filename;
                        } else {
                            if (isset($content[$key][$jsonKey]['logo'])) {
                                $boxData['logo'] = $content[$key][$jsonKey]['logo'];
                            }
                        }

                        $section[$jsonKey] = $boxData;
                    }

                    $content[$key] = $section;
                    $successMsg    = 'About main section updated successfully!';
                    break;

                // =====================================
                // OUR STORY / WHY CHOOSE US
                // =====================================
                case 'our_story':
                    $data = $request->validate([
                        'our_story_heading'      => 'required|string|max:255',
                        'our_story_paragraph_1'  => 'required|string',
                        'our_story_paragraph_2'  => 'nullable|string',
                        'why_choose_heading'     => 'required|string|max:255',
                        'why_choose_items'       => 'required|array|min:1',
                        'why_choose_items.*.title'       => 'required|string|max:255',
                        'why_choose_items.*.description' => 'required|string',

                        // New image fields
                        'story_image_left'  => 'nullable|image',
                        'story_image_right' => 'nullable|image',
                    ]);

                    $items = array_values($data['why_choose_items']);

                    // Base text content
                    $section = [
                        'our_story_heading'     => $data['our_story_heading'],
                        'our_story_paragraph_1' => $data['our_story_paragraph_1'],
                        'our_story_paragraph_2' => $data['our_story_paragraph_2'] ?? '',
                        'why_choose_heading'    => $data['why_choose_heading'],
                        'why_choose_items'      => $items,
                    ];

                    // Handle image uploads (left & right)
                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Left image
                    if ($request->hasFile('story_image_left')) {
                        $existingImage = $content[$key]['image_left'] ?? null;
                        if ($existingImage) {
                            $oldPath = $uploadPath . '/' . $existingImage;
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }

                        $image    = $request->file('story_image_left');
                        $filename = 'about_' . $key . '_left_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['image_left'] = $filename;
                    } else {
                        if (isset($content[$key]['image_left'])) {
                            $section['image_left'] = $content[$key]['image_left'];
                        }
                    }

                    // Right image
                    if ($request->hasFile('story_image_right')) {
                        $existingImage = $content[$key]['image_right'] ?? null;
                        if ($existingImage) {
                            $oldPath = $uploadPath . '/' . $existingImage;
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }

                        $image    = $request->file('story_image_right');
                        $filename = 'about_' . $key . '_right_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['image_right'] = $filename;
                    } else {
                        if (isset($content[$key]['image_right'])) {
                            $section['image_right'] = $content[$key]['image_right'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Our Story / Why Choose Us section updated successfully!';
                    break;

                // =====================================
                // LOOP SLIDER
                // =====================================
                case 'loop_slider':
                    $data = $request->validate([
                        'items'              => 'required|array|min:1',
                        'items.*.text'       => 'required|string|max:255',
                        'items.*.image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                    ]);

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $newItems = [];

                    foreach ($data['items'] as $i => $item) {
                        $slide = [
                            'text' => $item['text'],
                        ];

                        // old image if exists
                        $oldImage = $content[$key]['items'][$i]['image'] ?? null;

                        if ($request->hasFile("items.$i.image")) {
                            // delete old
                            if ($oldImage && file_exists("$uploadPath/$oldImage")) {
                                @unlink("$uploadPath/$oldImage");
                            }

                            $imageFile = $request->file("items.$i.image");
                            $filename  = 'loop_slider_' . time() . '_' . $i . '.' . $imageFile->extension();
                            $imageFile->move($uploadPath, $filename);

                            $slide['image'] = $filename;
                        } else {
                            if ($oldImage) {
                                $slide['image'] = $oldImage;
                            }
                        }

                        $newItems[] = $slide;
                    }

                    $content[$key] = [
                        'items' => $newItems,
                    ];

                    $successMsg = 'Loop slider updated successfully!';
                    break;

                // =====================================
                // EXPLORE PROPERTIES
                // =====================================
                case 'explore_properties':
                    $data = $request->validate([
                        'heading'     => 'required|string|max:255',
                        'paragraph_1' => 'required|string',
                        'paragraph_2' => 'required|string',

                        // media fields (only filenames stored)
                        'video_file'  => 'nullable|file',
                        'image_file'  => 'nullable|image',
                    ]);

                    // Base text content
                    $section = [
                        'heading'     => $data['heading'],
                        'paragraph_1' => $data['paragraph_1'],
                        'paragraph_2' => $data['paragraph_2'],
                    ];

                    // Upload directory: storage/cms/{page_key}/{section_key}
                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Handle video upload
                    if ($request->hasFile('video_file')) {
                        $existingVideo = $content[$key]['video_file'] ?? null;
                        if ($existingVideo) {
                            $oldPath = $uploadPath . '/' . $existingVideo;
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }

                        $video    = $request->file('video_file');
                        $filename = 'explore_video_' . time() . '.' . $video->extension();
                        $video->move($uploadPath, $filename);

                        $section['video_file'] = $filename;
                    } else {
                        if (isset($content[$key]['video_file'])) {
                            $section['video_file'] = $content[$key]['video_file'];
                        }
                    }

                    // Handle image upload
                    if ($request->hasFile('image_file')) {
                        $existingImage = $content[$key]['image_file'] ?? null;
                        if ($existingImage) {
                            $oldPath = $uploadPath . '/' . $existingImage;
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }

                        $image    = $request->file('image_file');
                        $filename = 'explore_image_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['image_file'] = $filename;
                    } else {
                        if (isset($content[$key]['image_file'])) {
                            $section['image_file'] = $content[$key]['image_file'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Explore Co-op Properties section updated successfully!';
                    break;

                default:
                    return response()->json([
                        'status' => false,
                        'msg'    => 'Unknown section type.',
                    ], 422);
            }

            $page->update(['content' => $content]);

            return response()->json([
                'status' => true,
                'msg'    => $successMsg,
            ]);

        } catch (Exception $e) {
            Log::error("Error updating about section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update About page section: ' . $e->getMessage(),
            ], 422);
        }
    }
}
