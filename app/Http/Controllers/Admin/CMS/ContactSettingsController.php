<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class ContactSettingsController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.contact-settings.';
    protected $pageKey      = 'contact-settings'; // CmsPage.page_key = 'contact-settings'

    /** =======================
     * 🔹 List All Sections
     * ======================= */
    public function contactList()
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            /**
             * Expected JSON structure:
             * {
             *   "contact_cards": { ... },
             *   "hero_text": { "heading": "Reach Out Anytime", ... },
             *   "map_section": { ... }
             * }
             */

            $sections = collect($content)->map(function ($section, $key) {
                // Normalize section to array
                if (is_object($section)) {
                    $section = (array) $section;
                } elseif (!is_array($section)) {
                    $section = [];
                }

                // If section has a "heading" key (like hero_text), use that in list
                $headingFromContent = $section['heading'] ?? null;

                // Fallback to readable label if no heading in JSON
                $label = $headingFromContent ?: $this->getSectionLabel($key);

                return [
                    'key'  => $key,                                    // e.g. "hero_text"
                    'name' => $label,                                  // what you show in <td>
                    'type' => str_replace('_', '-', $key),            // URL param: hero-text
                ];
            })->values();

            // Dynamic page title (priority: DB title, then fallback)
            $pageTitle = $page->title ?? 'Contact Settings';

            // Route name for edit page (uses shared list blade)
            $routeName = 'admin.cms.contact-settings.page';

            // Subtitle (you can also pull from DB later if needed)
            $subtitle  = 'Manage contact page cards, hero text and map embed.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load contact settings sections.');
        }
    }

    /**
     * Map section keys to readable labels (fallback when no heading in JSON)
     */
    protected function getSectionLabel(string $key): string
    {
        return match ($key) {
            'contact_cards' => 'Contact Cards (Phone, Email, Address)',
            'hero_text'     => 'Hero Text (Heading & Paragraphs)',
            'map_section'   => 'Map Section (Google Maps Iframe)',
            default         => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form for a Section
     * ======================= */
    public function showContactPage(string $type)
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            // Convert hyphen → underscore (URL -> JSON key)
            $cleanType = str_replace('-', '_', $type);

            if (!array_key_exists($cleanType, $content)) {
                return back()->with('error', 'Section not found.');
            }

            $sectionData = $content[$cleanType];

            // Title for admin page
            $pageTitle = 'Edit: ' . $this->getSectionLabel($cleanType);

            // Decide which blade to load
            $viewName = match ($cleanType) {
                'contact_cards' => 'contact-cards',
                'hero_text'     => 'hero-text',
                'map_section'   => 'map-section',
                default         => 'contact-generic',
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,   // e.g. 'hero_text'
                'sectionData' => $sectionData, // full JSON for that section
                'pageTitle'   => $pageTitle,
            ]);
        } catch (Exception $e) {
            Log::error("Error showing contact-settings section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =======================
     * 🔹 Update Section (AJAX)
     * ======================= */
    public function updateContactSection(Request $request)
    {
        try {
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // contact_cards | hero_text | map_section
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {
                case 'contact_cards':
                    $data = $request->validate([
                        'phone'   => 'required|string|max:255',
                        'email'   => 'required|email|max:255',
                        'address' => 'required|string|max:500',
                    ]);

                    $content[$key] = [
                        'phone'   => $data['phone'],
                        'email'   => $data['email'],
                        'address' => $data['address'],
                    ];
                    $successMsg = 'Contact cards updated successfully!';
                    break;

                case 'hero_text':
                    $data = $request->validate([
                        'heading'      => 'required|string|max:255',
                        'paragraph_1'  => 'required|string',
                        'paragraph_2'  => 'nullable|string',
                        'hero_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                    ]);

                    $section = [
                        'heading'     => $data['heading'],
                        'paragraph_1' => $data['paragraph_1'],
                        'paragraph_2' => $data['paragraph_2'] ?? '',
                    ];

                    // Handle image upload (optional)
                    if ($request->hasFile('hero_image')) {
                        $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);

                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }

                        // Delete old image if exists
                        $existingImage = $content[$key]['image'] ?? null;
                        if ($existingImage) {
                            $oldPath = $uploadPath . '/' . $existingImage;
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }

                        $image    = $request->file('hero_image');
                        $filename = 'hero_' . $key . '_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        // Save only filename
                        $section['image'] = $filename;
                    } else {
                        // keep old image if no new upload
                        if (isset($content[$key]['image'])) {
                            $section['image'] = $content[$key]['image'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg = 'Hero text updated successfully!';
                    break;

                case 'map_section':
                    $data = $request->validate([
                        'iframe_src' => 'required|string',
                    ]);

                    $content[$key] = [
                        'iframe_src' => $data['iframe_src'],
                    ];
                    $successMsg = 'Map embed updated successfully!';
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
            Log::error("Error updating contact-settings section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update contact settings: ' . $e->getMessage(),
            ], 422);
        }
    }
}
