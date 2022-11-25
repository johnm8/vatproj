<?php

namespace App\Repository;

use App\Entity\CmoCalculation;
use App\Entity\CmoPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CmoCalculation>
 *
 * @method CmoCalculation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmoCalculation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmoCalculation[]    findAll()
 * @method CmoCalculation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmoCalculationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmoCalculation::class);
    }

    public function add(CmoCalculation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CmoCalculation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function getCalculationData(CmoPrice $cmoPrice): array
    {
      $conn = $this->getEntityManager()->getConnection();
      $sql = "
        select 
        cp.price as price,
        cp.currency as currency,
        cvr.rate as rate,
        cc.price_exc_vat as ex_vat,
        cc.price_inc_vat as in_vat
        from cmo_calculation as cc
        left join cmo_price as cp on cc.cmo_price_id = cp.cmo_price_id
        left join cmo_vat_rate as cvr on cc.cmo_vat_rate_id = cvr.cmo_vat_rate_id
        where cp.cmo_price_id = :priceId
      ";

      return $conn->prepare($sql)->executeQuery(['priceId' => $cmoPrice->getCmoPriceId()])->fetchAllAssociative();
    }

}
