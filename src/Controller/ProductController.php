<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\Product;
use App\Repository\OwnerRepository;
use App\Repository\SellerRepository;
use App\Repository\ProductRepository;
use Lcobucci\JWT\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="api_GetAll_Products", methods={"GET"})
     */
    public function getAll(ProductRepository $productRepository)
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => 'product']);
    }

    /**
     * @Route("/api/product/{id}", name="api_product_getOne", methods={"GET"})
     */
    public function getOne($id, ProductRepository $productRepository)
    {

        if ($productRepository->find($id) == null) {
            $json = ['message' => 'id not found'];

            return $this->json($json, 400, []);
        }
        return $this->json($productRepository->find($id), 200, [], ['groups' => 'product']);
    }

    /**
     * @Route("/api/products/owner/{id}", name="api_product_owner_getAll", methods={"GET"})
     */
    public function getbyownerid($id, ProductRepository $productRepository)
    {

        if ($productRepository->findBy(['Owner' => $id]) == null) {
            $json = ['message' => 'id not found'];

            return $this->json($json, 400, []);
        }
        return $this->json($productRepository->findBy(['Owner' => $id]), 200, [], ['groups' => 'product']);
    }

    /**
     * @Route("/api/product/{id}", name="Delete_product_by_id", methods={"DELETE"})
     */
    public function DeleteProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface)
    {
        if ($productRepository->find($id) == null) {
            $json = ['message' => 'id not found'];

            return $this->json($json, 400, []);
        }

        $product = $productRepository->findOneBy(['id' => $id]);

        $entityManagerInterface->remove($product);
        $entityManagerInterface->flush();

        $json = [
            'Deleted Product' => $product,
            'message' => 'product deleted successfully',
        ];

        return $this->json($json, 202, [], ['groups' => 'product']);
    }


    /**
     * @Route("/api/product", name="add_new_product", methods={"POST"})
     */
    public function add(Request $request, OwnerRepository $ownerRepository , SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManagerInterface)
    {
        $jsonResponse = $request->getContent();
        try {



            $product = $serializer->deserialize($jsonResponse, Product::class, 'json');
            

            $product->setOwner($ownerRepository->find($product->getOwner()->getId()));



            // $errors = $validator->validate($product);

            // if (count($errors) > 0) {
            //     return $this->json($errors, 400);
            // }


            $entityManagerInterface->persist($product);

            $entityManagerInterface->flush();


            return $this->json($product, 201, [] , ['groups' => 'product']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], 400);
        } catch (NotNullConstraintViolationException $e) {
            return $this->json([
                'status' => $e->getErrorCode(),
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
