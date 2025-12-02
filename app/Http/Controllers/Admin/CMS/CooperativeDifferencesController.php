<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class CooperativeDifferencesController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.cooperative-differences.';
    protected $pageKey      = 'cooperative-differences'; // CmsPage.page_key = 'cooperative-differences'

    /** =======================
     * 🔹 List All Sections
     * ======================= */
    public function differencesList()
    {
        try {
            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            /**
             * Expected JSON structure in `cms_pages.content`:
             *
             * {
             *   "intro": {
             *     "mini_heading": "Cooperative Differences",
             *     "main_heading": "What Makes Co-op Housing Unique",
             *     "paragraph": "..."
             *   },
             *   "key_differences": {
             *     "main_title": "Key Differences",
             *     "closing_text": "Understanding these differences helps ...",
             *     "items": [
             *       { "title": "Ownership Structure", "description": "..." },
             *       { "title": "Community Governance", "description": "..." }
             *     ]
             *   }
             * }
             */

            $sections = collect($page->content ?? [])->map(
                fn ($section, $key) => [
                    // Section label: try reading heading from JSON, else fallback
                    'name' => $this->getSectionLabel($key, $section),
                    // For route param we use hyphens instead of underscores
                    'type' => str_replace('_', '-', $key),
                ]
            )->values();

            // Dynamic page title (priority: DB title, then fallback)
            $pageTitle = $page->title ?? 'Cooperative Differences';

            // Route name for edit page (shared list blade)
            $routeName = 'admin.cms.cooperative-differences.page';

            $subtitle  = 'Manage cooperative differences intro and key differences content.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load cooperative differences sections.');
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
            'intro' => $sectionArr['main_heading']   // Prefer main heading
                ?? $sectionArr['mini_heading']       // Or mini heading
                ?? 'Intro Section',

            'key_differences' => $sectionArr['main_title']
                ?? 'Key Differences',

            default => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form for a Section
     * ======================= */
    public function showDifferencesPage(string $type)
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
            $pageTitle = 'Edit: ' . $this->getSectionLabel($cleanType, $sectionData);

            // Decide which blade to load
            $viewName = match ($cleanType) {
                'intro'           => 'intro',
                'key_differences' => 'key-differences',
                default           => 'differences-generic', // fallback if ever needed
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,   // e.g. 'intro'
                'sectionData' => $sectionData, // array
                'pageTitle'   => $pageTitle,
            ]);
        } catch (Exception $e) {
            Log::error("Error showing cooperative-differences section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =======================
     * 🔹 Update Section (AJAX)
     * ======================= */
    public function updateDifferencesSection(Request $request)
    {
        try {
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // intro | key_differences
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {
                case 'intro':
                    $data = $request->validate([
                        'mini_heading' => 'nullable|string|max:255',
                        'main_heading' => 'required|string|max:255',
                        'paragraph'    => 'required|string',
                    ]);

                    $content[$key] = [
                        'mini_heading' => $data['mini_heading'] ?? '',
                        'main_heading' => $data['main_heading'],
                        'paragraph'    => $data['paragraph'],
                    ];
                    $successMsg = 'Intro section updated successfully!';
                    break;

                case 'key_differences':
                    $data = $request->validate([
                        'main_title'   => 'required|string|max:255',     // e.g. "Key Differences"
                        'closing_text' => 'nullable|string',
                        'items'        => 'required|array|min:1',
                        'items.*.title'       => 'required|string|max:255',
                        'items.*.description' => 'required|string',
                    ]);

                    // Re-index items to avoid weird keys
                    $items = array_values($data['items']);

                    $content[$key] = [
                        'main_title'   => $data['main_title'],
                        'closing_text' => $data['closing_text'] ?? '',
                        'items'        => $items,
                    ];

                    $successMsg = 'Key differences updated successfully!';
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
            Log::error("Error updating cooperative-differences section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update cooperative differences: ' . $e->getMessage(),
            ], 422);
        }
    }
}
