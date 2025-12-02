<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class TermsConditionsController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.terms-conditions.';
    protected $pageKey      = 'terms-conditions'; // CmsPage.page_key = 'terms-conditions'

    /** =====================================================
     * 🔹 LIST PAGE (Only 1 section → content)
     * ===================================================== */
    public function termsList()
    {
        try {
            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            // We only have ONE section: "content"
            $sections = [
                [
                    'name' => 'Terms & Conditions Content',
                    'type' => 'content'
                ]
            ];

            $pageTitle = $page->title ?? 'Terms & Conditions';
            $routeName = 'admin.cms.terms.page';
            $subtitle  = 'Manage the Terms & Conditions page content.';

            return view('screens.admin.cms.list', compact(
                'page', 'sections', 'pageTitle', 'routeName', 'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load terms list.');
        }
    }

    /** =====================================================
     * 🔹 SHOW EDIT PAGE
     * ===================================================== */
    public function showTermsPage(string $type)
    {
        try {
            if ($type !== 'content') {
                return back()->with('error', 'Invalid section.');
            }

            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            $contentData = $page->content['content'] ?? '';

            $pageTitle = 'Edit Terms & Conditions';

            return view($this->viewBasePath . 'edit', [
                'page'         => $page,
                'sectionType'  => 'content',
                'sectionData'  => $contentData,
                'pageTitle'    => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error loading terms section: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =====================================================
     * 🔹 UPDATE TERMS CONTENT
     * ===================================================== */
    public function updateTermsSection(Request $request)
    {
        try {
            $validated = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string|in:content',
                'content'     => 'required|string'
            ]);

            $page = CmsPage::where('page_key', $validated['page_key'])->firstOrFail();

            // Update content in DB
            $content              = $page->content ?? [];
            $content['content']   = $validated['content'];

            $page->update(['content' => $content]);

            return response()->json([
                'status' => true,
                'msg'    => 'Terms & Conditions updated successfully!'
            ]);

        } catch (Exception $e) {
            Log::error("Error updating terms section: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update terms: ' . $e->getMessage(),
            ], 422);
        }
    }
}
