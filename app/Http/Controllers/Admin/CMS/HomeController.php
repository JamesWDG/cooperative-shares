<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class HomeController extends Controller
{
    protected $viewBasePath = 'screens.admin.cms.home.';
    protected $pageKey      = 'home'; // CmsPage.page_key = 'home'

    /** =======================
     * 🔹 List All Home Sections
     * ======================= */
    public function homeList()
    {
        try {
            $page    = CmsPage::where('page_key', $this->pageKey)->firstOrFail();
            $content = $page->content ?? [];

            /**
             * Expected JSON structure in `cms_pages.content` for home:
             *
             * {
             *   "hero_banner": { ... },
             *   "properties":  { ... },
             *   "home_about":  { ... },
             *   "advertise":   { ... },
             *   "discover":    { ... },
             *   "partners":    { ... },
             *   "reviews":     { ... },
             *   "blogs":       { ... },
             *   "contact":     { ... }
             * }
             */

            $expectedKeys = [
                'hero_banner',
                'properties',
                'home_about',
                'advertise',
                'discover',
                'partners',
                'reviews',
                'blogs',
                'contact',
            ];

            $sections = collect($expectedKeys)->map(function ($key) use ($content) {
                $section = $content[$key] ?? [];

                return [
                    'name' => $this->getSectionLabel($key, $section),
                    'type' => str_replace('_', '-', $key), // URL param
                ];
            })->values();

            $pageTitle = $page->title ?? 'Home Page Content';
            $routeName = 'admin.cms.home.page';
            $subtitle  = 'Manage Home page sections such as hero banner, property options, about, advertise, discover, partners, reviews, blogs, and contact.';

            return view('screens.admin.cms.list', compact(
                'page',
                'sections',
                'pageTitle',
                'routeName',
                'subtitle'
            ));
        } catch (Exception $e) {
            Log::error("Error fetching {$this->pageKey} list: " . $e->getMessage());
            return back()->with('error', 'Failed to load Home page sections.');
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

            // 1️⃣ Hero Banner
            'hero_banner' => $sectionArr['heading'] ?? 'Hero Banner',

            // 2️⃣ Property Options Section
            'properties' => $sectionArr['heading'] ?? 'Property Options Section',

            // 3️⃣ Home About Section
            'home_about' => $sectionArr['main_heading'] ?? 'Home About Section',

            // 4️⃣ Advertise / List & Manage
            'advertise' => $sectionArr['block_1_heading'] ?? 'Advertise / List & Manage Properties',

            // 5️⃣ Discover Section
            'discover' => $sectionArr['heading'] ?? 'Discover / How It Works',

            // 6️⃣ Our Partners
            'partners' => $sectionArr['heading'] ?? 'Our Partners',

            // 7️⃣ Reviews / Testimonials
            'reviews' => $sectionArr['heading'] ?? 'Reviews / Client Success Stories',

            // 8️⃣ Our Blogs
            'blogs' => $sectionArr['heading'] ?? 'Our Blogs / Latest News & Updates',

            // 9️⃣ Contact Section
            'contact' => $sectionArr['heading'] ?? 'Contact / Free Consultation',

            default => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    /** =======================
     * 🔹 Show Edit Form for a Home Section
     * ======================= */
    public function showHomePage(string $type)
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
                'hero_banner' => 'hero-banner',
                'properties'  => 'properties',
                'home_about'  => 'home-about',
                'advertise'   => 'advertise',
                'discover'    => 'discover',
                'partners'    => 'partners',
                'reviews'     => 'reviews',
                'blogs'       => 'blogs',
                'contact'     => 'contact',
                default       => 'home-generic',
            };

            return view($this->viewBasePath . $viewName, [
                'page'        => $page,
                'sectionType' => $cleanType,
                'sectionData' => $sectionData,
                'pageTitle'   => $pageTitle,
            ]);

        } catch (Exception $e) {
            Log::error("Error showing home section [$type]: " . $e->getMessage());
            return back()->with('error', 'Failed to load section.');
        }
    }

    /** =======================
     * 🔹 Update Home Section (AJAX)
     * ======================= */
    public function updateHomeSection(Request $request)
    {
        try {
            $base = $request->validate([
                'page_key'    => 'required|string',
                'section_key' => 'required|string', // hero_banner | properties | home_about | advertise | discover | partners | reviews | blogs | contact
            ]);

            $page    = CmsPage::where('page_key', $base['page_key'])->firstOrFail();
            $content = $page->content ?? [];
            $key     = $base['section_key'];

            switch ($key) {

                // =====================================
                // HERO BANNER
                // =====================================
                case 'hero_banner':
                    $data = $request->validate([
                        'heading'        => 'required|string|max:255',
                        'description'    => 'required|string',
                        'video_file'     => 'nullable|file|max:102400',
                        'play_image'     => 'nullable|image',
                        'scroll_image'   => 'nullable|image',
                        'scroll_link'    => 'nullable|string|max:255',

                        // Optional social links
                        'facebook_url'   => 'nullable|string',
                        'twitter_url'    => 'nullable|string',
                        'linkedin_url'   => 'nullable|string',
                        'whatsapp_url'   => 'nullable|string',

                        // Search form labels / placeholders (optional)
                        'property_type_label'    => 'nullable|string|max:255',
                        'rooms_label'            => 'nullable|string|max:255',
                        'baths_label'            => 'nullable|string|max:255',
                        'sqfeet_label'           => 'nullable|string|max:255',
                        'property_type_option_1' => 'nullable|string|max:255',
                        'property_type_option_2' => 'nullable|string|max:255',
                        'property_type_option_3' => 'nullable|string|max:255',
                        'rooms_placeholder'      => 'nullable|string|max:255',
                        'baths_placeholder'      => 'nullable|string|max:255',
                        'sqfeet_placeholder'     => 'nullable|string|max:255',
                        'search_button_text'     => 'nullable|string|max:255',
                    ]);

                    $section = [
                        'heading'     => $data['heading'],
                        'description' => $data['description'],

                        'social_links' => [
                            'facebook' => $data['facebook_url'] ?? '',
                            'twitter'  => $data['twitter_url'] ?? '',
                            'linkedin' => $data['linkedin_url'] ?? '',
                            'whatsapp' => $data['whatsapp_url'] ?? '',
                        ],

                        'scroll_link' => $data['scroll_link'] ?? '#property',

                        'search_form' => [
                            'property_type_label' => $data['property_type_label'] ?? '',
                            'rooms_label'         => $data['rooms_label'] ?? '',
                            'baths_label'         => $data['baths_label'] ?? '',
                            'sqfeet_label'        => $data['sqfeet_label'] ?? '',

                            'property_type_options' => array_values(array_filter([
                                $data['property_type_option_1'] ?? 'Cooperative',
                                $data['property_type_option_2'] ?? 'Senior',
                                $data['property_type_option_3'] ?? 'Family',
                            ])),

                            'rooms_placeholder'  => $data['rooms_placeholder']  ?? '',
                            'baths_placeholder'  => $data['baths_placeholder']  ?? '',
                            'sqfeet_placeholder' => $data['sqfeet_placeholder'] ?? '',
                            'search_button_text' => $data['search_button_text'] ?? '',
                        ],
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Video file
                    if ($request->hasFile('video_file')) {
                        $existingVideo = $content[$key]['video'] ?? null;
                        if ($existingVideo && file_exists($uploadPath . '/' . $existingVideo)) {
                            @unlink($uploadPath . '/' . $existingVideo);
                        }

                        $video    = $request->file('video_file');
                        $filename = 'hero_video_' . time() . '.' . $video->extension();
                        $video->move($uploadPath, $filename);

                        $section['video'] = $filename;
                    } else {
                        if (isset($content[$key]['video'])) {
                            $section['video'] = $content[$key]['video'];
                        }
                    }

                    // Play image
                    if ($request->hasFile('play_image')) {
                        $existing = $content[$key]['play_image'] ?? null;
                        if ($existing && file_exists($uploadPath . '/' . $existing)) {
                            @unlink($uploadPath . '/' . $existing);
                        }

                        $image    = $request->file('play_image');
                        $filename = 'hero_play_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['play_image'] = $filename;
                    } else {
                        if (isset($content[$key]['play_image'])) {
                            $section['play_image'] = $content[$key]['play_image'];
                        }
                    }

                    // Scroll image
                    if ($request->hasFile('scroll_image')) {
                        $existing = $content[$key]['scroll_image'] ?? null;
                        if ($existing && file_exists($uploadPath . '/' . $existing)) {
                            @unlink($uploadPath . '/' . $existing);
                        }

                        $image    = $request->file('scroll_image');
                        $filename = 'hero_scroll_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['scroll_image'] = $filename;
                    } else {
                        if (isset($content[$key]['scroll_image'])) {
                            $section['scroll_image'] = $content[$key]['scroll_image'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Hero banner section updated successfully!';
                    break;

                // =====================================
                // PROPERTIES SECTION
                // =====================================
                case 'properties':
                    $data = $request->validate([
                        'mini_heading'         => 'nullable|string|max:255',
                        'heading'              => 'required|string|max:255',
                        'tab_all_label'        => 'required|string|max:255',
                        'tab_senior_55_label'  => 'required|string|max:255',
                        'tab_senior_62_label'  => 'required|string|max:255',
                        'tab_family_label'     => 'required|string|max:255',
                        'view_all_button_text' => 'nullable|string|max:255',
                    ]);

                    $section = [
                        'mini_heading'         => $data['mini_heading'] ?? 'Property Types',
                        'heading'              => $data['heading'],
                        'tab_all_label'        => $data['tab_all_label'],
                        'tab_senior_55_label'  => $data['tab_senior_55_label'],
                        'tab_senior_62_label'  => $data['tab_senior_62_label'],
                        'tab_family_label'     => $data['tab_family_label'],
                        'view_all_button_text' => $data['view_all_button_text'] ?? 'View All',
                    ];

                    $content[$key] = $section;
                    $successMsg    = 'Properties section updated successfully!';
                    break;

                // =====================================
                // HOME ABOUT SECTION
                // =====================================
                case 'home_about':
                    $data = $request->validate([
                        'mini_heading'   => 'nullable|string|max:255',
                        'main_heading'   => 'required|string|max:255',
                        'paragraph'      => 'required|string',

                        'box_1_title'       => 'required|string|max:255',
                        'box_1_description' => 'nullable|string',
                        'box_1_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'box_2_title'       => 'required|string|max:255',
                        'box_2_description' => 'nullable|string',
                        'box_2_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'box_3_title'       => 'required|string|max:255',
                        'box_3_description' => 'nullable|string',
                        'box_3_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'box_4_title'       => 'required|string|max:255',
                        'box_4_description' => 'nullable|string',
                        'box_4_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'about_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'more_about_btn_text' => 'nullable|string|max:255',
                        'more_about_btn_link' => 'nullable|string|max:255',
                        'phone_text'          => 'nullable|string|max:255',
                        'phone_number'        => 'nullable|string|max:255',
                    ]);

                    $section = [
                        'mini_heading' => $data['mini_heading'] ?? 'About Us',
                        'main_heading' => $data['main_heading'],
                        'paragraph'    => $data['paragraph'],

                        'box_1' => [
                            'title'       => $data['box_1_title'],
                            'description' => $data['box_1_description'] ?? '',
                        ],
                        'box_2' => [
                            'title'       => $data['box_2_title'],
                            'description' => $data['box_2_description'] ?? '',
                        ],
                        'box_3' => [
                            'title'       => $data['box_3_title'],
                            'description' => $data['box_3_description'] ?? '',
                        ],
                        'box_4' => [
                            'title'       => $data['box_4_title'],
                            'description' => $data['box_4_description'] ?? '',
                        ],

                        'more_about_btn_text' => $data['more_about_btn_text'] ?? 'More About Us',
                        'more_about_btn_link' => $data['more_about_btn_link'] ?? '#',
                        'phone_text'          => $data['phone_text'] ?? 'Free Consulting',
                        'phone_number'        => $data['phone_number'] ?? '816-529-7022',
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // About image
                    if ($request->hasFile('about_image')) {
                        $existingImage = $content[$key]['about_image'] ?? null;
                        if ($existingImage && file_exists($uploadPath . '/' . $existingImage)) {
                            @unlink($uploadPath . '/' . $existingImage);
                        }

                        $image    = $request->file('about_image');
                        $filename = 'home_about_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['about_image'] = $filename;
                    } else {
                        if (isset($content[$key]['about_image'])) {
                            $section['about_image'] = $content[$key]['about_image'];
                        }
                    }

                    // Box logos
                    foreach ([1, 2, 3, 4] as $boxIndex) {
                        $fieldName = 'box_' . $boxIndex . '_logo';
                        $jsonKey   = 'box_' . $boxIndex;

                        $boxData = $section[$jsonKey] ?? [];

                        if ($request->hasFile($fieldName)) {
                            $existingLogo = $content[$key][$jsonKey]['logo'] ?? null;
                            if ($existingLogo && file_exists($uploadPath . '/' . $existingLogo)) {
                                @unlink($uploadPath . '/' . $existingLogo);
                            }

                            $image    = $request->file($fieldName);
                            $filename = 'home_about_box_' . $boxIndex . '_' . time() . '.' . $image->extension();
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
                    $successMsg    = 'Home About section updated successfully!';
                    break;

                // =====================================
                // ADVERTISE SECTION
                // =====================================
                case 'advertise':
                    $data = $request->validate([
                        'block_1_heading'     => 'required|string|max:255',
                        'block_1_paragraph'   => 'required|string',
                        'block_1_button_text' => 'nullable|string|max:255',
                        'block_1_button_link' => 'nullable|string|max:255',
                        'image_1'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'block_2_heading'     => 'required|string|max:255',
                        'block_2_paragraph'   => 'required|string',
                        'block_2_button_text' => 'nullable|string|max:255',
                        'block_2_button_link' => 'nullable|string|max:255',
                        'image_2'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                    ]);

                    $section = [
                        'block_1_heading'     => $data['block_1_heading'],
                        'block_1_paragraph'   => $data['block_1_paragraph'],
                        'block_1_button_text' => $data['block_1_button_text'] ?? 'Show Your Co-op Homes for Sale',
                        'block_1_button_link' => $data['block_1_button_link'] ?? '#',

                        'block_2_heading'     => $data['block_2_heading'],
                        'block_2_paragraph'   => $data['block_2_paragraph'],
                        'block_2_button_text' => $data['block_2_button_text'] ?? 'Manage Affordable Housing Listings',
                        'block_2_button_link' => $data['block_2_button_link'] ?? '#',
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // image_1
                    if ($request->hasFile('image_1')) {
                        $existingImage = $content[$key]['image_1'] ?? null;
                        if ($existingImage && file_exists($uploadPath . '/' . $existingImage)) {
                            @unlink($uploadPath . '/' . $existingImage);
                        }

                        $image    = $request->file('image_1');
                        $filename = 'advertise_1_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['image_1'] = $filename;
                    } else {
                        if (isset($content[$key]['image_1'])) {
                            $section['image_1'] = $content[$key]['image_1'];
                        }
                    }

                    // image_2
                    if ($request->hasFile('image_2')) {
                        $existingImage = $content[$key]['image_2'] ?? null;
                        if ($existingImage && file_exists($uploadPath . '/' . $existingImage)) {
                            @unlink($uploadPath . '/' . $existingImage);
                        }

                        $image    = $request->file('image_2');
                        $filename = 'advertise_2_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['image_2'] = $filename;
                    } else {
                        if (isset($content[$key]['image_2'])) {
                            $section['image_2'] = $content[$key]['image_2'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Advertise section updated successfully!';
                    break;

                // =====================================
                // DISCOVER SECTION
                // =====================================
                case 'discover':
                    $data = $request->validate([
                        'mini_heading' => 'nullable|string|max:255',
                        'sub_heading'  => 'nullable|string|max:255',
                        'heading'      => 'required|string|max:255',
                        'paragraph'    => 'required|string',
                        'button_text'  => 'nullable|string|max:255',

                        'video_file'   => 'nullable|file|mimes:mp4,webm,ogg|max:204800',
                        'overlay_image'=> 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                    ]);

                    $section = [
                        'mini_heading' => $data['mini_heading'] ?? 'How It Works',
                        'sub_heading'  => $data['sub_heading'] ?? 'Browse Listing, Post Your Listing, Get Noticed, and Connect & Transact.',
                        'heading'      => $data['heading'],
                        'paragraph'    => $data['paragraph'],
                        'button_text'  => $data['button_text'] ?? 'Start Your Search Now',
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    // Video file
                    if ($request->hasFile('video_file')) {
                        $existingVideo = $content[$key]['video_file'] ?? null;
                        if ($existingVideo && file_exists($uploadPath . '/' . $existingVideo)) {
                            @unlink($uploadPath . '/' . $existingVideo);
                        }

                        $video    = $request->file('video_file');
                        $filename = 'discover_video_' . time() . '.' . $video->extension();
                        $video->move($uploadPath, $filename);

                        $section['video_file'] = $filename;
                    } else {
                        if (isset($content[$key]['video_file'])) {
                            $section['video_file'] = $content[$key]['video_file'];
                        }
                    }

                    // Overlay image
                    if ($request->hasFile('overlay_image')) {
                        $existingImage = $content[$key]['overlay_image'] ?? null;
                        if ($existingImage && file_exists($uploadPath . '/' . $existingImage)) {
                            @unlink($uploadPath . '/' . $existingImage);
                        }

                        $image    = $request->file('overlay_image');
                        $filename = 'discover_overlay_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['overlay_image'] = $filename;
                    } else {
                        if (isset($content[$key]['overlay_image'])) {
                            $section['overlay_image'] = $content[$key]['overlay_image'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Discover section updated successfully!';
                    break;

                // =====================================
                // PARTNERS SECTION (REPEATER)
                // =====================================
                case 'partners':
                    $data = $request->validate([
                        'mini_heading' => 'nullable|string|max:255',
                        'heading'      => 'required|string|max:255',
                        'items'        => 'required|array|min:1',
                        'items.*.title'       => 'nullable|string|max:255',
                        'items.*.link'        => 'nullable|string|max:255',
                        'items.*.communities' => 'nullable|string|max:255',
                        'items.*.units'       => 'nullable|string|max:255',
                        'items.*.cities'      => 'nullable|string|max:255',
                        'items.*.header_text' => 'nullable|string',
                        'items.*.image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                    ]);

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $items = [];
                    foreach ($data['items'] as $i => $item) {
                        $partner = [
                            'title'       => $item['title'] ?? '',
                            'link'        => $item['link'] ?? '',
                            'communities' => $item['communities'] ?? '',
                            'units'       => $item['units'] ?? '',
                            'cities'      => $item['cities'] ?? '',
                            'header_text' => $item['header_text'] ?? '',
                        ];

                        $oldImage = $content[$key]['items'][$i]['image'] ?? null;

                        if ($request->hasFile("items.$i.image")) {
                            if ($oldImage && file_exists("$uploadPath/$oldImage")) {
                                @unlink("$uploadPath/$oldImage");
                            }

                            $imageFile = $request->file("items.$i.image");
                            $filename  = 'partner_' . time() . '_' . $i . '.' . $imageFile->extension();
                            $imageFile->move($uploadPath, $filename);

                            $partner['image'] = $filename;
                        } else {
                            if ($oldImage) {
                                $partner['image'] = $oldImage;
                            }
                        }

                        $items[] = $partner;
                    }

                    $section = [
                        'mini_heading' => $data['mini_heading'] ?? 'Our Partners',
                        'heading'      => $data['heading'],
                        'items'        => $items,
                    ];

                    $content[$key] = $section;
                    $successMsg    = 'Partners section updated successfully!';
                    break;

                // =====================================
                // REVIEWS / TESTIMONIALS SECTION
                // =====================================
                case 'reviews':
                    $data = $request->validate([
                        'mini_heading'      => 'nullable|string|max:255',
                        'heading'           => 'required|string|max:255',
                        'rating_image'      => 'nullable|image',
                        'feedback_image_1'  => 'nullable|image',
                        'feedback_image_2'  => 'nullable|image',

                        'items'             => 'required|array|min:1',
                        'items.*.quote'     => 'required|string',
                        'items.*.name'      => 'required|string|max:255',
                        'items.*.role'      => 'nullable|string|max:255',
                        'items.*.avatar'    => 'nullable|image',
                    ]);

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $section = [
                        'mini_heading' => $data['mini_heading'] ?? 'Reviews',
                        'heading'      => $data['heading'],
                    ];

                    // Rating image
                    if ($request->hasFile('rating_image')) {
                        $existingImage = $content[$key]['rating_image'] ?? null;
                        if ($existingImage && file_exists($uploadPath . '/' . $existingImage)) {
                            @unlink($uploadPath . '/' . $existingImage);
                        }

                        $image    = $request->file('rating_image');
                        $filename = 'reviews_rating_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['rating_image'] = $filename;
                    } else {
                        if (isset($content[$key]['rating_image'])) {
                            $section['rating_image'] = $content[$key]['rating_image'];
                        }
                    }

                    // Feedback area image 1 (left)
                    if ($request->hasFile('feedback_image_1')) {
                        $existingImage1 = $content[$key]['feedback_image_1'] ?? null;
                        if ($existingImage1 && file_exists($uploadPath . '/' . $existingImage1)) {
                            @unlink($uploadPath . '/' . $existingImage1);
                        }

                        $image1    = $request->file('feedback_image_1');
                        $filename1 = 'reviews_feedback_1_' . time() . '.' . $image1->extension();
                        $image1->move($uploadPath, $filename1);

                        $section['feedback_image_1'] = $filename1;
                    } else {
                        if (isset($content[$key]['feedback_image_1'])) {
                            $section['feedback_image_1'] = $content[$key]['feedback_image_1'];
                        }
                    }

                    // Feedback area image 2 (right)
                    if ($request->hasFile('feedback_image_2')) {
                        $existingImage2 = $content[$key]['feedback_image_2'] ?? null;
                        if ($existingImage2 && file_exists($uploadPath . '/' . $existingImage2)) {
                            @unlink($uploadPath . '/' . $existingImage2);
                        }

                        $image2    = $request->file('feedback_image_2');
                        $filename2 = 'reviews_feedback_2_' . time() . '.' . $image2->extension();
                        $image2->move($uploadPath, $filename2);

                        $section['feedback_image_2'] = $filename2;
                    } else {
                        if (isset($content[$key]['feedback_image_2'])) {
                            $section['feedback_image_2'] = $content[$key]['feedback_image_2'];
                        }
                    }

                    // Testimonials items
                    $items = [];
                    foreach ($data['items'] as $i => $item) {
                        $review = [
                            'quote' => $item['quote'],
                            'name'  => $item['name'],
                            'role'  => $item['role'] ?? '',
                        ];

                        $oldAvatar = $content[$key]['items'][$i]['avatar'] ?? null;

                        if ($request->hasFile("items.$i.avatar")) {
                            if ($oldAvatar && file_exists("$uploadPath/$oldAvatar")) {
                                @unlink("$uploadPath/$oldAvatar");
                            }

                            $imageFile = $request->file("items.$i.avatar");
                            $filename  = 'review_avatar_' . time() . '_' . $i . '.' . $imageFile->extension();
                            $imageFile->move($uploadPath, $filename);

                            $review['avatar'] = $filename;
                        } else {
                            if ($oldAvatar) {
                                $review['avatar'] = $oldAvatar;
                            }
                        }

                        $items[] = $review;
                    }

                    $section['items'] = $items;

                    $content[$key] = $section;
                    $successMsg    = 'Reviews section updated successfully!';
                    break;

                    // =====================================
                    // BLOGS SECTION (fixed cards)
                    // =====================================
                    case 'blogs':
                        $data = $request->validate([
                            'mini_heading'           => 'nullable|string|max:255',
                            'heading'                => 'required|string|max:255',
                            'view_all_button_text'   => 'nullable|string|max:255',
                            'view_all_button_link'   => 'nullable|string|max:255',
                    
                            'items'                  => 'required|array|min:1',
                    
                            // per-blog fields
                            'items.*.date'           => 'nullable|string|max:255',
                            'items.*.title'          => 'nullable|string|max:255',
                            'items.*.slug'           => 'nullable|string|max:255',
                    
                            'items.*.short_des'      => 'nullable|string',   // summary
                            'items.*.long_des'       => 'nullable|string',   // full HTML body
                    
                            'items.*.read_in_minutes'=> 'nullable|integer|min:1',
                    
                            'items.*.link_text'      => 'nullable|string|max:255',
                            'items.*.link_url'       => 'nullable|string|max:255', // optional, for custom links
                    
                            'items.*.image'          => 'nullable|image',
                        ]);
                    
                        $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }
                    
                        // ensure unique slugs within this section
                        $usedSlugs = [];
                        $items     = [];
                    
                        foreach ($data['items'] as $i => $item) {
                    
                            // 1) Base slug: use provided slug, or title, or fallback
                            $rawSlug = $item['slug'] ?? $item['title'] ?? null;
                            $slug    = $rawSlug ? Str::slug($rawSlug) : 'blog-' . $i;
                    
                            // 2) Ensure uniqueness within this section
                            $original = $slug;
                            $counter  = 1;
                            while (in_array($slug, $usedSlugs, true)) {
                                $slug = $original . '-' . $counter;
                                $counter++;
                            }
                            $usedSlugs[] = $slug;
                    
                            $blog = [
                                'date'            => $item['date'] ?? null,
                                'title'           => $item['title'] ?? null,
                                'slug'            => $slug,
                    
                                // match your desired columns
                                'short_des'       => $item['short_des'] ?? null,
                                'long_des'        => $item['long_des'] ?? null,
                                'read_in_minutes' => $item['read_in_minutes'] ?? null,
                    
                                // optional link text/url for buttons
                                'link_text'       => $item['link_text'] ?? 'Read More',
                                'link_url'        => $item['link_url'] ?? null,
                            ];
                    
                            // image handling (featured_img equivalent)
                            $oldImage = $content[$key]['items'][$i]['image'] ?? null;
                    
                            if ($request->hasFile("items.$i.image")) {
                                if ($oldImage && file_exists("$uploadPath/$oldImage")) {
                                    @unlink("$uploadPath/$oldImage");
                                }
                    
                                $imageFile = $request->file("items.$i.image");
                                $filename  = 'blog_' . time() . '_' . $i . '.' . $imageFile->extension();
                                $imageFile->move($uploadPath, $filename);
                    
                                $blog['image'] = $filename; // this is your featured_img
                            } else {
                                if ($oldImage) {
                                    $blog['image'] = $oldImage;
                                }
                            }
                    
                            $items[] = $blog;
                        }
                    
                        $section = [
                            'mini_heading'          => $data['mini_heading'] ?? 'Our Blogs',
                            'heading'               => $data['heading'],
                            'view_all_button_text'  => $data['view_all_button_text'] ?? 'View All',
                            'view_all_button_link'  => $data['view_all_button_link'] ?? '#',
                            'items'                 => $items,
                        ];
                    
                        $content[$key] = $section;
                        $successMsg    = 'Blogs section updated successfully!';
                        break;



                // =====================================
                // CONTACT SECTION
                // =====================================
                case 'contact':
                    $data = $request->validate([
                        'mini_heading'     => 'nullable|string|max:255',
                        'heading'          => 'required|string|max:255',
                        'right_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

                        'name_placeholder'    => 'nullable|string|max:255',
                        'email_placeholder'   => 'nullable|string|max:255',
                        'phone_placeholder'   => 'nullable|string|max:255',
                        'service_placeholder' => 'nullable|string|max:255',
                        'message_placeholder' => 'nullable|string|max:255',
                        'submit_button_text'  => 'nullable|string|max:255',
                    ]);

                    $section = [
                        'mini_heading' => $data['mini_heading'] ?? 'Contact Us',
                        'heading'      => $data['heading'],

                        'placeholders' => [
                            'name'    => $data['name_placeholder']    ?? 'Your name',
                            'email'   => $data['email_placeholder']   ?? 'Your email',
                            'phone'   => $data['phone_placeholder']   ?? 'Phone number',
                            'service' => $data['service_placeholder'] ?? 'Select Service',
                            'message' => $data['message_placeholder'] ?? 'Write Message...',
                            'submit'  => $data['submit_button_text']  ?? 'Submit Now',
                        ],
                    ];

                    $uploadPath = public_path('storage/cms/' . $page->page_key . '/' . $key);
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    if ($request->hasFile('right_image')) {
                        $existingImage = $content[$key]['right_image'] ?? null;
                        if ($existingImage && file_exists($uploadPath . '/' . $existingImage)) {
                            @unlink($uploadPath . '/' . $existingImage);
                        }

                        $image    = $request->file('right_image');
                        $filename = 'contact_right_' . time() . '.' . $image->extension();
                        $image->move($uploadPath, $filename);

                        $section['right_image'] = $filename;
                    } else {
                        if (isset($content[$key]['right_image'])) {
                            $section['right_image'] = $content[$key]['right_image'];
                        }
                    }

                    $content[$key] = $section;
                    $successMsg    = 'Contact section updated successfully!';
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
            Log::error("Error updating home section [{$request->section_key}]: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to update Home page section: ' . $e->getMessage(),
            ], 422);
        }
    }
}
