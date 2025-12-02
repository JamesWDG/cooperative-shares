<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class SocialIconsController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.social-icons.';
    protected $pageKey      = 'social-icons'; // CmsPage.page_key = 'social-icons'

    /** =======================
     * 🔹 List All Sections (likely just social_links)
     * ======================= */
    public function socialList()
    {
        try {
            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            // content = [ 'social_links' => [ {...}, {...} ] ]
            $sections = collect($page->content ?? [])->map(
                fn ($section, $key) => [
                    'name' => ucfirst(str_replace('_', ' ', $key)), // e.g. "Social links"
                    'type' => str_replace('_', '-', $key),
                ]
            )->values();

            // Dynamic page title (priority: DB title, then fallback)
            $pageTitle = $page->title ?? 'All ' . ucwords(str_replace('-', ' ', $this->pageKey));

            // Route name for edit page
            $routeName = 'admin.cms.social-icons.page';
            $subtitle ='Manage social icons and their platforms';
            return view('screens.admin.cms.list', compact('page', 'sections', 'pageTitle', 'routeName','subtitle'));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load social icons sections.');
        }
    }
    /** =======================
     * 🔹 Show Edit Form for a Section (social_links)
     * ======================= */
    public function showSocialPage($type)
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            // Convert hyphen → underscore
            $cleanType = str_replace('-', '_', $type);

            if (!array_key_exists($cleanType, $content)) {
                return back()->with('error', 'Section not found.');
            }

            $socialLinks = $content[$cleanType];

            $pageTitle    = 'Edit Section: ' . ucfirst(str_replace('_', ' ', $cleanType));

            return view($this->viewBasePath . 'edit', [
                'page'         => $page,
                'sectionType'  => $cleanType,
                'socialLinks'  => $socialLinks,
                'pageTitle'    => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error showing social section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =======================
     * 🔹 Update Social Icons Section (AJAX)
     * ======================= */
    public function updateSocialSection(Request $request)
    {
        try {
            $validated = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string',

                'social_links'              => 'required|array',
                'social_links.*.platform'   => 'required|string|max:100',
                'social_links.*.icon'       => 'required|string|max:255',
                'social_links.*.url'        => 'required|string|max:500',
                'social_links.*.sort'       => 'nullable|integer|min:1',
            ]);

            $page = CmsPage::where('page_key', $validated['page_key'])->firstOrFail();

            $content = $page->content ?? [];
            $key     = $validated['section_key'];

            $links = array_values($validated['social_links']);

            // Ensure each icon has a sort value (fallback to sequence)
            foreach ($links as $i => &$link) {
                if (empty($link['sort'])) {
                    $link['sort'] = $i + 1;
                }
            }
            unset($link);

            // ❌ Prevent duplicate sort values
            $sortValues = array_column($links, 'sort');
            $filtered   = array_filter($sortValues, fn($v) => !is_null($v));

            if (count($filtered) !== count(array_unique($filtered))) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Sort positions must be unique. Please assign a different sort number to each social icon.',
                ], 422);
            }

            // Sort by 'sort' ascending
            usort($links, function ($a, $b) {
                return ($a['sort'] ?? 0) <=> ($b['sort'] ?? 0);
            });

            // Save array back exactly like JSON format
            $content[$key] = $links;

            $page->update(['content' => $content]);

            return response()->json([
                'status' => true,
                'msg'    => ucfirst(str_replace('_', ' ', $key)) . ' updated successfully!',
            ]);

        } catch (Exception $e) {
            Log::error("Error updating social section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update social icons: ' . $e->getMessage(),
            ], 422);
        }
    }
}
