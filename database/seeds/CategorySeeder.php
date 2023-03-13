<?php

use Illuminate\Database\Seeder;
use Illuminate\Mail\Markdown;
use League\Csv\Reader;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory(storage_path('app/public/categores'));

        $csv = Reader::createFromPath(__DIR__.'/imports/Categories, Providers & Tasks - Categories.csv', 'r');

        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $i = 1;
        $parentCategory = null;
        foreach ($records as $record => $row) {
            $name = $this->cleanName($row['Name']);
            if (!$name) continue;
            $isParentCategory = !Str::startsWith($row['Name'], '-');
            $category = new Category(
            [
                'name' => $name,
                'slug' => $this->cleanSlug($name),
                'description' => Markdown::parse($row['Description']),
                'parent_id' => (($parentCategory && !$isParentCategory) ? $parentCategory->id : null),
                'precedence' => $i,
            ]);

            $category->save();

            $imgPath = __DIR__ . '/categories/images/';
            $heroImg = $imgPath.$row['Image'];
            $defaultImg = $imgPath.$row['Provider Default Image'];

            if (is_file($heroImg)) {
                $adder = $category->addMedia($heroImg)->preservingOriginal();
                $media = $adder->toMediaCollection('category-hero', 'categories');
                $media->save();
            }

            if (is_file($defaultImg)) {
                $adder = $category->addMedia($defaultImg)->preservingOriginal();
                $media = $adder->toMediaCollection('category-default', 'categories');
                $media->save();
            }

            if ($isParentCategory) {
                $parentCategory = $category;
            }
            $i++;
        }

    }

    protected function cleanName($name)
    {
        return ltrim($name, '- ');
    }

    protected function cleanSlug($name)
    {
        $slug = preg_replace('#(.+)\(.+\)(.*)#U', '$1 $2', $name);
        $slug = str_replace([' - ', ' / '], ' ', $slug);
        $slug = Str::slug($slug);
        return $slug;
    }

}
