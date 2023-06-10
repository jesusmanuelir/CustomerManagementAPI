<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'dni';
    protected $fillable = ['dni', 'email', 'name', 'last_name', 'address', 'id_reg', 'id_com', 'status'];

    public $timestamps = false;
    public $incrementing = false;

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_reg');
    }

    public function commune()
    {
       return $this->belongsTo(Commune::class, 'id_com');
    }
}
