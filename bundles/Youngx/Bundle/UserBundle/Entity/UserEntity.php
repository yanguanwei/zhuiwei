<?php

namespace Youngx\Bundle\UserBundle\Entity;

use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\Database\Entity;
use Youngx\Database\Query;
use Youngx\MVC\User\UserEntityInterface;

class UserEntity extends Entity implements UserEntityInterface
{
    const ROLE_SELLER = 4;
    const ROLE_BUYER = 5;

    protected $uid;
    protected $name;
    protected $email;
    protected $password;
    protected $created_at = 0;
    protected $phone;
    protected $status = 0;
    protected $roles;

    /**
     * @return RoleEntity[]
     */
    public function getRoles()
    {
        if (!is_array($this->roles)) {
            $roles = array();
            if ($this->roles) {
                $repository = $this->repository();
                foreach (explode(',', $this->roles) as $roleId) {
                    $roles[$roleId] = $repository->load('role', $roleId);
                }
            }
            $this->roles = $roles;
        }
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = implode(',', $roles);

        return $this;
    }

    public function hasRole($role)
    {
        $roles = $this->getRoles();
        return isset($roles[$role]);
    }

    /**
     * @return UserProfileEntity
     */
    public function getProfile()
    {
        return $this->repository()->load('user-profile', $this->getUid());
    }

    public function getId()
    {
        return $this->uid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPassword($password)
    {
        $this->password = $this->encryptPassword($password);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function updatePassword($password)
    {
        return $this->repository()->getConnection()->update($this->table(), array(
                'password' => $this->encryptPassword($password)
            ), 'uid=:uid', array(':uid' => $this->uid));
    }

    public function encryptPassword($password)
    {
        return md5($password);
    }

    public static function type()
    {
        return 'user';
    }

    public static function table()
    {
        return 'y_user';
    }

    public static function primaryKey()
    {
        return 'uid';
    }

    public static function fields()
    {
        return array(
            'uid', 'name', 'email', 'password', 'created_at', 'status', 'roles',
        );
    }

    public static function findByName(Query $query, $name)
    {
        return $query->where(array(
                'name=?' => $name
            ))->one();
    }
}
