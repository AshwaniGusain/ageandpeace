<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use App\Models\ProviderType;
use App\Models\Category;

class CategoryAndVendorTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(__DIR__.'/Tasks & Categories v2 - Categories & Vendor Types.csv', 'r');

        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $c = 1;
        $s = 1;
        $p = 1;
        foreach ($records as $record => $row)
        {
            if (!empty($row['Category'])) {
                $category = $this->createCategory($row['Category'], $c);
                $c++;
            }

            if (!empty($category) && !empty($row['SubCategory'])) {
                $subCategory = $this->createSubCategory($row['SubCategory'], $category, $s);
                $s++;
            }

            if (!empty($subCategory) && !empty($row['Vendor Type'])) {
                $this->createProviderType($row['Vendor Type'], $subCategory, $p);
                $p++;
            }
        }
    }

    protected function createCategory($name, $i)
    {
        $category = new Category(
            [
                'name' => $name,
                'slug' => $this->cleanSlug($name),
                'precedence' => $i,
            ]);

        $category->save();

        return $category;
    }

    protected function createSubCategory($name, $parent, $i)
    {
        $subCategory = new Category(
            [
                'name' => $name,
                'slug' => $this->cleanSlug($name),
                'parent_id' => $parent->id,
                'precedence' => $i,
            ]);

        $subCategory->save();

        return $subCategory;
    }

    protected function createProviderType($name, $subCategory, $i)
    {
        $providerType = new ProviderType(
            [
                'name' => $name,
                'slug' => $this->cleanSlug($name),
                //'category_id' => $subCategory->id,
                'precedence' => $i,
            ]);

        $providerType->save();
        $providerType->categories()->attach($subCategory->id);

        return $providerType;
    }

    protected function cleanSlug($name)
    {
        $slug = preg_replace('#(.+)\(.+\)(.*)#U', '$1 $2', $name);
        $slug = str_replace([' - ', ' / '], ' ', $slug);
        $slug = str_slug($slug);
        return $slug;
    }

}
