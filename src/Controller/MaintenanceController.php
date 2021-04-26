<?php

namespace App\Controller;

use DateTimeInterface;
use App\Entity\Maintenance;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MaintenanceRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaintenanceController extends AbstractController
{
    /**
     * @Route("/api/maintenance", name="maintenanceGETALL" , methods={"GET"})
     */
    public function GetAll(MaintenanceRepository $maintenanceRepository)
    {
        return $this->json($maintenanceRepository->findAll() , 200 , [] , ['groups'=>'maintenance']);
    }

    /**
     * @Route("/api/maintenance/technician/{id}", name="GET_ALL_BY_TECHNICIAN_ID" , methods={"GET"})
     */
    public function GetAllByTechnicianID($id , MaintenanceRepository $maintenanceRepository)
    {
        if($maintenanceRepository->findBy(['Technician'=>$id]) == null){
            return $this->json([
                "message" => "technician not found"
            ],404);
        }
        return $this->json($maintenanceRepository->findby(['Technician'=>$id]) , 200 , [] , ['groups'=>'maintenance']);
        
    }

    /**
     * @Route("/api/maintenance/product/{id}", name="GET_ALL_BY_Product_ID" , methods={"GET"})
     */
    public function GetAllByProductID($id , MaintenanceRepository $maintenanceRepository)
    {
        if($maintenanceRepository->findBy(['Product'=>$id]) == null){
            return $this->json([
                "message" => "Product not found"
            ],404);
        }
        return $this->json($maintenanceRepository->findby(['Product'=>$id]) , 200 , [] , ['groups'=>'maintenance']);
        
    }


    /**
     * @Route("/api/maintenance", name="ADD_NEW_MAINTENANCE" , methods={"POST"})
     */
    public function AddNewMaintenance(Request $request, ProductRepository $productRepository , SerializerInterface $serializer , EntityManagerInterface $entityManagerInterface){
        $jsonResponse = $request->getContent();

        
        $maintenance = $serializer->deserialize($jsonResponse , Maintenance::class , 'json');

        $entityManagerInterface->persist($maintenance);
        $entityManagerInterface->flush();

        return $this->json($maintenance , 201 , [] , ['groups' => 'maintenance']);

    }


    /**
     * @Route("/api/maintenance/{idProduct}/{idIssue}", name="DELETE_MAINTENANCE" , methods={"DELETE"})
     */
    public function DeleteMaintenance($idProduct , $idIssue, Request $request, MaintenanceRepository $maintenanceRepository , SerializerInterface $serializer , EntityManagerInterface $entityManagerInterface){
        
        $maintenance = $maintenanceRepository->findOneBy(['Product' => $idProduct , 'Issue' => $idIssue]);
        
        $entityManagerInterface->remove($maintenance);
        $entityManagerInterface->flush();

        return $this->json($maintenance , 202 , [] , ['groups' => 'maintenance']);

    }

    /**
     * @Route("/api/maintenance/{idProduct}/{idIssue}", name="UPDATE_MAINTENANCE" , methods={"PUT"})
     */
    public function UpdateMaintenance($idProduct , $idIssue, Request $request, MaintenanceRepository $maintenanceRepository  , EntityManagerInterface $entityManagerInterface){
        
        $jsonResponse = $request->getContent();
        $updatedMaintenance = json_decode($jsonResponse , true);


        $maintenance = $maintenanceRepository->findOneBy(['Product' => $idProduct , 'Issue' => $idIssue]);
        

        
        empty($updatedMaintenance['ExpectedMaintenanceCost']) ? true : $maintenance->setExpectedMaintenanceCost($updatedMaintenance['ExpectedMaintenanceCost']);
        empty($updatedMaintenance['RepairDate']) ? true : $maintenance->setRepairDate(new \DateTime($updatedMaintenance['RepairDate']));



        $entityManagerInterface->persist($maintenance);
        $entityManagerInterface->flush();

        return $this->json($maintenance , 200 , [] , ['groups' => 'maintenance']);

    }


}
