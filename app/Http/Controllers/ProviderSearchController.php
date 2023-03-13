<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Provider;
use App\Models\Zip;

class ProviderSearchController extends Controller
{
    public function providers(Request $request)
    {
        $user = $request->user();
        $categoryId = (int) $request->input('category');
        $subcategoryId = (int) $request->input('subcategory');
        $providerTypeId = (int) $request->input('type');
        $filters = array_filter([$categoryId, $subcategoryId, $providerTypeId]);
        $zipValid = true;

        // @DAVE front end filter UI is looking for this nested array structure which I was eager loading here.
        // This is passed to the ProviderFilters.vue as the categories prop
        // categories.children_categories.provider_types
        $categories = Category::topLevel()->select('name', 'id', 'parent_id', 'slug')->with([
            'childrenCategories.childrenCategories',
            'childrenCategories:id,name,slug,parent_id',
            'childrenCategories.providerTypes',
        ])->get();

        $zip = false;
        if ($request->input('zip')) {
            $zip = $request->input('zip');
        } elseif ($request->cookie('search-zip')) {
            $zip = $request->cookie('search-zip');
        } elseif ($user && $user->zip) {
            $zip = $user->zip;
        }

        if ($zip) {
            \Cookie::queue('search-zip', $zip, 60*24*365);
            $zipModel = Zip::where('zipcode', $zip)->first();

            if (!$zipModel) {
                // To nullify results
                $query = Provider::where('id', 0);
                $zipValid = false;
            } else {
                $query = Provider::zipRadius($zipModel->geo_point);
            }

        } else {
            $query = Provider::whereNotNull('zip');
        }

        if ($search = $request->input('search')) {
            $query = $query->search($search);
            $activeTab = 'keyword';
        }

        // @DAVE this $filters array is being read on the front end to know which values to set. Passing in to ProviderFilters.vue as initialFilters prop
        if ($providerTypeId) {
            $query->where('provider_type_id', $providerTypeId);
        } elseif ($subcategoryId) {
            $query->withCategory($subcategoryId);
        } elseif ($categoryId) {
            $query->withCategory($categoryId);
        }


        $providers = $query->paginate(12);

        $filters = collect($filters); //Used to set values of dropdowns on front end

        return view('providers', compact('providers', 'categories', 'zip', 'zipValid', 'filters', 'activeTab', 'search'));
    }

    public function detail(Provider $provider)
    {
        $relatedProviders = [];
        $categories = $provider->providerType->categories;

        if ($categories) {
            $categorieIds = $categories->pluck('id');
            $relatedProviders = Provider::where('id', '!=', $provider->id)->whereHas('providerType.categories', function ($q) use ($categorieIds) {
                $q->whereIn('categories.id', $categorieIds);
            })->limit(3)->get();
        }


        return view('providers.detail', compact('provider', 'relatedProviders'));
    }


}
