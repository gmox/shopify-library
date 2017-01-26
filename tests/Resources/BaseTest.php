<?php

namespace Tests\Resources;

use Shopify\Models\Model;
use Shopify\Resources\Base;
use Shopify\Clients\Response;
use Illuminate\Support\Collection;
use Tests\Concerns\MocksGuzzleResponse;
use Shopify\Contracts\Clients\HttpClient;

class BaseTest extends \TestCase
{
    use MocksGuzzleResponse;

    /** @var HttpClient::class */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = \Mockery::mock(HttpClient::class)->shouldDeferMissing();

        $this->client->shouldReceive('execute')->andReturnUsing( function() {

            $this->createMockedGuzzleResponse([
                'resource' => [
                    'id' => 6,
                    'title' => 'Testing'
                ]
            ]);

            return new Response($this->mockedGuzzleResponse);
        })->byDefault();
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_return_index_responses_as_collections()
    {
        $this->client->shouldReceive('execute')->andReturnUsing( function() {

            $this->createMockedGuzzleResponse([
                [
                    'id' => 5,
                    'title' => 'Test',
                ],
                [
                    'id' => 6,
                    'title' => 'Testing',
                ]
            ]);

            return new Response($this->mockedGuzzleResponse);
        });

        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $resources = $base->index();

        $this->assertInstanceOf(Collection::class, $resources);

        $this->assertInstanceOf(Model::class, $resources->first());
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_return_find_responses_as_a_model()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $resource = $base->find(6);

        $this->assertInstanceOf(Model::class, $resource);
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_find_from_a_model()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $model = new Model([
            'id' => 6,
            'title' => 'Testing'
        ]);

        $resource = $base->find($model);

        $this->assertInstanceOf(Model::class, $resource);
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_find_from_an_array()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $model = [
            'id' => 6,
            'title' => 'Testing'
        ];

        $resource = $base->find($model);

        $this->assertInstanceOf(Model::class, $resource);
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_return_create_from_array_responses_as_a_model()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $resource = $base->create([
            'title' => 'Testing'
        ]);

        $this->assertInstanceOf(Model::class, $resource);
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_return_create_from_model_responses_as_a_model()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $model = new Model([
            'title' => 'Testing'
        ]);

        $resource = $base->create($model);

        $this->assertInstanceOf(Model::class, $resource);
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_return_update_from_array_responses_as_a_model()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $resource = $base->update([
            'id'    => 6,
            'title' => 'Testing'
        ]);

        $this->assertInstanceOf(Model::class, $resource);
    }

    /**
     * @group resource-tests
     * @group base-tests
     *
     * @test
     */
    public function it_should_return_update_from_model_responses_as_a_model()
    {
        $base = new Base($this->client, 'resource');

        $base->setModel(Model::class);

        $model = new Model([
            'id'    => 6,
            'title' => 'Testing'
        ]);

        $resource = $base->update($model);

        $this->assertInstanceOf(Model::class, $resource);
    }
}
