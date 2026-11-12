<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard_and_pie_chart(): void
    {
        $response = $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]))->get(route('dashboard'));

        $response->assertSee('<canvas id="myPieChart"', false);
        $this->assertSame(1, substr_count($response->getContent(), 'chart-pie-demo.js'));
    }
}
