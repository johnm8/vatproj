<?php

namespace App\Controller;

use App\Entity\CmoPrice;
use App\Entity\CmoVatRate;
use App\Form\PriceVatRateForm;
use App\Repository\CmoVatRateRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/vat", name="vat_")
 */
class VatRateController extends AbstractController
{

    private EntityManagerInterface $em;
    private CmoVatRateRepository $cvrRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->cvrRepo = $em->getRepository(CmoVatRate::class);
    }


    /**
     * @Route("/new", name="new")
     */
    public function createVatRate(Request $request): Response
    {
        $cmoVR = new CmoVatRate();
        $form = $this->createForm(PriceVatRateForm::class, $cmoVR);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cmoVR = $form->getData();
            $this->em->persist($cmoVR);
            $this->em->flush();

            //I would add a flashbag succes message saying new rate added here
            return $this->redirectToRoute('vat_list');
        }


        return $this->render('vat/new.html.twig', [
          'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/list", name="list")
     */
    public function listVatRates(Request $request): Response
    {
      $cmoRates = $this->cvrRepo->findAll();

      return $this->render('vat/list.html.twig', [
        'cmoRates' => $cmoRates
      ]);
    }
 
}
