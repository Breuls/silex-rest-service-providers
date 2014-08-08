<?php
namespace MJanssen\Service;

use Silex\Application;
use MJanssen\Assets\Entity\Test;

class RestEntityServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $getOutput = array('id' => 1, 'name' => 'foobaz');

    /**
     * Test get action
     */
    public function testGetAction()
    {
        $service = $this->getService();
        $service->setFieldNameIdentifier('id');
        $response = $service->getAction(1);

        $this->assertEquals(
            $response,
            $this->getOutput
        );
    }

    /**
     * Test get collection action
     */
    public function testGetCollectionAction()
    {
        $service = $this->getService();
        $response = $service->getCollectionAction();

        $this->assertEquals(
            $response,
            array(
                'data' => array($this->getOutput, $this->getOutput),
                'pagination' => array(
                    'page' => 1,
                    'limit' => 20,
                    'total' => 2
                )
            )
        );
    }

    /**
     * Test delete action
     */
    public function testDeleteAction()
    {
        $service = $this->getService();
        $response = $service->deleteAction(1);

        $this->assertEquals(
            $response,
            true
        );
    }

    /**
     * Test post action
     */
    public function testPostAction()
    {
        $service = $this->getService();
        $response = $service->postAction();

        $this->assertEquals(
            $response,
            $this->getOutput
        );
    }

    /**
     * Test put action
     */
    public function testPutAction()
    {
        $service = $this->getService();
        $response = $service->putAction(1);

        $this->assertEquals(
            $response,
            $this->getOutput
        );
    }

    /**
     * @return RestEntityService
     */
    protected function getService()
    {
        return new RestEntityService(
            $this->getMockRequest(),
            $this->getMockApplication()
        );

    }

    /**
     * @return mixed
     */
    protected function getMockRequest()
    {
        return $this->getMock('Symfony\Component\HttpFoundation\Request', array('getContent'));
    }

    /**
     * Get a mock silex service
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();

        $em = $this->getMock('\Doctrine\ORM\EntityManager', array('getRepository', 'persist', 'flush', 'remove', 'merge'), array(), '', false);

        $em->expects($this->any())
           ->method('getRepository')
           ->will($this->returnValue($this->getEntityRepository()));

        $app['orm.em'] = $em;

        $app['doctrine.resolver'] = $this->getResolverServiceMock();
        $app['doctrine.extractor'] = $this->getExtractorServiceMock();
        $app['doctrine.hydrator'] = $this->getHydratorServiceMock();
        $app['service.validator'] = $this->getValidatorServiceMock();
        $app['service.request.filter'] = $this->getRequestFilterServiceMock();

        return $app;
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

    /**
     * @return mixed
     */
    protected function getEntityRepository()
    {
        $entity = $this->createEntity(array('id' => 1, 'name' => 'foobaz'));

        $repository = $this->getMock('\Doctrine\ORM\EntityRepository', array('findOneBy', 'findBy', 'count', 'paginate'), array(), '', false);

        $repository->expects($this->any())
                   ->method('findOneBy')
                   ->will($this->returnValue($entity));

        $repository->expects($this->any())
                   ->method('findBy')
                   ->will($this->returnValue(array($entity,$entity)));

        $repository->expects($this->any())
                   ->method('count')
                   ->will($this->returnValue(2));

        $repository->expects($this->any())
                    ->method('paginate')
                    ->will($this->returnValue($repository));

        return $repository;
    }

    /**
     * @return mixed
     */
    protected function getResolverServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\ResolverService', array('getEntityClassName'), array(), '', false);

        $service->expects($this->any())
                ->method('getEntityClassName')
                ->will($this->returnValue('MJanssen\Assets\Entity\Test'));

        return $service;
    }

    /**
     * @return mixed
     */
    protected function getExtractorServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\ExtractorService', array('extractEntity', 'extractEntities'), array(), '', false);

        $service->expects($this->any())
                ->method('extractEntity')
                ->will($this->returnValue($this->getOutput));

        $service->expects($this->any())
                ->method('extractEntities')
                ->will($this->returnValue(array($this->getOutput, $this->getOutput)));

        return $service;
    }

    /**
     * @return mixed
     */
    protected function getValidatorServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\ValidatorService', array('validateRequest'), array(), '', false);

        $service->expects($this->any())
                ->method('validateRequest')
                ->will($this->returnValue(null));

        return $service;
    }

    /**
     * @return mixed
     */
    protected function getRequestFilterServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\RequestFilterService', array('filter'), array(), '', false);

        $service->expects($this->any())
                ->method('filter')
                ->will($this->returnValue(
                    $this->getEntityRepository()
                ));

        return $service;
    }

    /**
     * @return mixed
     */
    protected function getHydratorServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\HydratorService', array('hydrateEntity'), array(), '', false);

        $service->expects($this->any())
                ->method('hydrateEntity')
                ->will($this->returnValue(new \stdClass()));

        return $service;
    }
}