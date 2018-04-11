<?php

namespace App;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Validator;

class Customer
{
    public $customer_id;
    public $customer_group_id;
    public $firstname;
    public $lastname;
    public $email;
    public $telephone;
    public $fax;
    protected $password;
    protected $salt;
    public $address_id;
    public $ip;
    public $status;
    public $approved;
    public $safe;
    public $token;
    public $date_added;

    private $rules = [
        'customer_group_id' => 'required|integer',
        'firstname'         => 'required|max:32',
        'lastname'          => 'required|max:32',
        'email'             => 'required|email|max:96',
        'telephone'         => 'required|string|max:32',
        'fax'               => 'nullable|string|max:32',
        'password'          => 'required|string|max:40',
        'salt'              => 'nullable|string',
        'ip'                => "nullable|ip",
        'status'            => 'required|boolean',
        'approved'          => 'required|boolean',
        'safe'              => 'required|boolean',
        'date_added'        => 'required|date'
    ];

    public function __construct($data)
    {
        $this->customer_group_id = $data['customer_group_id'];
        $this->firstname         = $data['firstname'];
        $this->lastname          = $data['lastname'];
        $this->email             = $data['email'];
        $this->telephone         = $data['telephone'];
        $this->fax               = $data['fax'];
        $this->password          = $data['password'];
        $this->salt              = $data['salt'];
        $this->ip                = $data['ip'];
        $this->status            = $data['status'];
        $this->approved          = $data['approved'];
        $this->safe              = $data['safe'];
        $this->date_added        = $data['date_added'];
        
        if (isset($data['address_id'], $data['token'], $data['customer_id'])) {
            $this->customer_id   = $data['customer_id'];
            $this->address_id    = $data['address_id'];
            $this->token         = $data['token'];
        } else {
            $this->address_id    = 0;
            $this->token         = str_random(32);
        }
    }

    static public function load($id)
    {
        
        try {
            $customer = DB::table('oc_customer')->where('customer_id', $id)->first();
            
        } catch (QueryException $e) {
            return false;
        }
        
        if (empty($customer)) {
            return false;
        }

        return new Customer((array) $customer);
    }

    public function validateAll()
    {
        $validator = Validator::make($this->toArray(), $this->rules);

        if ($validator->fails()) {
            return $validator->failed();
        }

        return true;
    }

    public function toArray()
    {
        return [
            'customer_group_id' => $this->customer_group_id,
            'firstname'         => $this->firstname,
            'lastname'          => $this->lastname,
            'email'             => $this->email,
            'telephone'         => $this->telephone,
            'fax'               => $this->fax,
            'password'          => $this->password,
            'salt'              => $this->salt,
            'address_id'        => $this->address_id,
            'ip'                => $this->ip,
            'status'            => $this->status,
            'approved'          => $this->approved,
            'safe'              => $this->safe,
            'token'             => $this->token,
            'date_added'        => $this->date_added
        ];
    }

    public function add()
    {
        try
        {
            $this->customer_id = DB::table('oc_customer')->insertGetId([
                'customer_group_id' => $this->customer_group_id,
                'firstname'         => $this->firstname,
                'lastname'          => $this->lastname,
                'lastname'          => $this->lastname,
                'email'             => $this->email,
                'telephone'         => $this->telephone,
                'fax'               => $this->fax,
                'password'          => $this->password,
                'salt'              => $this->salt,
                'address_id'        => $this->address_id,
                'ip'                => $this->ip,
                'status'            => $this->status,
                'approved'          => $this->approved,
                'safe'              => $this->safe,
                'token'             => $this->token,
                'date_added'        => $this->date_added
            ]);

            return true;

        } catch (QueryException $e) {
            return false;
        }
    }

    public function updateAddressId($id)
    {
        $this->address_id = $id;

        DB::table('oc_customer')
            ->where('customer_id', $this->customer_id)
            ->update(['address_id' => $this->address_id]);
    }
}
