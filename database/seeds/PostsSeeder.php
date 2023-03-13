<?php

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostsSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Storage::deleteDirectory(storage_path('app/public/posts'));

        $this->createPost(
            '10 Common Causes of Cognitive Impairment in Seniors & the 10 Things a Doctor Should Review at an Appointment',
            'post1',
            [
                'excerpt' => 'Concerns about memory loss are common as we age. There’s always a fear that any slip of the memory could be the start of Alzheimer’s or another form of dementia.',
                'featured' => 1,
                'precedence' => 1,
                'category_id' => 3 //Mental / Dementia
            ]
        );

        $this->createPost(
            'The 10 Most Common Causes of Dementia',
            'post2',
            [
                'excerpt' => 'Although cognitive impairment may be treated and easily cured, dementia can be progressive and incurable. It’s only by properly diagnosing the cause of the cognitive impairment as early as possible that its effects can be treated and in some cases reversed or even cured. For more information see 10 Common Causes of Cognitive Impairment in Seniors & the 10 Things a Doctor Should Review at an Appointment.',
                'featured' => 1,
                'precedence' => 2,
                'category_id' => 3 //Mental / Dementia
            ]
        );

        $this->createPost(
            'How to Make Your Parent’s Home Safer for Them to Age-in-Place',
            'post3',
            [
                'excerpt' => 'When you were younger, your parents had to make their homes safer for us. Now, it may be time to make their home safer for them.',
                'featured' => 1,
                'category_id' => 13 // Home and Care
            ]
        );

        $this->createPost(
            'How to Monitor Your Parent’s Health and Safety Remotely',
            'post4',
            [
                'excerpt' => 'While home health and retirement communities are possibilities, they may not be wanted by a parent or even affordable. What other options are there?',
                'featured' => 0,
                'category_id' => 13 // Home and Care
            ]
        );

        $this->createPost(
            'Are You Ready to be Your Parent’s Caregiver?',
            'post5',
            [
                'excerpt' => 'Becoming a caregiver for a parent or other relative can be a rewarding experience. Before you take on the role, however, it’s important to be aware of the challenges that lay ahead.',
                'featured' => 0,
                'category_id' => 13 // Home and Care
            ]
        );

        $this->createPost(
            'What\'s The Difference Between Non-Medical Home Care and Home Health Care?',
            'post6',
            [
                'excerpt' => 'Non-medical home care and home health care can both come into your home for caregiving. Each, however, offers different services. Do you know what they are?',
                'featured' => 0,
                'category_id' => 13 // Home and Care
            ]
        );

        $this->createPost(
            '7 Most Common Caregiving Solutions for Seniors',
            'post7',
            [
                'excerpt' => 'If you don’t wish to move a parent into a care facility, there are a number of caregiving options available to you in both their own home and in the community.',
                'featured' => 0,
                'category_id' => 13 // Home and Care
            ]
        );

        $this->createPost(
            '7 Most Common Healthcare Challenges of Aging',
            'post8',
            [
                'excerpt' => 'As over 10,000 Baby Boomers turn 65 every day, their concerns with aging will only continue to grow, as well.',
                'featured' => 0,
                'category_id' => 13 // Home and Care
            ]
        );

        /*\DB::unprepared(file_get_contents(__DIR__ . '/imports/posts.sql'));

        factory(Post::class, 6)->create([
            'featured' => true,
            'status' => 'published',
            'precedence' => rand(1 , 6)
        ])->each(function ($p){
            $p->save();
        });

	    factory(Post::class, 5)->create();
        factory(Post::class, 1)->states('draft')->make();
        factory(Post::class, 1)->states('unpublished')->make();*/
    }

    public function createPost($title, $html, $props = [])
    {
        $body = file_get_contents(__DIR__.'/posts/' . $html . '.html');

        $post = new Post($props);
        $post->title = $title;
        $post->body = $body;
        $post->status = 'published';
        $post->author_id = 1;
        $post->publish_date = date('Y-m-d');

        $image = pathinfo($html, PATHINFO_FILENAME).'.jpg';
        $dir = __DIR__.'/posts/images/';
        $file = $dir.$image;
        if (file_exists($file)) {
            $post->copyMedia($file)->toMediaCollection('post-hero', 'posts');
        }

        $post->save();

        $this->indexModuleResource($post);

        return $post;

    }

}
