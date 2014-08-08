<?php
namespace MJanssen\Controller;

use MJanssen\Assets\Controller\TestDefaultController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Perform a get action
     * @return mixed
     */
    protected function doGetAction()
    {
        return $this->getTestController()->getAction(
            $this->getMockRequest(),
            $this->getMockApplication(),
            1
        );
    }

    /**
     * Perform a put action
     */
    protected function doGetCollectionAction()
    {
        return $this->getTestController()->getCollectionAction(
            $this->getMockRequest(),
            $this->getMockApplication()
        );
    }

    /**
     * Perform a delete action
     */
    protected function doDeleteAction()
    {
        return $this->getTestController()->deleteAction(
            $this->getMockRequest(),
            $this->getMockApplication(),
            1
        );
    }

    /**
     * Perform a put action
     */
    protected function doPutAction()
    {
        return $this->getTestController()->putAction(
            $this->getMockRequest(),
            $this->getMockApplication(),
            1
        );
    }

    /**
     * Perform a post action
     */
    protected function doPostAction()
    {
        return $this->getTestController()->postAction(
            $this->getMockRequest(),
            $this->getMockApplication()
        );
    }

    /**
     * Perform a resolve action
     */
    protected function doResolveAction($method = 'GET', $identifier = null)
    {
        return $this->getTestController()->resolveAction(
            $this->getMockRequest($method),
            $this->getMockApplication(),
            $identifier
        );
    }

    /**
     * @return TestController
     */
    protected function getTestController()
    {
        return new TestDefaultController();
    }

    /**
     * @param string $method
     * @return Request
     */
    protected function getMockRequest($method = '')
    {
        $request = new Request;

        if(!empty($method)) {
            $request->setMethod($method);
        }

        return $request;
    }

    /**
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();

        $serviceRestEntity = $this->getMock('MJanssen\Service\RestEntityService', array(), array($this->getMockRequest(), $app));

        $serviceRestEntity->expects($this->any())
            ->method('getAction')
            ->will(
                $this->returnValue(
                    array('foo' => 'baz')
                )
            );

        $serviceRestEntity->expects($this->any())
            ->method('getCollectionAction')
            ->will(
                $this->returnValue(
                    array(
                        'data' => array(array('foo' => 'baz'), array('foo' => 'baz')),
                        'pagination' => array(
                            'page' => 1,
                            'limit' => 20,
                            'total' => 2
                        )
                    )
                )
            );

        $serviceRestEntity->expects($this->any())
            ->method('deleteAction')
            ->will($this->returnValue(true));

        $serviceRestEntity->expects($this->any())
            ->method('putAction')
            ->will(
                $this->returnValue(
                    array('foo' => 'baz')
                )
            );

        $serviceRestEntity->expects($this->any())
            ->method('postAction')
            ->will(
                $this->returnValue(
                    array('foo' => 'baz')
                )
            );

        $app['service.rest.entity'] = $serviceRestEntity;

        return $app;
    }

} 