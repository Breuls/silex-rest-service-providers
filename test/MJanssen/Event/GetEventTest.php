<?php
namespace MJanssen\Event;

use MJanssen\Assets\Entity\Test;
use PHPUnit_Framework_TestCase;

class GetEventTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->event = new GetEvent();
    }

    public function testSetObjectName()
    {
        $testName = 'MJanssen\Assets\Entity\Test';

        $this->event->setObjectName($testName);

        $this->assertSame(
            $testName,
            $this->event->getObjectName()
        );
    }

    public function testSetObject()
    {
        $testEntity = new Test();

        $this->event->setObject($testEntity);

        $this->assertSame(
            $testEntity,
            $this->event->getObject()
        );
    }

    public function testSetObjectManager()
    {
        $repository = $this->getObjectManagerMock();

        $this->event->setObjectManager(
            $repository
        );

        $this->assertSame(
            $repository,
            $this->event->getObjectManager()
        );
    }

    public function testSetObjectRepository()
    {
        $repository = $this->getObjectRepositoryMock();

        $this->event->setObjectRepository(
            $repository
        );

        $this->assertSame(
            $repository,
            $this->event->getObjectRepository()
        );
    }

    public function testSetIdentifier()
    {
        $identifier = 1;

        $this->event->setIdentifier($identifier);

        $this->assertSame(
            $identifier,
            $this->event->getIdentifier()
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getObjectRepositoryMock()
    {
        return $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectRepository')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getObjectManagerMock()
    {
        return $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();
    }

} 