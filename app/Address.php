<?php

namespace App;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Validator;

class Address
{
    public $address_id;
    public $customer_id;
    public $firstname;
    public $lastname;
    public $company;
    public $address_1;
    public $address_2;
    public $city;
    public $postcode;
    public $country_id;
    public $zone_id;
    public $custom_field;

    private $rules = [
        "firstname"    => 'required|string|max:32',
        "lastname"     => 'required|string|max:32',
        "company"      => 'nullable|string|max:32',
        "address_1"    => 'required|string|max:96',
        "address_2"    => 'nullable|string|max:96',
        "city"         => 'required|string|max:32',
        "postcode"     => 'required|max:10',
        "country_id"   => 'required|integer',
        "zone_id"      => 'required|integer',
        "custom_field" => 'nullable|string|max:96',
    ];

    public function __construct($data)
    {
        $this->firstname    = $data['firstname'];
        $this->lastname     = $data['lastname'];
        $this->company      = $data['company'];
        $this->address_1    = $data['address_1'];
        $this->address_2    = $data['address_2'];
        $this->city         = $data['city'];
        $this->postcode     = $data['postcode'];
        $this->country_id   = $data['country_id'];
        $this->zone_id      = $data['zone_id'];
        $this->custom_field = $data['custom_field'];
    }

    public function setCustomerId($id)
    {
        $this->customer_id = (int) $id;
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
            'address_id' => $this->address_id,
            'customer_id' => $this->customer_id,
            'firstname'    => $this->firstname,
            'lastname'     => $this->lastname,
            'company'      => $this->company,
            'address_1'    => $this->address_1,
            'address_2'    => $this->address_2,
            'city'         => $this->city,
            'postcode'     => $this->postcode,
            'country_id'   => $this->country_id,
            'zone_id'      => $this->zone_id,
            'custom_field' => $this->custom_field,
        ];
    }

    public function save()
    {
        try
        {
            $this->address_id =DB::table('oc_address')->insertGetId([
                'customer_id' => $this->customer_id,
                'firstname'    => $this->firstname,
                'lastname'     => $this->lastname,
                'company'      => $this->company,
                'address_1'    => $this->address_1,
                'address_2'    => $this->address_2,
                'city'         => $this->city,
                'postcode'     => $this->postcode,
                'country_id'   => $this->country_id,
                'zone_id'      => $this->zone_id,
                'custom_field' => $this->custom_field,
            ]);

            return true;

        } catch (QueryException $e) {
            return false;
        }
    }
}
