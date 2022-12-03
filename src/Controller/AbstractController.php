<?php

namespace App\Controller;

use App\Form\Error\ApiFormError;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AbstractController extends SymfonyController
{
    const DEFAULT_FORMAT = 'json';


    protected function buildDataResponse(object|array|null $data, ?string $context = null) : Response
    {
        $groups = ['default'];
        if ($context !== null) {
            $groups[] = $context;
        }

        $header = [];
        if ($data instanceof PaginationInterface) {
            $header = [
                'x-total-count' => $data->getTotalItemCount()
            ];
        }

        return $this->json($data, Response::HTTP_OK, $header, [ObjectNormalizer::GROUPS => $groups]);
    }

    protected function buildEmptyResponse() : Response
    {
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function buildFormErrorResponse(FormInterface $form) : Response
    {
        $apiFormError = new ApiFormError();
        $data = $apiFormError->getFormErrorsAsFormattedArray($form);

        return $this->json($data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    protected function buildErrorResponse(Exception $exception) : Response
    {
        return $this->json([$exception->getMessage()], Response::HTTP_BAD_REQUEST);
    }


    protected function buildNotFoundResponse(string $message = '') : Response
    {
        return $this->json([$message], Response::HTTP_NOT_FOUND);
    }


    protected function buildUnauthorizedResponse(string $message = '') : Response
    {
        return $this->json([$message], Response::HTTP_UNAUTHORIZED);
    }
}
