<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class FaqsController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.faqs.';
    protected $pageKey      = 'faqs'; // CmsPage.page_key = 'faqs'

    /** =======================
     * 🔹 List All Sections (only: faqs)
     * ======================= */
    public function faqsList()
    {
        try {
            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            /**
             * Expected JSON structure in `cms_pages.content`:
             *
             * {
             *   "faqs": {
             *     "main_title": "Frequently Asked Questions",
             *     "items": [
             *       { "title": "Question 1", "description": "Answer 1" },
             *       { "title": "Question 2", "description": "Answer 2" }
             *     ]
             *   }
             * }
             */

            $sections = collect($page->content ?? [])->map(
                fn ($section, $key) => [
                    // Section label from JSON if available
                    'name' => $this->getSectionLabel($key, $section),
                    // For route param we use hyphens instead of underscores
                    'type' => str_replace('_', '-', $key),
                ]
            )->values();

            // Dynamic page title (priority: DB title, then fallback)
            $pageTitle = $page->title ?? 'FAQs';

            // Route name for edit page (shared list blade)
            $routeName = 'admin.cms.faqs.page';

            $subtitle  = 'Manage FAQs content.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load FAQs sections.');
        }
    }

    /**
     * Small helper to map section keys to readable labels,
     * and if possible use heading from DB content.
     */
    protected function getSectionLabel(string $key, $section = null): string
    {
        // Normalize section to array
        $sectionArr = [];
        if (is_array($section)) {
            $sectionArr = $section;
        } elseif (is_object($section)) {
            $sectionArr = (array) $section;
        }

        return match ($key) {
            'faqs' => $sectionArr['main_title']
                ?? 'FAQs',

            default => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form for FAQs Section
     * URL param: /faqs/faqs  (type = "faqs")
     * ======================= */
    public function showFaqsPage(string $type)
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

            // Title for admin edit page
            $pageTitle = 'Edit: ' . $this->getSectionLabel($cleanType, $sectionData);

            // Only one view for now, but kept match for future flexibility
            $viewName = match ($cleanType) {
                'faqs'  => 'faqs',        // resources/views/screens/admin/cms/faqs/faqs.blade.php (or edit.blade if you prefer)
                default => 'faqs-generic' // optional fallback
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,   // 'faqs'
                'sectionData' => $sectionData, // array
                'pageTitle'   => $pageTitle,
            ]);
        } catch (Exception $e) {
            Log::error("Error showing faqs section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load FAQs section.');
        }
    }

    /** =======================
     * 🔹 Update FAQs Section (AJAX)
     * POST: /faqs/update
     * ======================= */
    public function updateFaqsSection(Request $request)
    {
        try {
            // Base validation: from hidden fields in form
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // expecting "faqs"
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {
                case 'faqs':
                    // Form fields: main_title + items[*][title], items[*][description]
                    $data = $request->validate([
                        'main_title'           => 'required|string|max:255',
                        'items'                => 'required|array|min:1',
                        'items.*.title'        => 'required|string|max:255',
                        'items.*.description'  => 'required|string',
                    ]);

                    // Re-index to a clean numeric array
                    $items = array_values($data['items']);

                    $content[$key] = [
                        'main_title' => $data['main_title'],
                        'items'      => $items,
                    ];

                    $successMsg = 'FAQs updated successfully!';
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
            Log::error("Error updating faqs section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update FAQs: ' . $e->getMessage(),
            ], 422);
        }
    }
}
