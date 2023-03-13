<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use App\Models\Category;
use App\Models\ProviderType;
use App\Models\ProviderTypeGroup;
use Illuminate\Support\Str;

class ProviderTypeSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(__DIR__.'/imports/Categories, Providers & Tasks - Provider Types.csv', 'r');

        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $i = 1;
        foreach ($records as $record => $row) {
            $name = $row['Name'];
            $slug = $this->cleanSlug($name);
            $providerType = ProviderType::where('slug', $slug)->first();
            $category = $this->getCategory($row['Category']);
            if (!isset($providerType)) {
                $group = $this->getGroup($row['Group']);
                $providerType = new ProviderType(
                    [
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $row['Description'],
                        'provider_type_group_id' => ($group ? $group->id : null),
                        //'precedence' => ($slug == 'technology') ? 999 : $i,
                        'precedence' => $i,
                    ]);

                $providerType->save();
            }

            if ($category) {
                $providerType->categories()->attach($category->id, ['precedence' => $i]);
            }

            $this->indexModuleResource($providerType);

            $i++;
        }
    }

    protected function getCategory($name)
    {
        $name = ltrim($name, '- ');
        return Category::where('name', $name)->first();
    }

    protected function getGroup($name)
    {
        return ProviderTypeGroup::firstOrCreate(['name' => $name]);
    }

    protected function cleanSlug($name)
    {
        $slug = preg_replace('#(.+)\(.+\)(.*)#U', '$1 $2', $name);
        $slug = str_replace([' - ', ' / '], ' ', $slug);
        $slug = Str::slug($slug);
        return $slug;
    }

}
