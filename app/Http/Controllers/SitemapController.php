<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Provider;
use Auth;
use Cache;
use Carbon\Carbon;
use Snap\Page\Facades\PublicUrls;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        return $this->build();
    }

    public function build()
    {
        // Only allow generation if you are logged in
        //if (Auth::user() && Auth::user()->isSuperAdmin()) {



        //$sitemap->writeToFile('sitemap.xml');
        //}

        $response = Cache::remember('sitemap', 600, function() {
            $sitemap = Sitemap::create();
            $urls = collect([
                url('providers'),
                url('providers/become-a-provider'),
                url('news'),
            ]);
            $cmsPages = $this->getCMSPages();
            $providerUrls = $this->getProviderUrls();
            $postUrls = $this->getPostUrls();
            $providerTypeUrls = $this->getCategoryUrls();

            $urls = $urls
                ->merge($cmsPages)
                ->merge($providerUrls)
                ->merge($postUrls)
                ->merge($providerTypeUrls)
            ;

            $urls = $this->setProperDomain($urls);

            $urls->each(function ($url) use ($sitemap) {
                $sitemap->add(Url::create($url)->setLastModificationDate(Carbon::yesterday())
                         ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)->setPriority(0.5));
            });

            return $sitemap->render();
        });

        return $response;

    }

    protected function getCMSPages()
    {
        return PublicUrls::get();
    }

    protected function getProviderUrls()
    {
        return Provider::where('active', 1)->get()->pluck('url');
    }

    protected function getPostUrls()
    {
        return Post::where('status', 'published')->get()->pluck('url');
    }

    protected function getCategoryUrls()
    {
        return Category::where('active', 1)->get()->pluck('url');
    }

    public function setProperDomain($urls)
    {
        foreach ($urls as $i => $url) {
            $url = preg_replace('#^http(s)?://.*/(.+)#U', '$2', $url);
            $urls[$i] = 'https://www.ageandpeace.com/'.$url;
        }

        return collect($urls);
    }

    public function generate()
    {
        SitemapGenerator::create(url('https://www.ageandpeace.com'))->getSitemap()
            //->add(Url::create('/extra-page')
            //         ->setLastModificationDate(Carbon::yesterday())
            //         ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            //         ->setPriority(0.1))
            //
            //->add(...)

                        ->writeToFile('sitemap.xml')
        ;
    }
}