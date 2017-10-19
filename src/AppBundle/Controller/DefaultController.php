<?php

namespace AppBundle\Controller;

use AppBundle\Document\Location;
use AppBundle\Document\MaterialItem;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @param LoggerInterface $logger
     * @Route("/log", name="log.action")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logAction(LoggerInterface $logger)
    {
        $logger->error('I left the oven on!', array(
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ));

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/item", name="add_item")
     */
    public function addItem()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $location = new Location();
        $location->setAddress('New-York, Bavarya str. 12/2, 220137');
        $dm->persist($location);

        $item = new MaterialItem();
        $item->setCode('CLNPW');
        $item->setDescription('Pressure washing machine - variable pressure');
        $item->setUnit('DA');
        $item->setQuantity(1.63764860381532);
        $item->setUnitPrice(72.34);
        $item->setTotal(118.47);
        $item->setLocation($location);
        $dm->persist($item);

        $item2 = new MaterialItem();
        $item2->setCode('CLNPW');
        $item2->setDescription('Pressure washing machine - variable pressure');
        $item2->setUnit('DA');
        $item2->setQuantity(1.63764860381532);
        $item2->setUnitPrice(72.34);
        $item2->setTotal(118.47);
        $item2->setLocation($location);
        $dm->persist($item2);

        $dm->flush();

        return new JsonResponse(['id' => $item->getId()]);
    }
}
