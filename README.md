# Introduction

This is a library to interact with various Shopify functionality.

It supports both HTTP Basic and OAuth2 authentication. Authentication is done via an authentication strategy. These strategies
decorate the outgoing request with the required authentication in the format that Shopify accepts.

The core functionality of this library is based on two concepts: The first is the idea of "Resources" which represent
Shopify API Resources such as Orders, Products, Customers, etc. The second concept is that of "Models" which represent
specific entities from Shopify: An order, a product, a customer, etc.

## Resources

Because Shopify's API is [RESTful](https://www.w3.org/2001/sw/wiki/REST), every resource and sub-resource will have common
CRUD methods. These methods will return either an Illuminate Collection of Model instances (when the resource returns an array of objects)
or a Model instance.

To view the index of a resource, you simple call

`$resource->index($queryParameters)`

With query parameters as you need to filter down what gets returned.

To view a specific resource

`$resource->find(123456789)`

Or o pass a model instance that will then be used to find the resource on Shopify

`$resource->find($model)`

To create a resource

`$resource->create([ 'key' => 'value' ])`

Or to use a model instance

`$resource->create($model)`

To update a resource

`$resource->update([ 'key' => 'value' ])`

Or to use a model instance

`$resource->update($model)`

**Note**: The respective objects passed into `update()` must have an `id` field set.

To delete a resource

`$resource->delete(123456789)`

Or to use a model instance

`$resource->delete($model)`

## Examples

The [examples](./examples) directory has three examples:

1. To download orders
2. To see Shopify with product data
3. To download products
