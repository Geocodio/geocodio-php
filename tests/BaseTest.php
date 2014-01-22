<?php

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set Protected Attribute of a class.
     *
     * @param object &$object       Instantiated object that property needs to be changed on (by reference).
     * @param string $attributeName Attribute name to change.
     * @param string $value         Value to change it to.
     *
     * @return null
     */
    public function setAttribute(&$object, $attributeName, $value)
    {
        if (is_object($object)) {
            $class = get_class($object);
        } else {
            $class = $object;
        }

        // Create the reflection.
        $reflection = new \ReflectionProperty($class, $attributeName);
        // Make it accessible (so we can change it).
        $reflection->setAccessible(true);
        // Use the instantiated object and set the value.
        $reflection->setValue($object, $value);
    }

    /**
     * Get Protected Attribute of a class.
     *
     * @param object $object       Instantiated object that property needs to be retrieved.
     * @param string $attributeName Attribute name to retrieve.
     *
     * @return mixed
     */
    public function getAttribute($object, $attributeName)
    {
        if (is_object($object)) {
            $class = get_class($object);
        } else {
            $class = $object;
        }

        // Create the reflection.
        $reflection = new \ReflectionClass($object);
        // Get the property
        $property = $reflection->getProperty($attributeName);
        // set it accessible so we can retrieve it
        $property->setAccessible(true);
        // Use the instantiated object and get the value.
        return $property->getValue($object);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $method     Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $method, array $parameters = array())
    {
        $class = get_class($object);

        // Create reflection
        $reflection = new \ReflectionClass($class);
        // Get the method.
        $method = $reflection->getMethod($method);
        // Set it to accessible
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}