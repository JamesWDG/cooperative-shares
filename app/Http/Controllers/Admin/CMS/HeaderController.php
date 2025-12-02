<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class HeaderController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.header.';
    protected $pageKey      = 'header'; // CmsPage.page_key = 'header'

    /** =======================
     * 🔹 List All Header Sections
     * ======================= */
    public function headerList()
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            /**
             * Expected JSON structure in `cms_pages.content` for header:
             *
             * {
             *   "main": {
             *     "top_bar": {
             *       "email": "anderson@theaapg.com",
             *       "phone": "816-529-7022"
             *     },
             *     "social_links": {
             *       "facebook": "#",
             *       "twitter": "#",
             *       "linkedin": "#",
             *       "whatsapp": "#"
             *     },
             *     "header": {
             *       "logo": "header_logo_...png",
             *       "logo_link_route": "index",
             *       "logo_link_url": null
             *     }
             *   }
             * }
             */

            $expectedKeys = ['main'];

            $sections = collect($expectedKeys)->map(function ($key) use ($content) {
                $section = $content[$key] ?? [];

                return [
                    'name' => $this->getSectionLabel($key, $section),
                    'type' => str_replace('_', '-', $key), // URL param
                ];
            })->values();

            $pageTitle = $page->title ?? 'Header Content';
            $routeName = 'admin.cms.header.page';
            $subtitle  = 'Manage Header top bar (email, phone, social links) and logo settings.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load Header sections.');
        }
    }

    /**
     * 🔹 Map section keys to readable labels.
     */
    protected function getSectionLabel(string $key, $section = null): string
    {
        return match ($key) {
            'main'   => 'Header & Top Bar',
            default  => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form for Header Section
     * ======================= */
    public function showHeaderPage(string $type)
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
                'main'   => 'header-main',
                default  => 'header-main',
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,
                'sectionData' => $sectionData,
                'pageTitle'   => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error showing header section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =======================
     * 🔹 Update Header Section (AJAX)
     * ======================= */
    public function updateHeaderSection(Request $request)
    {
        try {
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // main
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {
                // =====================================
                // HEADER MAIN (TOP BAR + LOGO)
                // =====================================
                case 'main':
                    $data = $request->validate([
                        // Top bar
                        'top_bar_email' => 'nullable|string|max:255',
                        'top_bar_phone' => 'nullable|string|max:255',

                        // Social links
                        'facebook_url'  => 'nullable|string|max:255',
                        'twitter_url'   => 'nullable|string|max:255',
                        'linkedin_url'  => 'nullable|string|max:255',
                        'whatsapp_url'  => 'nullable|string|max:255',

                        // Header logo
                        'logo'              => 'nullable|image',
                        'logo_link_route'   => 'nullable|string|max:255',
                        'logo_link_url'     => 'nullable|string|max:255',
                        'logo_alt_text'     => 'nullable|string|max:255',
                    ]);

                    $section = [
                        'top_bar' => [
                            'email' => $data['top_bar_email'] ?? '',
                            'phone' => $data['top_bar_phone'] ?? '',
                        ],
                        'social_links' => [
                            'facebook' => $data['facebook_url'] ?? '',
                            'twitter'  => $data['twitter_url'] ?? '',
                            'linkedin' => $data['linkedin_url'] ?? '',
                            'whatsapp' => $data['whatsapp_url'] ?? '',
                        ],
                        'header' => [
                            'logo_link_route' => $data['logo_link_route'] ?? 'index'
                        ],
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Handle logo upload
                    if ($request->hasFile('logo')) {
                        $existingLogo = $content[$key]['header']['logo'] ?? null;
                        if ($existingLogo && file_exists($uploadPath . '/' . $existingLogo)) {
                            @unlink($uploadPath . '/' . $existingLogo);
                        }

                        $image    = $request->file('logo');
                        $filename = 'header_logo_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['header']['logo'] = $filename;
                    } else {
                        if (isset($content[$key]['header']['logo'])) {
                            $section['header']['logo'] = $content[$key]['header']['logo'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Header & top bar updated successfully!';
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
            Log::error("Error updating header section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update Header section: ' . $e->getMessage(),
            ], 422);
        }
    }
}
