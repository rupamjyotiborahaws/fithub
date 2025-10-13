<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $fillable = ['name', 'email', 'address', 'phone_no', 'date_of_icorporation','contact_person', 'gst_id', 'business_type', 'status'];
}
