<?php


namespace Dumkaaa\Boxberry\Api\Common\Message\Response;

use Dumkaaa\Boxberry\Api\Common\Message\RequestInterface;
use Dumkaaa\Boxberry\Api\Common\Message\ResponseInterface;
use Dumkaaa\Boxberry\Api\Common\Model\AbstractModel;
use Dumkaaa\Boxberry\Api\Common\Model\CreateFromAttributesInterface;

/**
 * Абстрактный класс ответа
 *
 * @package Dumkaaa\Boxberry\Api\Common\Message\Response
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var RequestInterface Объект запроса
     */
    protected $request;

    /**
     * @var mixed Ответ (данные)
     */
    protected $data;

    /**
     * AbstractResponse constructor.
     *
     * @param RequestInterface $request
     * @param mixed            $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * Возвращает объект запроса
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return array_key_exists('err', $this->data) ? $this->data['err'] : null;
    }

    /**
     * @inheritdoc
     *
     * @return AbstractModel[]|object[]
     */
    public function getCollection($collectionClass = CreateFromAttributesInterface::class)
    {
        $collection = [];
        if ($this->isSuccessful()) {
            $data = $this->getData();
            foreach ($data as $attributes) {
                /** @var AbstractModel $collectionClass */
                $collection[] = $collectionClass::createFromAttributes($attributes);
            }
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->getStatus() == 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return array_key_exists('status', $this->data) ? $this->data['status'] : null;
    }

    /**
     * Возвращает ответ (данные)
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
