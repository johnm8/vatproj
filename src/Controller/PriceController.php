<?php

namespace App\Controller;

use App\Entity\CmoPrice;
use App\Form\PriceCreateForm;
use App\Repository\CmoPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/price", name="price_")
 */
class PriceController extends AbstractController
{

    private EntityManagerInterface $em;
    private CmoPriceRepository $cpRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->cpRepo = $em->getRepository(CmoPrice::class);
    }


    /**
     * @Route("/new", name="new")
     */
    public function createPrice(Request $request): Response
    {
        $price = new CmoPrice();
        $form = $this->createForm(PriceCreateForm::class, $price);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $price = $form->getData();
            $this->em->persist($price);
            $this->em->flush();

            //I would add a flashbag succes message saying new price added here
            return $this->redirectToRoute('price_list');
        }


        return $this->render('price/new.html.twig', [
          'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function listPrices(Request $request): Response
    {
      $cmoPrices = $this->cpRepo->findAll();

      return $this->render('price/list.html.twig', [
        'cmoPrices' => $cmoPrices
      ]);
    }
 
}
