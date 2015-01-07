<?php

namespace app\models;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $sa;
    public $admin;
    public $ctime;
    public $authKey;
    public $accessToken;

    public static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $query = DcmdUser::findOne($id);
        if($query) {
          $user = array();
          $user['id'] = $id;
          $user['username'] = $query['username'];
          $user['password'] = $query['passwd'];
          $user['sa'] = $query['sa'];
          $user['admin'] = $query['admin'];
          $user['ctime'] = $query['ctime'];
          $user['authKey'] = $query['passwd'];
          $user['accessToken'] = $query['passwd'];
          return new static($user);
        } 
        return NULL;
echo "iden===";exit;
        return   isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $query = DcmdUser::fineOne(['passwd'=>$token]);
        if($query) {
           $user = array();
           $user['id'] = $query['uid'];
           $user['username'] = $query['username'];
           $user['password'] = $query['passwd'];
           $user['sa'] = $query['sa'];
           $user['admin'] = $query['admin'];
           $user['ctime'] = $query['ctime'];
           $user['authKey'] = $query['passwd'];
           $user['accessToken'] = $query['passwd'];
           return new static($user);
        } 
        return NULL;
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
         $query = DcmdUser::findOne(['username' => $username]);
         if ($query) {
            $user = array();
            $user['id'] = $query['uid'];
            $user['username'] = $username;
            $user['password'] = $query['passwd'];
            $user['sa'] = $query['sa'];
            $user['admin'] = $query['admin'];
            $user['ctime'] = $query['ctime'];
            $user['authKey'] = $query['passwd'];
            $user['accessToken'] = $query['passwd'];
            return new static($user);
         }
        return NULL;
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
 
               return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
       return $this->password == md5($password+$this->username+$this->ctime);
    }
}
