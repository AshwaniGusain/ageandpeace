<?php

namespace Tests\Feature;

use App\Models\Provider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\Support\AdminAuthTrait;
use Tests\TestCase;
use App\Models\Zip;

class AdminProviderTest extends TestCase
{
    use DatabaseTransactions;
    use AdminAuthTrait;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @runInSeparateProcess
     */
    public function testCannotAccessAdminProviderWithoutLoginFirst()
    {
        $response = $this->get('/admin/provider');
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCanAccessAdminProviderWithLogin()
    {
        $this->login();
        $response = $this->get('/admin/provider');
        $this->assertEquals(200, $response->getStatusCode());
        //$this->logout();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGeoPointsAreCreatedWhenLeftBlank()
    {
        $this->login();

        $response = $this->post('/admin/provider/insert', [
            '_token'      => csrf_token(),
            'user'        => ['name' => 'Rebels', 'email' => 'yoda@dagobah.com'],
            'street'      => '1001 SE Water Ave',
            'city'        => 'Portland',
            'state'       => 'OR',
            'zip'         => '97214',
            'phone'       => '555-555-5555',
            'website'     => 'http://www.thedaylightstudio.com',
            'description' => 'No! Try not. Do or do not. There is no try.',
            'slug'        => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('users', [
            'name' => 'Rebels',
        ]);

        // Assert that the geo points get generated if left blank.
        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'yoda@dagobah.com');
        })->first();
        $this->assertEquals(45.515251, $provider->geo_point->getLat());
        $this->assertEquals(-122.6661995, $provider->geo_point->getLng());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGeoPointsAreCreatedWhenEntered()
    {
        $this->login();

        // Now test that if you update the lat and lng, that they save.
        $response = $this->post('/admin/provider/insert', [
            '_token'      => csrf_token(),
            'user'        => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'      => '1001 SE Water Ave',
            'city'        => 'Portland',
            'state'       => 'OR',
            'zip'         => '97214',
            'phone'       => '555-555-5555',
            'website'     => 'http://www.thedaylightstudio.com',
            'description' => 'Laugh it up fuzz ball!',
            'latitude'    => 45,
            'longitude'   => -122,
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'han@milleniumfalcon.com');
        })->first();

        $this->assertEquals(45, $provider->geo_point->getLat());
        $this->assertEquals(-122, $provider->geo_point->getLng());

    }

    /**
     * @runInSeparateProcess
     */
    public function testZipCodesServicedAreAdded()
    {
        $this->login();

        $response = $this->post('/admin/provider/insert', [
            '_token'          => csrf_token(),
            'user'            => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'          => '1001 SE Water Ave',
            'city'            => 'Portland',
            'state'           => 'OR',
            'zip'             => '97214',
            'phone'           => '555-555-5555',
            'website'         => 'http://www.thedaylightstudio.com',
            'description'     => 'Laugh it up fuzz ball!',
            'latitude'        => 45,
            'longitude'       => -122,
            'searchable_zips' => [0 => '66207', 1 => '66208'],
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'han@milleniumfalcon.com');
        })->first();

        $zipOne = Zip::where('zipcode', '66207')->first();
        $zipTwo = Zip::where('zipcode', '66208')->first();

        $this->assertDatabaseHas('provider_zip', [
            'provider_id' => $provider->id,
            'zip_id'      => $zipOne->id
        ]);

        $this->assertDatabaseHas('provider_zip', [
            'provider_id' => $provider->id,
            'zip_id'      => $zipTwo->id
        ]);

    }

    /**
     * @runInSeparateProcess
     */
    public function testZipCodesServicedAreUpdated()
    {
        $this->login();

        $response = $this->post('/admin/provider/insert', [
            '_token'          => csrf_token(),
            'user'            => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'          => '1001 SE Water Ave',
            'city'            => 'Portland',
            'state'           => 'OR',
            'zip'             => '97214',
            'phone'           => '555-555-5555',
            'website'         => 'http://www.thedaylightstudio.com',
            'description'     => 'Laugh it up fuzz ball!',
            'latitude'        => 45,
            'longitude'       => -122,
            'searchable_zips' => [0 => '66207', 1 => '66208'],
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());

        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'han@milleniumfalcon.com');
        })->first();


        $response = $this->patch('/admin/provider/' . $provider->id . '/update', [
            '_token'          => csrf_token(),
            'user'            => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'          => '1001 SE Water Ave',
            'city'            => 'Portland',
            'state'           => 'OR',
            'zip'             => '97214',
            'phone'           => '555-555-5555',
            'website'         => 'http://www.thedaylightstudio.com',
            'description'     => 'Laugh it up fuzz ball!',
            'latitude'        => 45,
            'longitude'       => -122,
            'searchable_zips' => [0 => '66207'],
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());

        $zipOne = Zip::where('zipcode', '66207')->first();
        $zipTwo = Zip::where('zipcode', '66208')->first();

        $this->assertDatabaseHas('provider_zip', [
            'provider_id' => $provider->id,
            'zip_id'      => $zipOne->id
        ]);

        $this->assertDatabaseMissing('provider_zip', [
            'provider_id' => $provider->id,
            'zip_id'      => $zipTwo->id
        ]);

    }

    /**
     * @runInSeparateProcess
     */
    public function testNationalProviderFilter()
    {
        $this->login();

        $response = $this->post('/admin/provider/insert', [
            '_token'      => csrf_token(),
            'user'        => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'      => '1001 SE Water Ave',
            'city'        => 'Portland',
            'state'       => 'OR',
            'zip'         => '97214',
            'phone'       => '555-555-5555',
            'website'     => 'http://www.thedaylightstudio.com',
            'description' => 'Laugh it up fuzz ball!',
            'latitude'    => 45,
            'longitude'   => -122,
            'national'    => true,
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());

        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'han@milleniumfalcon.com');
        })->first();

        $z = Zip::where('zipcode', '66209')->first();

        $nearProviders = Provider::zipRadius($z->geo_point)->pluck('id');

        $this->assertContains($provider->id, $nearProviders);

    }

    /**
     * @runInSeparateProcess
     */
    public function testAddressFilter()
    {
        $this->login();

        $response = $this->post('/admin/provider/insert', [
            '_token'      => csrf_token(),
            'user'        => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'      => '1001 SE Water Ave',
            'city'        => 'Portland',
            'state'       => 'OR',
            'zip'         => '97214',
            'phone'       => '555-555-5555',
            'website'     => 'http://www.thedaylightstudio.com',
            'description' => 'Laugh it up fuzz ball!',
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());

        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'han@milleniumfalcon.com');
        })->first();

        $z = Zip::where('zipcode', '97214')->first();

        $nearProviders = Provider::zipRadius($z->geo_point)->pluck('id');

        $this->assertContains($provider->id, $nearProviders);

    }

    /**
     * @runInSeparateProcess
     */
    public function testZipAssociatedFilter()
    {
        $this->login();

        $response = $this->post('/admin/provider/insert', [
            '_token'          => csrf_token(),
            'user'            => ['name' => 'Han Solo', 'email' => 'han@milleniumfalcon.com'],
            'street'          => '1001 SE Water Ave',
            'city'            => 'Portland',
            'state'           => 'OR',
            'zip'             => '97214',
            'phone'           => '555-555-5555',
            'website'         => 'http://www.thedaylightstudio.com',
            'description'     => 'Laugh it up fuzz ball!',
            'latitude'        => 45,
            'longitude'       => -122,
            'searchable_zips' => [0 => '66209'],
            'slug' => 'hans'
        ]);

        $this->assertEquals(302, $response->getStatusCode());

        $provider = Provider::whereHas('user', function ($query) {
            $query->where('email', 'han@milleniumfalcon.com');
        })->first();

        $z = Zip::where('zipcode', '66209')->first();

        $nearProviders = Provider::zipRadius($z->geo_point)->pluck('id');

        $this->assertContains($provider->id, $nearProviders);

    }
}
