<?php

namespace Pazulx\RESTBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pazulx\RESTBundle\DTO\DtoInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;
use Pazulx\RESTBundle\Exception\ValidationException;

class RestRequestParamConverter implements ParamConverterInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface  $validator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotFoundHttpException When invalid date given
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $param = $configuration->getName();
        $options = $this->getOptions($configuration);
        if (!($content = $request->getContent())) {
            return false;
        }
        $class = $configuration->getClass();

        $object = $this->serializer->deserialize($content, $class, 'json');
        if (isset($options['validate']) && $options['validate'] == true) {
            $this->validate($object);
        }

        $request->attributes->set($param, $object);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return is_subclass_of($configuration->getClass(), DtoInterface::class);
    }

    /**
     * validate.
     *
     * @param mixed $data
     */
    private function validate($data)
    {
        $violations = $this->validator->validate($data);

        if (count($violations) > 0) {
            throw new ValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, $violations);
        }
    }

    private function getOptions(ParamConverter $configuration)
    {
        $defaultValues = array(
            'validate' => false,
        );

        $passedOptions = $configuration->getOptions();

        $extraKeys = array_diff(array_keys($passedOptions), array_keys($defaultValues));
        if ($extraKeys) {
            throw new \InvalidArgumentException(sprintf('Invalid option(s) passed to @%s: %s', $this->getAnnotationName($configuration), implode(', ', $extraKeys)));
        }

        return array_replace($defaultValues, $passedOptions);
    }
}
