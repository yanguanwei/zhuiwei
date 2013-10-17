<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\TermEntity;
use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Entity;

class ProductEntity extends Entity
{
    protected $id;
    protected $category_id;
    protected $uid;
    protected $unit_id;
    protected $code;
    protected $title;
    protected $subtitle;
    protected $picture;
    protected $inventory;
    protected $weight;
    protected $production_time;
    protected $shipping_time;
    protected $prices;
    protected $priceIds;

    public function __sleep()
    {
        $fields = $this->fields();
        if (null === $this->priceIds) {
            foreach ($this->getPrices() as $price) {
                $this->priceIds[$price->getType()] = $price->getId();
            }
        }
        $fields[] = 'priceIds';

        return $fields;
    }

    /**
     * @return ProductDetailEntity
     */
    public function getDetail()
    {
        return $this->repository()->load('product_detail', $this->id);
    }

    /**
     * @return ProductPriceEntity[]
     */
    public function getPrices()
    {
        if (null === $this->prices) {
            if (is_array($this->priceIds)) {
                if ($this->priceIds) {
                    $entities = $this->repository()->loadMultiple('product_price', $this->priceIds);
                } else {
                    $entities = array();
                }
            } else {
                $entities = $this->repository()->query('product_price')->where('product_id=:product_id')->all(array(':product_id'=>$this->id));
            }

            $prices = array();
            foreach ($entities as $price) {
                $prices[$price->get('type')] = $price;
            }
            $this->prices = $prices;
        }

        return $this->prices;
    }

    /**
     * @return ProductPriceEntity
     */
    public function getTradePrice()
    {
        if ($this->priceIds && isset($this->priceIds[ProductPriceEntity::TYPE_TRADE])) {
            return $this->repository()->load('product_price', $this->priceIds[ProductPriceEntity::TYPE_TRADE]);
        } else {
            $prices = $this->getPrices();
            return isset($prices[ProductPriceEntity::TYPE_TRADE]) ? $prices[ProductPriceEntity::TYPE_TRADE] : null;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getTaobaoPrice()
    {
        if ($this->priceIds && isset($this->priceIds[ProductPriceEntity::TYPE_TAOBAO])) {
            return $this->repository()->load('product_price', $this->priceIds[ProductPriceEntity::TYPE_TAOBAO]);
        } else {
            $prices = $this->getPrices();
            return isset($prices[ProductPriceEntity::TYPE_TAOBAO]) ? $prices[ProductPriceEntity::TYPE_TAOBAO] : null;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getStockPrice()
    {
        if ($this->priceIds && isset($this->priceIds[ProductPriceEntity::TYPE_STOCK])) {
            return $this->repository()->load('product_price', $this->priceIds[ProductPriceEntity::TYPE_STOCK]);
        } else {
            $prices = $this->getPrices();
            return isset($prices[ProductPriceEntity::TYPE_STOCK]) ? $prices[ProductPriceEntity::TYPE_STOCK] : null;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getCustomerPrice()
    {
        if ($this->priceIds && isset($this->priceIds[ProductPriceEntity::TYPE_CUSTOMER])) {
            return $this->repository()->load('product_price', $this->priceIds[ProductPriceEntity::TYPE_CUSTOMER]);
        } else {
            $prices = $this->getPrices();
            return isset($prices[ProductPriceEntity::TYPE_CUSTOMER]) ? $prices[ProductPriceEntity::TYPE_CUSTOMER] : null;
        }
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->uid ? $this->repository()->load('user', $this->uid) : null;
    }

    public static function type()
    {
        return 'product';
    }

    public static function table()
    {
        return 'zw_product';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'category_id', 'uid', 'code', 'title', 'subtitle', 'picture', 'inventory', 'weight',
            'production_time', 'shipping_time', 'unit_id'
        );
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return CategoryEntity | null
     */
    public function getCategory()
    {
        return $this->category_id ? $this->repository()->load('category', $this->category_id) : null;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $inventory
     */
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * @return mixed
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $production_time
     */
    public function setProductionTime($production_time)
    {
        $this->production_time = $production_time;
    }

    /**
     * @return mixed
     */
    public function getProductionTime()
    {
        return $this->production_time;
    }

    /**
     * @param mixed $shipping_time
     */
    public function setShippingTime($shipping_time)
    {
        $this->shipping_time = $shipping_time;
    }

    /**
     * @return mixed
     */
    public function getShippingTime()
    {
        return $this->shipping_time;
    }

    /**
     * @param mixed $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $unit_id
     */
    public function setUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    /**
     * @return mixed
     */
    public function getUnitId()
    {
        return $this->unit_id;
    }

    /**
     * @return TermEntity | null
     */
    public function getUnit()
    {
        return $this->unit_id ? $this->repository()->load('term', $this->unit_id) : null;
    }
}