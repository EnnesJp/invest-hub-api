<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Portfolio;
use App\Repositories\PortfolioRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_update()
    {
        $repository = $this->app->make(PortfolioRepository::class);
        $dummyPortfolio = Portfolio::factory(1)->create()[0];

        $payload = [
            'name' => 'Portfolio updated',
            'description' => 'Portfolio updated at 2021-01-01',
            'balance' => 100,
        ];

        $updated = $repository->update($dummyPortfolio, $payload);
        $this->assertSame($payload['description'], $updated->description, 'Portfolio updated does not have the same description.');
    }

    public function test_delete_will_throw_exception_when_delete_portfolio_that_doesnt_exist()
    {
        $repository = $this->app->make(PortfolioRepository::class);
        $dummy = Portfolio::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->delete($dummy);
    }

    public function test_delete()
    {
        $repository = $this->app->make(PortfolioRepository::class);
        $dummy = Portfolio::factory(1)->create()->first();

        $deleted = $repository->delete($dummy);
        $found = Portfolio::query()->find($dummy->id);

        $this->assertSame(null, $found, 'Portfolio is not deleted');

    }
}
