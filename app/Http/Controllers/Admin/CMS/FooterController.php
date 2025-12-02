<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Exception;

class FooterController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.footer.';
    protected $pageKey      = 'footer'; // CmsPage.page_key = 'footer'

    /** =======================
     * 🔹 List Footer Section
     * ======================= */
    public function footerList()
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            /**
             * Expected JSON structure in `cms_pages.content` for footer:
             *
             * {
             *   "footer": {
             *     "logo": "footer_logo_xxx.png",
             *     "description": "<p>...</p>",
             *     "social_links": {
             *       "facebook": "#",
             *       "twitter": "#",
             *       "linkedin": "#"
             *     },
             *     "quick_links": [
             *       { "label": "Home",   "url": "/",             "is_route": true,  "route_name": "index" },
             *       { "label": "About",  "url": "/about",        "is_route": true,  "route_name": "about" },
             *       ...
             *     ],
             *     "helpful_links": [
             *       { "label": "Cooperative Differences", "url": "/cooperrative-differences", "is_route": true, "route_name": "cooperrative.differences" },
             *       ...
             *     ],
             *     "contact": {
             *       "phone": "816-529-7022",
             *       "email": "anderson@theaapg.com",
             *       "address": "520 E 4th st Tonganoxie, KS 66086"
             *     },
             *     "bottom_bar": {
             *       "left_text": "© Copyright 2025 <strong>Cooperative Homes</strong>. All rights Reserved",
             *       "right_text": "Design & Developed by: <strong><a href=\"https://www.webdesignglory.com/\">Web Design Glory</a></strong>"
             *     }
             *   }
             * }
             */

            // Only one logical section: "footer"
            $expectedKeys = ['footer'];

            $sections = collect($expectedKeys)->map(function ($key) use ($content) {
                $section = $content[$key] ?? [];

                return [
                    'name' => $this->getSectionLabel($key, $section),
                    'type' => str_replace('_', '-', $key), // URL param
                ];
            })->values();

            $pageTitle = $page->title ?? 'Footer Content';
            $routeName = 'admin.cms.footer.page'; // route to edit page
            $subtitle  = 'Manage footer content such as logo, description, links, contact info, and bottom bar.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load Footer sections.');
        }
    }

    /**
     * 🔹 Map section keys to readable labels.
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
            'footer' => 'Footer (Logo, Links & Contact)',
            default  => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form
     * ======================= */
    public function showFooterPage(string $type)
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            // URL param (hyphen) → JSON key (underscore)
            $cleanType = str_replace('-', '_', $type); // "footer" remains "footer"

            // If not present yet, initialize empty array so form still loads
            $sectionData = $content[$cleanType] ?? [];

            $pageTitle = 'Edit: ' . $this->getSectionLabel($cleanType, $sectionData);

            $viewName = match ($cleanType) {
                'footer' => 'footer-main',     // screens/admin/cms/footer/footer-main.blade.php
                default  => 'footer-generic',
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,
                'sectionData' => $sectionData,
                'pageTitle'   => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error showing footer section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load footer section.');
        }
    }

    /** =======================
     * 🔹 Update Footer Section (AJAX)
     * ======================= */
    public function updateFooterSection(Request $request)
    {
        try {
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // "footer"
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {

                // =====================================
                // FOOTER (LOGO + TEXT + LINKS + CONTACT)
                // =====================================
                case 'footer':
                    $data = $request->validate([
                        // Logo + description
                        'logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                        'description' => 'nullable|string',

                        // Social links
                        'facebook_url' => 'nullable|string|max:255',
                        'twitter_url'  => 'nullable|string|max:255',
                        'linkedin_url' => 'nullable|string|max:255',

                        // Quick links (left links)
                        'quick_links'               => 'nullable|array',
                        'quick_links.*.label'       => 'required_with:quick_links|string|max:255',
                        'quick_links.*.url'         => 'nullable|string|max:255',
                        'quick_links.*.is_route'    => 'nullable|boolean',
                        'quick_links.*.route_name'  => 'nullable|string|max:255',

                        // Helpful links (middle column)
                        'helpful_links'               => 'nullable|array',
                        'helpful_links.*.label'       => 'required_with:helpful_links|string|max:255',
                        'helpful_links.*.url'         => 'nullable|string|max:255',
                        'helpful_links.*.is_route'    => 'nullable|boolean',
                        'helpful_links.*.route_name'  => 'nullable|string|max:255',

                        // Contact info
                        'contact_phone'   => 'nullable|string|max:255',
                        'contact_email'   => 'nullable|string|max:255',
                        'contact_address' => 'nullable|string',

                        // Bottom bar text
                        'bottom_left_text'  => 'nullable|string',
                        'bottom_right_text' => 'nullable|string',
                    ]);

                    $section = [
                        'description' => $data['description'] ?? '',
                        'social_links' => [
                            'facebook' => $data['facebook_url'] ?? '',
                            'twitter'  => $data['twitter_url'] ?? '',
                            'linkedin' => $data['linkedin_url'] ?? '',
                        ],
                        'quick_links' => [],
                        'helpful_links' => [],
                        'contact' => [
                            'phone'   => $data['contact_phone']   ?? '',
                            'email'   => $data['contact_email']   ?? '',
                            'address' => $data['contact_address'] ?? '',
                        ],
                        'bottom_bar' => [
                            'left_text'  => $data['bottom_left_text']  ?? '',
                            'right_text' => $data['bottom_right_text'] ?? '',
                        ],
                    ];

                    // Normalize quick_links
                    if (!empty($data['quick_links']) && is_array($data['quick_links'])) {
                        $section['quick_links'] = array_values(array_map(function ($item) {
                            return [
                                'label'      => $item['label'] ?? '',
                                'url'        => $item['url'] ?? '',
                                'is_route'   => (bool)($item['is_route'] ?? false),
                                'route_name' => $item['route_name'] ?? '',
                            ];
                        }, $data['quick_links']));
                    }

                    // Normalize helpful_links
                    if (!empty($data['helpful_links']) && is_array($data['helpful_links'])) {
                        $section['helpful_links'] = array_values(array_map(function ($item) {
                            return [
                                'label'      => $item['label'] ?? '',
                                'url'        => $item['url'] ?? '',
                                'is_route'   => (bool)($item['is_route'] ?? false),
                                'route_name' => $item['route_name'] ?? '',
                            ];
                        }, $data['helpful_links']));
                    }

                    // Upload directory: storage/cms/footer/footer
                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Logo upload (with delete old)
                    if ($request->hasFile('logo')) {
                        $existingLogo = $content[$key]['logo'] ?? null;
                        if ($existingLogo && file_exists($uploadPath . '/' . $existingLogo)) {
                            @unlink($uploadPath . '/' . $existingLogo);
                        }

                        $image    = $request->file('logo');
                        $filename = 'footer_logo_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['logo'] = $filename;
                    } else {
                        if (isset($content[$key]['logo'])) {
                            $section['logo'] = $content[$key]['logo'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Footer section updated successfully!';
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
            Log::error("Error updating footer section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update Footer section: ' . $e->getMessage(),
            ], 422);
        }
    }
}
