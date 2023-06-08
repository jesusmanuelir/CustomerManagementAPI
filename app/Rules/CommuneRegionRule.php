<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Commune;
use App\Models\Region;

class CommuneRegionRule implements Rule
{
    public function passes($attribute, $value)
    {
        $commune = Commune::find($value);

        if (!$commune) {
            return false;
        }

        $id_reg = request()->input('id_reg');
        $region = Region::find($id_reg);

       if (!$region) {
           return false;
       }
       $isCommuneRegionRelated = $commune->id_reg == $region->id_reg && (int)$value === (int)$commune->id_com;
       $isCommunesAndRegionsActive = $region->status == 'A' && $commune->status == 'A';

      return $isCommuneRegionRelated && $isCommunesAndRegionsActive;
   }

   public function message()
   {
       return 'La comuna y región proporcionadas no están relacionadas o no existen.';
   }
}
