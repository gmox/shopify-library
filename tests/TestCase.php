<?php

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function assertException( $test, $callback )
    {
        try {
            $test();
            $this->fail('Exception not thrown');
        } catch( \Throwable $e ) {
            $callback($e);
        } catch( \Exception $e ) {
            $callback($e);
        }
    }
}
