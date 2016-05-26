<?php

namespace AppBundle\Controller\FrontEnd;

use Doctrine\ODM\PHPCR\Mapping\Annotations\Date;
use Sylius\Bundle\WebBundle\Controller\Frontend\HomepageController as baseHomepageController;

class HomepageController extends baseHomepageController
{
    /**
     * Store front page.
     *
     * @return Response
     */
    public function mainAction()
    {
        dump('hi im in controller');
        $taxons = $this->container->get('sylius.context.channel')->getChannel()->getTaxons();
        $repository = $this->container->get('sylius.repository.product');

        $newProducts = $repository->findBy([], ['createdAt' => 'desc'], $limit = 6);
        $lastProduct = $repository->findBy([], ['createdAt' => 'asc'], $limit = 6);
        return $this->render('SyliusWebBundle:Frontend/Homepage:main.html.twig',
            [
                'taxons' => $taxons,
                'newProducts' => $newProducts,
                'lastProdect' => $lastProduct
            ]);
    }

    protected function getTaxonChildren($taxon)
    {
        foreach ($taxon->getChildren() as $child) {
            dump($name = $child->getName());
        }
    }
}