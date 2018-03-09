<?php

namespace Tests\Models;

use Illuminate\Support\Collection;
use Tests\TestCase;
use Shopify\Models\Model;

class ModelTest extends TestCase
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
    public function it_should_determine_whether_attributes_are_set_via_magic_method()
    {
        $data = ['attribute' => 'value'];
        $model = new Model($data);

        $this->assertTrue(isset($model->attribute));
        $this->assertFalse(isset($model->fakeAttribute));
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_determine_whether_relations_are_set_via_magic_method()
    {
        $model = new Model();
        $model->setRelations([
            'relation' => Model::class
        ]);

        $this->assertTrue(isset($model->relation));
        $this->assertFalse(isset($model->fakeRelation));
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_return_null_for_ids_that_are_not_set()
    {
        $model = new Model();

        $this->assertNull($model->getKey());
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_return_null_for_fields_that_are_not_set()
    {
        $model = new Model();

        $this->assertNull($model->fake_field);
    }

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_initialize_relational_models_that_are_defined()
    {
        $data = [
            'attribute' => 'value',
            'relation' => [
                'attribute' => 'value'
            ]
        ];

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
        $data = [
            'attribute' => 'value',
                'relations' => [
                  ['attribute' => 'value'],
                  ['attribute' => 'other value'],
            ]
        ];

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

    /**
     * @group model-tests
     *
     * @test
     */
    public function it_should_transform_model_with_relations_to_array()
    {
        $data = [
            'attribute' => 'value',
            'relations' => [
                [
                    'attribute' => 'value'
                ]
            ]
        ];

        $model = new Model();
        $model->setRelations([
            'relations' => Model::class
        ]);

        $model->fill($data);

        $this->assertInstanceOf(Collection::class, $model->relations);

        $this->assertEquals([
            'attribute' => 'value',
            'relations' => [
                [
                    'attribute' => 'value'
                ]
            ]
        ], $model->toArray());
    }
}
