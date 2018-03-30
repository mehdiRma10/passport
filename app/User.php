<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $customer_id;
    public $customer_group_id;
    protected $store_id;
    public $firstname;
    public $lastname;
    public $email;
    public $telephone;
    public $fax;
    protected $password;
    protected $salt;
    public $cart;
    public $wishlist;
    public $newsletter;
    public $address_id;
    public $status;
    public $safe;
    protected $token;
    public $date_added;

    public function __construct($base_array)
    {
        $this->customer_id       = $base_array['customer_id'];
        $this->customer_group_id = $base_array['customer_group_id'];
        $this->store_id          = $base_array['store_id'];
        $this->firstname         = $base_array['firstname'];
        $this->lastname          = $base_array['lastname'];
        $this->email             = $base_array['email'];
        $this->telephone         = $base_array['telephone'];
        $this->fax               = $base_array['fax'];
        $this->password          = $base_array['password'];
        $this->salt              = $base_array['salt'];
        $this->cart              = $base_array['cart'];
        $this->wishlist          = $base_array['wishlist'];
        $this->newsletter        = $base_array['newsletter'];
        $this->address_id        = $base_array['address_id'];
        $this->status            = $base_array['status'];
        $this->safe              = $base_array['safe'];
        $this->token             = $base_array['token'];
        $this->date_added        = $base_array['date_added'];
    }
}
