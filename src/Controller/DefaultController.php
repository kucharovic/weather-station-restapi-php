<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\{Route, Method};
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Common\Collections\Criteria;
use App\Entity\{Sensor, Data};
use App\Mapper\SensorDataMapper;
use App\DTO\SensorData;
use DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/{id}", requirements={"id" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     * @Method("GET")
     */
    public function listAction(Sensor $sensor, Request $request)
    {
        if (false === $start = DateTime::createFromFormat(DateTime::ATOM, $request->query->get('start')) ) {
            $start = new DateTime('-14 days 00:00:00');
        }
        if (false === $end = DateTime::createFromFormat(DateTime::ATOM, $request->query->get('end')) ) {
            $end = new DateTime;
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('sensor', $sensor))
            ->andWhere(Criteria::expr()->andX(
                Criteria::expr()->gte('datetime', $start),
                Criteria::expr()->lte('datetime', $end)
            ))
        ;
        $sensors = array_map(
            [$this->get(SensorDataMapper::class), 'createFromEntity'],
            $this->getDoctrine()->getRepository(Data::class)->matching($criteria)->toArray()
        );
        $data = $this->get('jms_serializer')->serialize($sensors, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $json = $request->getContent();

        $data = $this->get('jms_serializer')->deserialize($json ?: '{}', SensorData::class, 'json');
        $validator = $this->get('validator');
        $errors = $validator->validate($data);

        if (0 === $errors->count()) {

            try {

                $entity = $this->get(SensorDataMapper::class)->createFromDTO($data);
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return new JsonResponse($json, Response::HTTP_CREATED, [], true);

            } catch (UniqueConstraintViolationException $e) {
                $responseCode = Response::HTTP_CONFLICT;
                $response = ['error' => [
                    'code' => $responseCode,
                    'message' => 'Double submit'
                ]];
            }

        } else {

            $responseCode = Response::HTTP_BAD_REQUEST;
            $response = ['error' => [
                'code' => $responseCode,
                'message' => 'Invalid data',
                'errors' => array_map(function ($violation) {
                    return [
                        'location' => $violation->getPropertyPath(),
                        'message' => $violation->getMessage(),
                    ];
                }, iterator_to_array($errors))
            ]];

        }

        return new JsonResponse($response, $responseCode);
    }

}