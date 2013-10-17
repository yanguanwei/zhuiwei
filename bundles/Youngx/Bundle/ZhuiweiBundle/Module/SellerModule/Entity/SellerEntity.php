<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity;

use Youngx\Database\Entity;

class SellerEntity extends Entity
{
    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;

    const TYPE_WILLING = 1;
    const TYPE_VIP1 = 2;
    const TYPE_VIP2 = 3;
    const TYPE_VIP3 = 4;
    const TYPE_VIP4 = 5;

    protected $uid;
    protected $factory_id = 0;
    protected $status;
    protected $type;
    protected $started_at;
    protected $ended_at;

    public static function type()
    {
        return 'seller';
    }

    public static function table()
    {
        return 'zw_seller';
    }

    public static function primaryKey()
    {
        return 'uid';
    }

    public static function fields()
    {
        return array(
            'uid', 'factory_id', 'status', 'type', 'started_at', 'ended_at'
        );
    }

    /**
     * @param int $factory_id
     */
    public function setFactoryId($factory_id)
    {
        $this->factory_id = $factory_id;
    }

    /**
     * @return int
     */
    public function getFactoryId()
    {
        return $this->factory_id;
    }


    /**
     * @return FactoryEntity | null
     */
    public function getFactory()
    {
        return $this->factory_id ? $this->repository()->load('factory', $this->factory_id) : null;
    }

    /**
     * @param mixed $ended_at
     */
    public function setEndedAt($ended_at)
    {
        if (is_numeric($ended_at)) {
            $this->ended_at = intval($ended_at);
        } else {
            $this->ended_at = strtotime($ended_at);
        }
    }

    /**
     * @return mixed
     */
    public function getEndedAt()
    {
        return intval($this->ended_at);
    }

    /**
     * @param mixed $started_at
     */
    public function setStartedAt($started_at)
    {
        if (is_numeric($started_at)) {
            $this->started_at = intval($started_at);
        } else {
            $this->started_at = strtotime($started_at);
        }
    }

    /**
     * @return mixed
     */
    public function getStartedAt()
    {
        return intval($this->started_at);
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}