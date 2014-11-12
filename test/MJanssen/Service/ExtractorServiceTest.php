<?php
namespace MJanssen\Service;

use JMS\Serializer\SerializerBuilder;
use MJanssen\Assets\Entity\Test;

class ExtractorServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $testData = array('id' => 1, 'name' => 'foo');

    /**
     * Test extract single entity
     */
    public function testExtractEntity()
    {
        $service = new ExtractorService($this->getSerializer());
        $entity = $this->createEntity($this->testData);
        $result = $service->extractEntity($entity, 'foo');

        $this->assertEquals($this->testData, $result);
    }

    /**
     * Test extracting multiple entities
     */
    public function testExtractEntities()
    {
        $service = new ExtractorService($this->getSerializer());

        $data = array(
            $this->testData,
            $this->testData
        );
        $entities = array(
            $this->createEntity($data[0]),
            $this->createEntity($data[1])
        );

        $result = $service->extractEntities($entities, 'foo');

        $this->assertEquals($data, $result);
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    protected function getSerializer()
    {
        return SerializerBuilder::create()->build();
    }

    /**
     * Create a mock entity
     * @param $args
     * @return Foo
     */
    protected function createEntity($args)
    {
        $entity = new Test;
        $entity->id = $args['id'];
        $entity->name = $args['name'];
        return $entity;
    }
}