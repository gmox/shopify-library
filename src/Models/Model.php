<?php

namespace Shopify\Models;

use Illuminate\Support\Collection;

class Model
{
    /** @var Collection */
    protected $attributes;

    /** @var Collection */
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
     * @return array
     */
    public function toArray(): array
    {
        $return = [];

        foreach( $this->attributes as $key => $value ) {
            // If it's a relation model, call toArray on each relational attribute
            if( $this->isRelationDefined($key) ) {
                foreach( $value as $k => $relation ) {
                    if( $k !== 'relations' && $k !== 'original' ) {
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
    public function getOriginal(): Collection
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
     * @param mixed  $key   The key of the value being set
     * @param mixed  $value The value of the key being set
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Return if an attribute or relation exist on the model
     *
     * @param mixed  $key  The key of the value being checked
     * @return bool
     */
    public function __isset($key): bool
    {
        return isset($this->attributes[$key]) || $this->isRelationDefined($key);
    }

    /**
     * Set an attribute on the collection
     *
     * @param mixed  $key    The key of the value being set
     * @param mixed $value  The value of the key being set
     */
    public function fill($data)
    {
        $this->attributes = new Collection();

        $this->fillField('attributes', $data);
    }

    /**
     * Set the relations of the model
     *
     * @param array  $relation  The relations being set
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * Check whether a relation matching the name exists on the relations array.
     *
     * @param string  $relation  The relation being checked
     * @return bool
     */
    protected function isRelationDefined($relation): bool
    {
        return isset($this->relations[$relation]);
    }

    /**
     * Get the model of the relation from the key
     *
     * @param string  $relation  The relation being checked
     * @return string  The model of the associated relation
     */
    protected function getRelationModelFromKey($relation): string
    {
        return $this->relations[$relation];
    }

    /**
     * Get the model of the relation from the key
     *
     * @param string  $relationName  The relation being built
     * @param array   $relationData  The data used to build the relation.
     * @return mixed  A collection or model that was built from data
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
     * @param array  $data  The data to set as the original
     */
    protected function setOriginal($data)
    {
        $this->original = new Collection();

        $this->fillField('original', $data);
    }

    /**
     * Fill a specific attribute (attributes, original, etc) with the data
     *
     * @param string  $field  The data to assign
     * @param array   $data   The field to set
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

    /**
     * Check if an array is multidimensional.
     *
     * @param array  $array  The array being checked
     * @return bool
     */
    protected function isMultidimensionalArray($array): bool
    {
        $values = array_filter($array, 'is_array');

        return count($values) === count(array_keys($array));
    }
}
