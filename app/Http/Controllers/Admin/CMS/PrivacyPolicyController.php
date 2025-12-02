<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class PrivacyPolicyController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.privacy-policy.';
    protected $pageKey      = 'privacy-policy'; // CmsPage.page_key = 'privacy-policy'

    /** =====================================================
     * 🔹 LIST PAGE (Only 1 section → content)
     * ===================================================== */
    public function privacyList()
    {
        try {
            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            // We only have ONE section: "content"
            $sections = [
                [
                    'name' => 'Privacy Policy Content',
                    'type' => 'content'
                ]
            ];

            $pageTitle = $page->title ?? 'Privacy Policy';
            $routeName = 'admin.cms.privacy.page';
            $subtitle  = 'Manage the Privacy Policy page content.';

            return view('screens.admin.cms.list', compact(
                'page', 'sections', 'pageTitle', 'routeName', 'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load privacy list.');
        }
    }

    /** =====================================================
     * 🔹 SHOW EDIT PAGE
     * ===================================================== */
    public function showprivacyPage(string $type)
    {
        try {
            if ($type !== 'content') {
                return back()->with('error', 'Invalid section.');
            }

            $page = CmsPage::where('page_key', $this->pageKey)->firstOrFail();

            $contentData = $page->content['content'] ?? '';

            $pageTitle = 'Edit Privacy Policy';

            return view($this->viewBasePath . 'edit', [
                'page'         => $page,
                'sectionType'  => 'content',
                'sectionData'  => $contentData,
                'pageTitle'    => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error loading privacy section: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =====================================================
     * 🔹 UPDATE privacy CONTENT
     * ===================================================== */
    public function updateprivacySection(Request $request)
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
                'msg'    => 'Privacy Policy updated successfully!'
            ]);

        } catch (Exception $e) {
            Log::error("Error updating privacy section: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update privacy: ' . $e->getMessage(),
            ], 422);
        }
    }
}
