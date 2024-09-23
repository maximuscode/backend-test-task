<?php

namespace App\Controller;

use App\DTO\CalculatePriceDTO;
use App\Service\PriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PriceController extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(
        Request $request,
        PriceCalculatorService $calculatorService,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        $content = $request->getContent();
        if (empty($content)) {
            return $this->json(['error' => 'Request body is empty'], 400);
        }

        try {
            $dto = $serializer->deserialize($content, CalculatePriceDTO::class, 'json');
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        try {
            $price = $calculatorService->calculatePrice($dto->product, $dto->taxNumber, $dto->couponCode);
            return $this->json(['price' => $price]);
        } catch (NotFoundHttpException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
