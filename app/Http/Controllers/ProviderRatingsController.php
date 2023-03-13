<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Rating;

class ProviderRatingsController extends Controller
{
    public function rating(Request $request, Provider $provider)
    {
        if (! $request->input('rating')) {
            return redirect()->back();
        }

        $user = $request->user();

        if ($user->hasRole('customer')) {
            Rating::updateOrCreate(
                [
                    'customer_id' => $user->customer->id,
                    'provider_id' => $provider->id
                ],
                [
                    'rating' => $request->input('rating')
                ]);
        }

        return redirect(route('provider.detail', ['provider' => $provider]));
    }
}
