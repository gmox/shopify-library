<?php

namespace Shopify\Models;

use Illuminate\Support\Collection;

class Model
{
    /** @var Collection::class */
    protected $attributes;

    /** @var Collection::class */
    protected $original;

    /** @var array */
    protected $relations;

    /**
     * Creates a Model object. Data passed in will be set to the attributes, and the original will be set as well.
     *
     * @param GuzzleResponse  $shopifyResponse  GuzzleResponse returned by the request execution
     */
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
        $this->setOriginal($attributes);
    }

    /**
     * Turn a model into an array
     *
     * @param array
     */
    public function toArray()
    {
        $return = [];

        foreach( $this->attributes as $key => $value ) {
            // If it's a relation model, call toArray on each relational attribute
            if( $this->isRelationDefined($key) ) {
                foreach( $value as $k => $relation ) {
                    if( $k !== 'relations' ) {
                        $return[$key][] = $relation->toArray();
                    }
                }
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /**
     * Get the original array attributes
     *
     * @return Collection
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Get the key of the model
     *
     * @return string|null
     */
    public function getKey()
    {
        if( !isset($this->attributes['id']) )  {
            return null;
        }

        return $this->attributes['id'];
    }

    /**
     * Get an attribute from the collection
     *
     * @return mixed
     */
    public function __get($key)
    {
        if( !isset($this->attributes[$key]) ) {
            return null;
        }

        return $this->attributes[$key];
    }

    /**
     * Set an attribute on the collection
     *
     * @param $key    mixed  The key of the value being set
     * @param $value  mixed  The value of the key being set
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Set an attribute on the collection
     *
     * @param $key    mixed  The key of the value being set
     * @param $value  mixed  The value of the key being set
     */
    public function fill($data)
    {
        $this->attributes = new Collection();

        $this->fillField('attributes', $data);
    }

    /**
     * Set the relations of the model
     *
     * @param $relations  array  The relations being set
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * Check whether a relation matching the name exists on the relations array.
     *
     * @param $relation  string  The relation being checked
     */
    protected function isRelationDefined($relation)
    {
        return isset($this->relations[$relation]);
    }

    /**
     * Get the model of the relation from the key
     *
     * @param $relation  string  The relation being checked
     * @return string  The model of the associated relation
     */
    protected function getRelationModelFromKey($relation)
    {
        return $this->relations[$relation];
    }

    /**
     * Get the model of the relation from the key
     *
     * @param $relation  string  The relation being checked
     * @return string  The model of the associated relation
     */
    protected function buildRelationFromData($relationName, $relationData)
    {
        $relationModel = $this->getRelationModelFromKey($relationName);

        $relationObject = null;

        // if an array of an array
        if( $this->isMultidimensionalArray($relationData) ) {
            $relationCollection = new Collection();

            foreach( $relationData as $key => $relationValue ) {
                $relationCollection[] = new $relationModel($relationValue);
            }

            $relationObject = $relationCollection;
        } else {
            $relationObject = new $relationModel($relationData);
        }

        return $relationObject;
    }

    /**
     * Set the original data
     *
     * @param $data  array  The data to set as the original
     */
    protected function setOriginal($data)
    {
        $this->original = new Collection();

        $this->fillField('original', $data);
    }

    /**
     * Fill a specific attribute (attributes, original, etc) with the data
     *
     * @param $field  string  The data to assign
     * @param $data   array   The field to set
     */
    protected function fillField($field, $data)
    {
        foreach( $data as $key => $value ) {
            if( $this->isRelationDefined($key) ) {
                $value = $this->buildRelationFromData($key, $value);
            }
            $this->{$field}[$key] = $value;
        }
    }

    protected function isMultidimensionalArray($a)
    {
        $rv = array_filter($a, 'is_array');

        return count($rv) === count(array_keys($a));
    }
}
