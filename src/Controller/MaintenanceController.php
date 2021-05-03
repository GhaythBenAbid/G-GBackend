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


        //Select all values from maintenance table
        $maintenance = $maintenanceRepository->findAll();
        
        //send all the data in json format
        return $this->json($maintenanceRepository->findAll() , 200 , [] , ['groups'=>'maintenance']);
    }

    /**
     * @Route("/api/maintenance/technician/{id}", name="GET_ALL_BY_TECHNICIAN_ID" , methods={"GET"})
     */
    public function GetAllByTechnicianID($id , MaintenanceRepository $maintenanceRepository)
    {

        //check if the technician id exist
        if($maintenanceRepository->findBy(['Technician'=>$id]) == null){
            return $this->json([
                "message" => "technician not found"
            ],404);


        }

        //send all the maintenance made by specific technician in json format
        return $this->json($maintenanceRepository->findby(['Technician'=>$id]) , 200 , [] , ['groups'=>'maintenance']);
        
    }

    /**
     * @Route("/api/maintenance/product/{id}", name="GET_ALL_BY_Product_ID" , methods={"GET"})
     */
    public function GetAllByProductID($id , MaintenanceRepository $maintenanceRepository)
    {

        //check if the product id exist
        if($maintenanceRepository->findBy(['Product'=>$id]) == null){
            return $this->json([
                "message" => "Product not found"
            ],404);
        }
        
        //send all the maintenance made by specific product in json format
        return $this->json($maintenanceRepository->findby(['Product'=>$id]) , 200 , [] , ['groups'=>'maintenance']);
        
    }

    /**
     * @Route("/api/maintenance/{idProduct}/{idTech}", name="GET_ALL_BY_Product_ID_AND_TECH_ID" , methods={"GET"})
     */
    public function GetAllByProductIDandTechnicienID($idProduct , $idTech , MaintenanceRepository $maintenanceRepository)
    {

        //check if the product id and technician id exist
        if($maintenanceRepository->findOneBy(['Product'=>$idProduct , 'Technician' => $idTech]) == null){
            return $this->json([
                "message" => "Product not found"
            ],404);
        }


        //send the maintenance made by specific product id and technician id in json format
        return $this->json($maintenanceRepository->findOneBy(['Product'=>$idProduct , 'Technician' => $idTech]) , 200 , [] , ['groups'=>'maintenance']);
        
    }


    /**
     * @Route("/api/maintenance", name="ADD_NEW_MAINTENANCE" , methods={"POST"})
     */
    public function AddNewMaintenance(Request $request, ProductRepository $productRepository , SerializerInterface $serializer , EntityManagerInterface $entityManagerInterface){
        
        //recive the  post request
        $jsonResponse = $request->getContent();

        //convert the request to object maintenance
        $maintenance = $serializer->deserialize($jsonResponse , Maintenance::class , 'json');

        //send it to database
        $entityManagerInterface->persist($maintenance);
        $entityManagerInterface->flush();

        //send response that the maintenace are submitted
        return $this->json($maintenance , 201 , [] , ['groups' => 'maintenance']);

    }


    /**
     * @Route("/api/maintenance/{idProduct}/{idIssue}", name="DELETE_MAINTENANCE" , methods={"DELETE"})
     */
    public function DeleteMaintenance($idProduct , $idIssue, Request $request, MaintenanceRepository $maintenanceRepository , SerializerInterface $serializer , EntityManagerInterface $entityManagerInterface){
        
        //check if the product id with issue id exist
        $maintenance = $maintenanceRepository->findOneBy(['Product' => $idProduct , 'Issue' => $idIssue]);
        
        //remove it from database
        $entityManagerInterface->remove($maintenance);
        $entityManagerInterface->flush();

        //send response that the maintenace are removed
        return $this->json($maintenance , 202 , [] , ['groups' => 'maintenance']);

    }

    /**
     * @Route("/api/maintenance/{idProduct}/{idIssue}", name="UPDATE_MAINTENANCE" , methods={"PUT"})
     */
    public function UpdateMaintenance($idProduct , $idIssue, Request $request, MaintenanceRepository $maintenanceRepository  , EntityManagerInterface $entityManagerInterface){
        
        //get the Put request
        $jsonResponse = $request->getContent();
        //convert it to associated table
        $updatedMaintenance = json_decode($jsonResponse , true);

        //get the maintenance equivlant to to the PUT request 
        $maintenance = $maintenanceRepository->findOneBy(['Product' => $idProduct , 'Issue' => $idIssue]);
        

        //update the data in the object maintenance
        empty($updatedMaintenance['ExpectedMaintenanceCost']) ? true : $maintenance->setExpectedMaintenanceCost($updatedMaintenance['ExpectedMaintenanceCost']);
        empty($updatedMaintenance['RepairDate']) ? true : $maintenance->setRepairDate(new \DateTime($updatedMaintenance['RepairDate']));


        //update the data in the database
        $entityManagerInterface->persist($maintenance);
        $entityManagerInterface->flush();


        //success response
        return $this->json($maintenance , 200 , [] , ['groups' => 'maintenance']);

    }


}
