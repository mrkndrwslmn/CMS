<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Mocks\FakeFirebaseService;
use Tests\Mocks\FakeMayaPaymentService;
use App\Services\FirebaseService;
use App\Services\MayaPaymentService;

abstract class TestCase extends BaseTestCase
{
    use UseCmsSqlSchema;

    /**
     * Fake Firebase service for testing notifications
     */
    protected FakeFirebaseService $fakeFirebase;

    /**
     * Fake Maya payment service for testing payments
     */
    protected FakeMayaPaymentService $fakeMayaPayment;

    /**
     * Set up the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Set up database schema from cms.sql (instead of migrations)
        $this->setUpSchema();

        // Bind fake Firebase service
        $this->fakeFirebase = new FakeFirebaseService();
        $this->app->instance(FirebaseService::class, $this->fakeFirebase);

        // Bind fake Maya payment service
        $this->fakeMayaPayment = new FakeMayaPaymentService();
        $this->app->instance(MayaPaymentService::class, $this->fakeMayaPayment);
    }

    /**
     * Reset mock services between tests
     */
    protected function tearDown(): void
    {
        $this->fakeFirebase->reset();
        $this->fakeMayaPayment->reset();

        parent::tearDown();
    }

    /**
     * Create an admin user and authenticate
     */
    protected function actingAsAdmin(): self
    {
        $admin = \App\Models\User::factory()->admin()->create();
        return $this->actingAs($admin);
    }

    /**
     * Create a client user and authenticate
     */
    protected function actingAsClient(): self
    {
        $client = \App\Models\User::factory()->client()->create();
        return $this->actingAs($client);
    }

    /**
     * Create an adiutor user and authenticate
     */
    protected function actingAsAdiutor(): self
    {
        $adiutor = \App\Models\User::factory()->adiutor()->create();
        return $this->actingAs($adiutor);
    }
}
