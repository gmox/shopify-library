<?php

namespace Tests\Models;

use Shopify\Models\Model;

class ModelTest extends \TestCase
{
    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_set_current_data_on_construct()
    {
        $data = ['attribute' => 'value'];
        $model = new Model($data);

        $this->assertEquals($data, $model->toArray());
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_set_original_data_on_construct()
    {
        $data = ['attribute' => 'value'];
        $model = new Model($data);

        $this->assertEquals($data, $model->getOriginal()->toArray());
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_set_attributes_via_setter()
    {
        $data = ['attribute' => 'value'];
        $model = new Model($data);

        $model->attribute = 'new value';

        $this->assertEquals(['attribute' => 'new value'], $model->toArray());
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_get_attributes_via_getter()
    {
        $data = ['attribute' => 'value'];
        $model = new Model($data);

        $this->assertEquals('value', $model->attribute);
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_initialize_relational_models_that_are_defined()
    {
        $data = ['attribute' => 'value', 'relation' => [
              'attribute' => 'value'
        ]];

        $model = new Model();
        $model->setRelations([
            'relation' => Model::class
        ]);

        $model->fill($data);

        $this->assertInstanceOf(Model::class, $model->relation);

        $this->assertEquals([
            'attribute' => 'value'
        ], $model->relation->toArray());
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_initialize_a_collection_of_relational_models_that_are_defined()
    {
        $data = ['attribute' => 'value', 'relations' => [
              [ 'attribute' => 'value' ],
              [ 'attribute' => 'other value' ],
        ]];

        $model = new Model();
        $model->setRelations([
            'relations' => Model::class
        ]);

        $model->fill($data);

        $this->assertInstanceOf(Model::class, $model->relations->first());

        $this->assertEquals([
            'attribute' => 'value'
        ], $model->relations->first()->toArray());
    }
}
