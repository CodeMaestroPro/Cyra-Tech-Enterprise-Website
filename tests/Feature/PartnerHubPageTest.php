<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerHubPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_partner_hub_page_renders_catalog_and_ecosystem(): void
    {
        $response = $this->get(route('partner-hub'));

        $response
            ->assertOk()
            ->assertViewIs('partner-hub.index')
            ->assertSee('Grow with Cyra-Tech through strategic partnerships')
            ->assertSee('Cloud Alliance Program')
            ->assertSee('Technology ISV Partners')
            ->assertSee('Built for mutual growth and client impact');
    }

    public function test_partner_hub_detail_page_renders_program(): void
    {
        $response = $this->get(route('partner-hub.show', 'cloud-alliance-program'));

        $response
            ->assertOk()
            ->assertViewIs('partner-hub.show')
            ->assertSee('Cloud Alliance Program')
            ->assertSee('Co-deliver secure landing zones and migrations')
            ->assertSee('Joint go-to-market motions with Cyra-Tech sales')
            ->assertSee('Apply Now');
    }

    public function test_unknown_program_returns_not_found(): void
    {
        $response = $this->get(route('partner-hub.show', 'unknown-program'));

        $response->assertNotFound();
    }
}
