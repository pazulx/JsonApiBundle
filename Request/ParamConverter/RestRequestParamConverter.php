<?php

namespace Pazulx\JsonApiBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pazulx\JsonApiBundle\DTO\DtoInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;

class RestRequestParamConverter implements ParamConverterInterface
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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

        $class = $configuration->getClass();

        if ($options['type'] == 'body') {

            if (!($content = $request->getContent())) {
                return false;
            }
            if (!empty($options['array_of'])) {
                $class = 'array<' . $options['array_of'] . '>';
            }

            $object = $this->serializer->deserialize($content, $class, 'json');
        } elseif ($options['type'] == 'query') {

            $object = new $class();

            foreach($object->getProperties() as $property) {
                if ($request->query->has($property)) {
                    $object->$property = $request->query->get($property);
                }
            }
        } else {
            throw new \Exception('Unsuported type');
        }

        $request->attributes->set($param, $object);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        $options = $configuration->getOptions();
        if (!empty($options['array_of'])) {
            return is_subclass_of($options['array_of'], DtoInterface::class);
        }
        if (null === $configuration->getClass()) {
            return false;
        }

        return is_subclass_of($configuration->getClass(), DtoInterface::class);
    }

    protected function getOptions(ParamConverter $configuration)
    {
        $defaultValues = array(
            'array_of' => null,
            'type' => 'body',
        );

        $passedOptions = $configuration->getOptions();

        $extraKeys = array_diff(array_keys($passedOptions), array_keys($defaultValues));
        if ($extraKeys) {
            throw new \InvalidArgumentException(sprintf('Invalid option(s) passed to @%s: %s', $this->getAnnotationName($configuration), implode(', ', $extraKeys)));
        }

        return array_replace($defaultValues, $passedOptions);
    }
}
