<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\Support\AdminAuthTrait;
use Tests\TestCase;

class AdminMeTest extends TestCase
{
    use DatabaseTransactions;
    use AdminAuthTrait;

    public function setUp()
    {
        parent::setUp();
        //$this->login();
    }

    public function tearDown()
    {
        parent::tearDown();
        //$this->logout();
    }

    /**
     * @runInSeparateProcess
     */
    public function testCannotAccessAdminMeWithoutLoginFirst()
    {
        $response = $this->get('/admin/me');
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCanAccessAdminMeWithLogin()
    {
        $this->login();
        $response = $this->get('/admin/me');
        $this->assertEquals(200, $response->getStatusCode());

       //$this->logout();
    }

    /**
     * @runInSeparateProcess
     */
    public function testRequiredValidation()
    {
        $this->login();

        $response = $this->patch('/admin/me', [
                '_token'   => csrf_token(),
                'name'     => '',
                'email'    => '',
                'password' => '',
            ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHasErrors(['name', 'email']);
        $this->assertDatabaseMissing('users', [
            'name' => '',
        ]);

        $this->logout();
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmailValidation()
    {
        $this->login();

        $response = $this->patch('/admin/me', [
            '_token'   => csrf_token(),
            'name'     => 'Lord Vader',
            'email'    => 'vader@thedeathstar',
            'password' => '',
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('users', [
            'name' => 'Lord Vader',
        ]);

        $this->logout();
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmailUniqueValidation()
    {
        $this->login();

        $user = new User();
        $user->name = 'Darth Maul';
        $user->email = 'vader2@thedeathstar.com';
        $user->save();

        $response = $this->patch('/admin/me', [
            '_token'   => csrf_token(),
            'name'     => 'Lord Vader',
            'email'    => 'vader2@thedeathstar.com',
            'password' => '',
        ]);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('users', [
            'email' => 'vader2@thedeathstar.com',
            'name'  => 'Lord Vader',
        ]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSave()
    {
        $this->login();

        $response = $this->patch('/admin/me', [
            '_token'   => csrf_token(),
            'name'     => 'Lord Vader',
            'email'    => 'vader@thedeathstar.com',
            'password' => '',
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('users', [
            'name' => 'Lord Vader',
            'email' => 'vader@thedeathstar.com',
        ]);
    }

    // TODO... add validation for minimum length for password
    /**
     * @runInSeparateProcess
     */
    public function testPasswordValidation()
    {
        $this->login();

        // Test minimum length error
        $response = $this->patch('/admin/me', [
            '_token'   => csrf_token(),
            'name'     => 'Lord Vader',
            'email'    => 'vader@thedeathstar.com',
            'password' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);

        // Test minimum length error
        $response = $this->patch('/admin/me', [
            '_token'   => csrf_token(),
            'name'     => 'Lord Vader',
            'email'    => 'vader@thedeathstar.com',
            'password' => '12345678',
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('users', [
            'name' => 'Lord Vader',
            'email' => 'vader@thedeathstar.com',
        ]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testPasswordSave()
    {
        $this->login();

        $response = $this->patch('/admin/me', [
            '_token'   => csrf_token(),
            'name'     => 'Lord Vader',
            'email'    => 'vader@thedeathstar.com',
            'password' => 'abc12345',
        ]);

        $this->assertEquals(302, $response->getStatusCode());

        // We will check that the password does not equal what it was originally saved as as a check that it is being bcrypted
        $this->assertDatabaseMissing('users', [
            'password' => 'abc12345',
            'name'     => 'Lord Vader',
            'email'    => 'vader@thedeathstar.com',
        ]);

        $user = User::where('email', 'vader@thedeathstar.com')->first();
        $this->assertGreaterThan(50, strlen($user->password));
    }
}
