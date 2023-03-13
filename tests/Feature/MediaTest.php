<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Provider;
use App\Models\Task;
use App\Models\Customer;
use File;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Tests\Feature\Support\AdminAuthTrait;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    use AdminAuthTrait;

    public function setUp()
    {
        parent::setUp();

        $this->setUpTempTestFiles();

        $this->login();
    }

    public function getTempDirectory($suffix = '')
    {
        return __DIR__.'/Support/tmp'.($suffix == '' ? '' : '/'.$suffix);
    }

    public function getMediaDirectory($suffix = '')
    {
        return $this->getTempDirectory().'/media'.($suffix == '' ? '' : '/'.$suffix);
    }

    public function getTestFilesDirectory($suffix = '')
    {
        return $this->getTempDirectory().'/testfiles'.($suffix == '' ? '' : '/'.$suffix);
    }

    public function getTestJpg()
    {
        return $this->getTestFilesDirectory('sabotage.jpg');
    }

    public function getSmallTestJpg()
    {
        return $this->getTestFilesDirectory('conversions/sabotage_150x150.jpg');
    }

    public function getTestPng()
    {
        return $this->getTestFilesDirectory('logo_sun.png');
    }

    public function getTestPdf()
    {
        return $this->getTestFilesDirectory('test.pdf');
    }

    public function getUploadFile($path, $originalName, $mime)
    {
        $size = filesize($path);
        $error = null;
        $test = true;

        $file = new UploadedFile($path, $originalName, $mime, $size, $error, $test);

        return $file;
    }

    public function testPostAddMedia()
    {
        $post = factory(\App\Models\Post::class)->create();
        $media = $post->addMedia($this->getTestJpg())->toMediaCollection('post-hero', 'media');
        $this->assertEquals($post->image[0]->getUrl(), '/storage/media/'.$media->id.'/sabotage.jpg');
        $this->assertEquals($post->image[0]->getUrl('thumb'), '/storage/media/'.$media->id.'/conversions/sabotage-thumb.jpg');
    }

    public function testPostCreateHeroImage()
    {
        //https://laravel-news.com/testing-file-uploads-with-laravel
        //Storage::fake('public');
        //$image = UploadedFile::fake()->image('hero.jpg');

        $file = $this->getUploadFile($this->getTestJpg(), 'sabotage.jpg', 'image/jpeg');

        //Storage::disk('public')->assertExists('avatar.jpg');
        $options = ['collection' => 'post-hero', 'multiple' => false, 'sanitize' => true, 'unique' => false];

        $response = $this->call('POST', '/admin/post/insert', [
            '_token'        => csrf_token(),
            //'image'  => $file,
            'image_options' => $options,
            'title'         => 'May the Force Be With You',
            'slug'          => 'may-the-force-be-with-you',
            'body'          => 'A long time ago, in a galaxy far, far away...',
        ], [], ['image' => $file]);

        $this->assertEquals($response->getStatusCode(), 302);

        // This asserts that the image saved correctly since there is mime validation on save.
        $this->assertDatabaseHas('posts', [
            'slug' => 'may-the-force-be-with-you',
        ]);

        $post = Post::where('slug', 'may-the-force-be-with-you')->first();
        $this->assertEquals($post->image[0]->getUrl(), '/storage/media/'.$post->image[0]->id.'/sabotage.jpg');
        $this->assertEquals($post->image[0]->getUrl('thumb'), '/storage/media/'.$post->image[0]->id.'/conversions/sabotage-thumb.jpg');
    }

    // Test image mime validation on upload to /admin/post/create or update only accepts jpg and png
    public function testPostImageMime()
    {
        $file = $this->getUploadFile($this->getTestPdf(), 'test.pdf', 'application/pdf');

        //Storage::disk('public')->assertExists('avatar.jpg');
        $options = ['collection' => 'post-hero', 'multiple' => false, 'sanitize' => true, 'unique' => false];

        $response = $this->call('POST', '/admin/post/insert', [
            '_token'        => csrf_token(),
            //'image'  => $file,
            'image_options' => $options,
            'title'         => 'May the Force Be With You2',
            'slug'          => 'may-the-force-be-with-you2',
            'body'          => 'A long time ago, in a galaxy far, far away...',
        ], [], ['image' => $file]);

        $this->assertEquals($response->getStatusCode(), 302);
        $response->assertSessionHasErrors(['image']);

        // This asserts that the image saved correctly since there is mime validation on save.
        $this->assertDatabaseMissing('posts', [
            'slug' => 'may-the-force-be-with-you',
        ]);
    }

    // Test image size transformation on upload to /admin/post/create or update
    public function testProviderLogoSize()
    {
        $file = $this->getUploadFile($this->getTestJpg(), 'sabotage.jpg', 'image/jpeg');
        $options = ['collection' => 'provider-logo', 'multiple' => false, 'sanitize' => true, 'unique' => false];
        $response = $this->call('POST', '/admin/provider/insert', [
            '_token' => csrf_token(),
            'logo_options' => $options,
            //'image'  => $file,
            'user'   => ['name' => 'Darth Vader', 'email' => 'heavybreather@deathstar.com'],
            'street'      => 'Sesame St',
            'city'        => 'Gotham',
            'state'       => 'OR',
            'zip'         => '55555',
            'phone'       => '555-555-5555',
            'website'     => 'thedaylightstudio.com',
            'description' => 'May the force be with you',
            'slug' => 'logo',
        ], [], ['logo' => $file]);

        $this->assertEquals($response->getStatusCode(), 302);
        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'heavybreather@deathstar.com');
        })->first();

        $this->assertEquals(getimagesize($provider->logo[0]->getPath('thumb'))[1], 100);
    }

    // Test image mime validation on upload to /admin/post/create or update only accepts jpg and png
    public function testProviderLogoMime()
    {
        $file = $this->getUploadFile($this->getTestPdf(), 'test.pdf', 'application/pdf');

        //Storage::disk('public')->assertExists('avatar.jpg');
        $options = ['collection' => 'provider-hero', 'multiple' => false, 'sanitize' => true, 'unique' => false];

        $response = $this->call('POST', '/admin/provider/insert', [
            '_token'        => csrf_token(),
            //'image'  => $file,
            'hero_options' => $options,
            'user'   => ['name' => 'Darth Vader', 'email' => 'heavybreather@deathstar.com'],
            'street'      => 'Sesame St',
            'city'        => 'Gotham',
            'state'       => 'OR',
            'zip'         => '55555',
            'phone'       => '555-555-5555',
            'website'     => 'thedaylightstudio.com',
            'description' => 'May the force be with you',
        ], [], ['hero' => $file]);

        $this->assertEquals($response->getStatusCode(), 302);
        $response->assertSessionHasErrors(['hero']);

        // This asserts that the image saved correctly since there is mime validation on save.
        $this->assertDatabaseMissing('users', [
            'email' => 'heavybreather@deathstar.com',
        ]);
    }


    public function testTaskFile()
    {
        $file = $this->getUploadFile($this->getTestPdf(), 'test.pdf', 'application/pdf');

        $task = factory(Task::class)->create();

        $task->addMedia($file)->preservingOriginal()->toMediaCollection('task-files');

        // This asserts that the image saved correctly since there is mime validation on save.
        $this->assertDatabaseHas('media', [
            'model_id' => $task->id,
        ]);

        $customer= factory(Customer::class)->create();

        $task->issueTask($customer);

        $customerTask = $customer->tasks->where('task_id', '=', $task->id)->first();

        $this->assertNotNull($customerTask->task->file);
        
    }


    // @TODO Test unique file name option on upload to /admin/post/create or update
    // @TODO Test image setting different name for file on upload to /admin/post/create or update
    // @TODO Test multiple image uploads

    // Test basic Media association with Post object

    protected function setUpTempTestFiles()
    {
        $this->initializeDirectory($this->getTestFilesDirectory());
        File::copyDirectory(__DIR__.'/Support/testfiles', $this->getTestFilesDirectory());
    }

    // Test Validation for File Types

    // Test Basic Upload

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }

        File::makeDirectory($directory);
    }
}
