<?php

use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\Company;
use App\Models\MembershipType;
use App\Models\ProviderType;
use App\Models\Category;
use App\Models\Zip;
use Illuminate\Mail\Markdown;
use League\Csv\Reader;
use Snap\Support\Helpers\GoogleHelper;
use Snap\Support\Helpers\UrlHelper;

class ProvidersSeeder extends \BaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(\App\Models\Task::class, 125)->create();
        \Storage::deleteDirectory(storage_path('app/public/providers'));

        $csv = Reader::createFromPath(__DIR__.'/imports/Categories, Providers & Tasks - Providers.csv', 'r');

        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $data) {
            if (!empty($data['Company Name'])) {
                $data = $this->clean($data);
                $this->createProvider($data);
            }
        }
    }

    protected function createProvider($data)
    {
        $company = $this->getCompany($data);

        $provider = new Provider(
            [
                'user_id' => $this->getUserId($data),
                'company_id' => $company->id,
                'membership_type_id' => $this->getMembershipId($data['Membership Tier']),
                'provider_type_id' => $this->getProviderTypeId($data['Provider Type']),
                'name' => $company->name,
                //'slug' => str_slug($company->name),
                'street' => $data['Street'],
                'city' => $data['City'],
                'zip' => $data['Zip'],
                'phone' => $this->getPhone($data['Phone']),
                'website' => $this->getWebsite($data['Website']),
                'state' => $data['State'],
                'description' => $this->getDescription($data['Description']),
                'geo_point' => $this->getCoords($data, true),
                'national' => 0,
            ]
        );

        if (!$provider->save()) {
            dump($provider->getErrors());
            dump($data);
        }

        if (isset($provider->id)) {
            if (!empty($data['Category'])) {
                $this->attachCategory($provider, $data['Category']);
            }

            if (!empty($data['Zip'])) {
                $this->attachSearchableZip($provider, $data['Zip']);
            }

            if (!empty($data['Logo File Name'])) {
                $this->attachLogo($provider, $data['Logo File Name']);
            }
        }

        $this->indexModuleResource($provider);

        return $provider;
    }

    protected function getUserId($data)
    {
        $name = $this->getName($data);
        if (!empty($data['Email'])) {
            $user = User::withoutGlobalScopes()->where(['email' => trim($data['Email'])])->first();
        } elseif (!empty($name)) {
            $user = User::withoutGlobalScopes()->where(['name' => $name])->first();
        }

        if (empty($user)) {
            $user = new User();
            $user->name = $name;
            $user->email = trim($data['Email']);
            $user->password = bcrypt(uniqid());
            $user->active = 0;

            if (!$user->save()) {
                dump($user->getErrors());
                dump($user->getAttributes());
            }

            $user->assignRole('provider');
        }

        if (!empty($user)) {
            return $user->id;
        }

        return null;
    }

    protected function getName($data)
    {
        $name = !empty($data['Name']) ? $data['Name'] : $data['Company Name'];
        return $name;
    }

    protected function getCompany($data)
    {
        $name = !empty($data['Company Name']) ? $data['Company Name'] : $data['Name'];
        $company = Company::firstOrCreate(['name' => $name]);

        $this->indexModuleResource($company);

        return $company;
    }

    protected function getMembershipId($tier)
    {
        $membership = MembershipType::where('tier', $tier)->first();

        if ($membership) {
            return $membership->id;
        }
        return null;
    }

    protected function getProviderTypeId($name)
    {
        $type = ProviderType::where('name', $name)->first();
        if ($type) {
            return $type->id;
        }
        return null;
    }

    protected function getWebsite($website)
    {
        return UrlHelper::prep($website);
    }

    protected function getPhone($phone)
    {
        return preg_replace("#[^0-9]#", '', $phone);
    }

    protected function getDescription($description)
    {
        return Markdown::parse($description);
    }

    protected function getCoords($data, $locate = false)
    {
        $lat = (float) $data['Lat'];
        $lng = (float) $data['Lng'];
        if ((empty($data['Lat']) || empty($data['Lng'])) && $locate) {
            $fullAddress = $data['Street'] . ', ' . $data['City'] . ', ' . $data['State'] . ' ' . $data['Zip'];
            $coords = GoogleHelper::geoLocate($fullAddress);
            $lat = $coords['latitude'];
            $lng = $coords['longitude'];
        }

        if ($lat && $lng) {
            return new Point($lat, $lng);
        }

        return null;
    }

    protected function attachCategory($provider, $name)
    {
        $name = ltrim($name, '- ');
        $category = Category::where('name', $name)->first();
        if ($category) {
            //$provider->categories()->attach($category->id);
        }
    }

    protected function attachSearchableZip($provider, $code)
    {
        $zip = Zip::where('zipcode', $code)->first();
        if ($zip) {
            $provider->zipCodes()->sync($zip->id);
        }
    }

    protected function attachLogo($provider, $filename)
    {
        $dir = __DIR__.'/providers/images/';
        $file = $dir.$filename;

        if (file_exists($file)) {
            $provider->copyMedia($file)->toMediaCollection('provider-logo', 'providers');
        }
    }

    protected function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = $this->clean($val);
            }
            return $data;
        } else {
            return trim($data);
        }
    }

}
