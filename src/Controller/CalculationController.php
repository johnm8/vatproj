<?php

namespace App\Controller;

use App\Entity\CmoCalculation;
use App\Entity\CmoPrice;
use App\Form\PriceCalculateForm;
use App\Repository\CmoCalculationRepository;
use App\Services\CalculationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calculation", name="calculation_")
 */
class CalculationController extends AbstractController
{

    private EntityManagerInterface $em;
    private CmoCalculationRepository $cvrRepo;
    private CalculationService $calcService;

    public function __construct(EntityManagerInterface $em,CalculationService $calcService)
    {
        $this->em = $em;
        $this->calcRepo = $em->getRepository(CmoCalculation::class);
        $this->calcService = $calcService;
    }


    /**
     * @Route("/new", name="new")
     */
    public function createCalculation(Request $request): Response
    {
        $cmoCalc = new CmoCalculation();
        $form = $this->createForm(PriceCalculateForm::class, $cmoCalc);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cmoCalc = $form->getData();
            $calcs = $this->calcService->calculate($cmoCalc->getCmoPrice(),$cmoCalc->getCmoVatRate());
            $cmoCalc->setPriceIncVat($calcs['incVatRate']);
            $cmoCalc->setPriceExcVat($calcs['excVatRate']);
            $this->em->persist($cmoCalc);
            $this->em->flush();

            //I would add a flashbag succes message saying calculation
            return $this->redirectToRoute('calculation_list',['cmoPriceId' => $cmoCalc->getCmoPrice()->getCmoPriceId()]);
        }

        //if error then would add better response for it


        return $this->render('calculation/new.html.twig', [
          'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{cmoPriceId}/list", name="list")
     */
    public function priceVatCalculations(Request $request,CmoPrice $cmoPrice): Response
    {
      $calculations =  $cmoPrice->getCmoCalculations();

      return $this->render('calculation/list.html.twig', [
        'cmoCalculations' => $calculations->toArray()
      ]);
    }
 
    /**
     * @Route("/{cmoPriceId}/csv", name="csv")
     */
    public function priceVatCalculationsCsvDownload(Request $request,CmoPrice $cmoPrice): Response
    {
      $calculations =  $this->calcRepo->getCalculationData($cmoPrice);

      $streamedResponse = new StreamedResponse();
      $streamedResponse->setCallback(
        function () use ($calculations) {
          $stream = fopen('php://output','r+');
          if (count($calculations) > 0) {
            fputcsv($stream,array_keys($calculations));
          }

          foreach ($calculations as $calculationRow) {
            fputcsv($stream,$calculationRow);
          }
          fclose($stream);
        }
      );

      $filename = 'calculations for '.$cmoPrice->getCmoPriceId().'csv';
      $streamedResponse->headers->set('Content-Type','text/csv');
      $streamedResponse->headers->set('Content-Disposition','attachment; filename='.$filename);


      return $streamedResponse;
    }


    /**
     * @Route("/{cmoPriceId}/deletecalcs", name="delete")
     */
    public function deleteCalculations(Request $request,CmoPrice $cmoPrice): Response
    {
      $calculations =  $cmoPrice->getCmoCalculations();

      return $this->render('calculation/priceVatCalculations.html.twig', [
        'calculations' => $calculations
      ]);
    }
}
