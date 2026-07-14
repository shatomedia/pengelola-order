<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The app has no public landing page - "/" always redirects to the login screen.
     */
    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login.index'));
    }
}
