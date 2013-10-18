<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Form;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductPriceEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;

class ProductAdminForm extends Form
{
    /**
     * @var ProductEntity
     */
    protected $product;

    /**
     * @var UserEntity
     */
    protected $user;

    protected $uid;

    protected $code;
    protected $title;
    protected $subtitle;
    protected $inventory;
    protected $weight;
    protected $production_time;
    protected $shipping_time;

    /**
     * @var ProductPriceEntity
     */
    protected $trade_price;
    /**
     * @var ProductPriceEntity
     */
    protected $taobao_price;
    /**
     * @var ProductPriceEntity
     */
    protected $stock_price;
    /**
     * @var ProductPriceEntity
     */
    protected $customer_price;

    protected $unit_id;
    protected $introduction;
    protected $description;

    public function id()
    {
        return 'product-admin';
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($form = $this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'vertical'
                )))->addVariable('#subtitle', $this->product ? ('编辑产品 #<i>'.$this->product->getId().'</i>') : '添加产品');

        $form->render('admin/form.html.yui@Zhuiwei:Product');
    }

    protected function registerValidators()
    {
        return array(
            'uid' => array(
                'required' => '请选择一个卖家'
            ),
            'code' => array(
                'required' => '产品编号不能为空'
            ),
            'title' => array(
                'required' => '产品标题不能为空'
            )
        );
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        $user = $this->context->repository()->load('user', $this->uid);
        if (!$user) {
            $feh->add('uid', '无效的卖家');
        } else {
            $this->user = $user;
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $repository = $this->context->repository();
        if (!$this->product) {
            $product = $repository->create('product');
        } else {
            $product = $this->product;
        }

        $product->set($this->toArray());
        $product->save();

        $productDetail = $product->getDetail();
        if (!$productDetail) {
            $productDetail = $repository->create('product-detail');
        }
        $productDetail->set($this->toArray());
        $productDetail->set('product_id', $product->getId());
        $productDetail->save();

        $this->trade_price->setProductId($product->getId());
        $this->trade_price->save();

        $this->taobao_price->setProductId($product->getId());
        $this->taobao_price->save();

        $this->stock_price->setProductId($product->getId());
        $this->stock_price->save();

        $this->customer_price->setProductId($product->getId());
        $this->customer_price->save();

        $this->product = $product;

        $this->context->flash()->add('success', sprintf('产品 <i>%s</i> 保存成功！', $product->getTitle()));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('product-admin')
            ));
    }

    protected function fields()
    {
        return array(
            'uid', 'code', 'title', 'subtitle', 'inventory', 'weight', 'production_time', 'shipping_time',
            'unit_id', 'introduction', 'description', 'trade_price', 'taobao_price', 'stock_price', 'customer_price'
        );
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = trim($code);
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $inventory
     */
    public function setInventory($inventory)
    {
        $this->inventory = intval($inventory);
    }

    /**
     * @return mixed
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @param ProductEntity $product
     */
    public function setProduct(ProductEntity $product)
    {
        $this->product = $product;
        $this->set($product->toArray());
        $detail = $product->getDetail();
        if ($detail) {
            $this->set($detail->toArray());
        }

        $this->trade_price = $product->getTradePrice();
        $this->taobao_price = $product->getTaobaoPrice();
        $this->stock_price = $product->getStockPrice();
        $this->customer_price = $product->getCustomerPrice();
    }

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->product;
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
        $this->subtitle = trim($subtitle);
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
        $this->title = trim($title);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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
     * @param mixed $introduction
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    }

    /**
     * @return mixed
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param mixed UserEntity
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
        $this->uid = $user->getUid();
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
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
     * @param mixed $stock_price
     */
    public function setStockPrice($stock_price)
    {
        if (is_array($stock_price)) {
            if ($this->stock_price) {
                $this->stock_price->set($stock_price);
            } else {
                $this->stock_price = $this->context->repository()->create('product-price', array_merge($stock_price, array(
                            'type' => ProductPriceEntity::TYPE_STOCK
                        )));
            }
        } else if ($stock_price instanceof ProductPriceEntity) {
            $this->stock_price = $stock_price;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getStockPrice()
    {
        return $this->stock_price;
    }

    /**
     * @param mixed $taobao_price
     */
    public function setTaobaoPrice($taobao_price)
    {
        if (is_array($taobao_price)) {
           if ($this->taobao_price) {
               $this->taobao_price->set($taobao_price);
           } else {
               $this->taobao_price = $this->context->repository()->create('product-price', array_merge($taobao_price, array(
                           'type' => ProductPriceEntity::TYPE_TAOBAO
                       )));
           }
        } else if ($taobao_price instanceof ProductPriceEntity) {
            $this->taobao_price = $taobao_price;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getTaobaoPrice()
    {
        return $this->taobao_price;
    }

    /**
     * @param mixed $trade_price
     */
    public function setTradePrice($trade_price)
    {
        if (is_array($trade_price)) {
            if ($this->trade_price) {
                $this->trade_price->set($trade_price);
            } else {
                $this->trade_price = $this->context->repository()->create('product-price', array_merge($trade_price, array(
                            'type' => ProductPriceEntity::TYPE_TRADE
                        )));
            }
        } else if ($trade_price instanceof ProductPriceEntity) {
            $this->trade_price = $trade_price;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getTradePrice()
    {
        return $this->trade_price;
    }

    /**
     * @param mixed $customer_price
     */
    public function setCustomerPrice($customer_price)
    {
        if (is_array($customer_price)) {
            if ($this->customer_price) {
                $this->customer_price->set($customer_price);
            } else {
                $this->customer_price = $this->context->repository()->create('product-price', array_merge($customer_price, array(
                            'type' => ProductPriceEntity::TYPE_CUSTOMER
                        )));
            }
        } else if ($customer_price instanceof ProductPriceEntity) {
            $this->customer_price = $customer_price;
        }
    }

    /**
     * @return ProductPriceEntity
     */
    public function getCustomerPrice()
    {
        return $this->customer_price;
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