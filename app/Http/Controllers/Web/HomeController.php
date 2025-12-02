<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\Review; 
use App\Models\Blog;
 use App\Models\VendorBlog;
use App\Models\User;
use App\Models\TrainingVideo;
use App\Models\VendorSubscription;
use Illuminate\Support\Arr;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ Load CMS content for home page
        $cmsPage = CmsPage::where('page_key', 'home')->first();
        $content = $cmsPage->content ?? [];

        $heroBanner       = $content['hero_banner']       ?? null;
        $propertiesSection = $content['properties']        ?? null;
        $homeAbout        = $content['home_about']        ?? null;
        $advertiseSection = $content['advertise']         ?? null;
        $discoverSection  = $content['discover']          ?? null;
        $partnersSection  = $content['partners']          ?? null;
        $reviewsSection   = $content['reviews']           ?? null;
        $blogsSection     = $content['blogs']             ?? null;
        $contactSection   = $content['contact']           ?? null;

        // Home par jo bhi listings dikhani hain, unke sath images relation load karo
        $allListings = Listing::with('images')
                ->orderByRaw("CASE 
                    WHEN listing = 'featured' THEN 1
                    ELSE 2
                END")
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
        $apartmentListings = Listing::with('images')
            ->where('category', 'apartment')
            ->orderByRaw("CASE 
                    WHEN listing = 'featured' THEN 1
                    ELSE 2
                END")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $commercialListings = Listing::with('images')
            ->where('category', 'commercial')
            ->orderByRaw("CASE 
                    WHEN listing = 'featured' THEN 1
                    ELSE 2
                END")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $landOrPlotListings = Listing::with('images')
            ->where('category', 'land-or-plot')
            ->orderByRaw("CASE 
                    WHEN listing = 'featured' THEN 1
                    ELSE 2
                END")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
         
        // get_defined_vars() se upar ke sab variables view ko mil jayenge
        return view('screens.web.index', get_defined_vars());
    }

    public function about()
    {
        $page = CmsPage::where('page_key', 'about')->first();

        $content          = $page->content ?? [];
        $aboutMain        = $content['about_main'] ?? null;
        $ourStory         = $content['our_story'] ?? null;
        $exploreProperties = $content['explore_properties'] ?? null;

        return view('screens.web.about', compact(
            'page',
            'aboutMain',
            'ourStory',
            'exploreProperties'
        ));
    }
    public function faq()
    {
        $page = CmsPage::where('page_key', 'faqs')->first();

        $faqs = null;

        if ($page) {
            $content = $page->content ?? [];
            $faqs    = $content['faqs'] ?? null; // { main_title, items[] }
        }

        return view('screens.web.faq', compact(
            'page',
            'faqs'
        ));
    }
    public function privacyPolicy()
    {
        $page = CmsPage::where('page_key', 'privacy-policy')->first();

        $content = '';
        if ($page) {
            $contentArray = $page->content ?? [];
            $content      = $contentArray['content'] ?? '';
        }

        return view('screens.web.privacy-policy', compact(
            'page',
            'content'
        ));
    }
    public function terms()
    {
        $page = CmsPage::where('page_key', 'terms-conditions')->first();

        // Single section: "content"
        $content = $page->content['content'] ?? '';

        return view('screens.web.terms', compact(
            'page',
            'content'
        ));
    }
    public function cooperativeDifferences()
    {
        $page = CmsPage::where('page_key', 'cooperative-differences')->first();

        $content        = $page->content ?? [];
        $intro          = $content['intro'] ?? null;
        $keyDifferences = $content['key_differences'] ?? null;

        return view('screens.web.cooperrative-differences', compact(
            'page',
            'intro',
            'keyDifferences'
        ));
    }
    public function contact()
    {
        // Fetch CMS page for contact settings
        $page = CmsPage::where('page_key', 'contact-settings')->first();

        $content      = $page->content ?? [];
        $contactCards = $content['contact_cards'] ?? [];
        $heroText     = $content['hero_text'] ?? [];
        $mapSection   = $content['map_section'] ?? [];

        return view('screens.web.contact', compact(
            'page',
            'contactCards',
            'heroText',
            'mapSection'
        ));
    }
    public function reviews()
    {
        // Get all reviews (latest first)
        $reviews = Review::orderBy('id', 'desc')->get();

        // If you ever want to control footer behavior with $page, you can pass it too
        // $page = 'reviews';

        return view('screens.web.reviews', compact('reviews'));
    }
    public function blogs()
    {
        // Latest blogs first – paginate if you want
        $blogs = Blog::orderBy('id', 'desc')->paginate(9);

        return view('screens.web.blogs', compact('blogs'));
    }
    public function blogDetail(Blog $blog)
    {
        return view('screens.web.blog-detail', compact('blog'));
    }
    public function homeBlogDetail(string $slug)
    {
        // Load Home CMS page
        $page = CmsPage::where('page_key', 'home')->firstOrFail();
        $content = $page->content ?? [];

        $blogsSection = $content['blogs'] ?? null;
        $items        = $blogsSection['items'] ?? [];

        // Normalize to array in case it's an object
        if (!is_array($items)) {
            $items = (array) $items;
        }

        // Find blog by slug inside CMS items
        $blog = collect($items)->first(function ($item) use ($slug) {
            if (is_object($item)) {
                $item = (array) $item;
            }
            return !empty($item['slug']) && $item['slug'] === $slug;
        });

        if (!$blog) {
            abort(404);
        }

        // Ensure $blog is an array for blade
        if (is_object($blog)) {
            $blog = (array) $blog;
        }

        $pageKey = $page->page_key; // usually 'home'

        return view('screens.web.home_blog_detail', compact('blog', 'pageKey'));
    }
   

    public function vendorBlogDetail(string $slug)
    {
        // Find blog by slug
        $blog = VendorBlog::where('slug', $slug)->first();
    
        if (!$blog) {
            abort(404);
        }
        // Load the vendor who created the blog
        $vendor = User::find($blog->vendor_id);
        // Check if this vendor has Co-Op access
        $vendorProfileHasCoopAccess = VendorSubscription::active()
            ->where('vendor_id', $vendor->id)
            ->whereHas('plan', function ($q) {
                $q->where('allow_coop', 1);
            })
            ->exists();
    
        // If vendor does NOT have Co-Op access → redirect back
        if (!$vendorProfileHasCoopAccess) {
            return redirect()
                ->route('realtor.profile', $vendor)
                ->with('error', 'This vendor does not have Co-Op access.');
        }

    
        return view('screens.web.home_blog_detail', compact('blog'));
    }

    
    
    public function tutorials()
    {
        $videos = TrainingVideo::all();
        return view('screens.web.tutorials', get_defined_vars());
    }
    
    public function realtorProfile(User $vendor)
    {
        // Check if this vendor has Premium / Co-Op accesss
        $vendorProfileHasCoopAccess = VendorSubscription::active()
            ->where('vendor_id', $vendor->id)
            ->whereHas('plan', function ($q) {
                $q->where('allow_coop', 1);
            })
            ->exists();
        return view('screens.web.realtor-profile', get_defined_vars());
    }
}
