<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Rules\CommuneRegionRule;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "dni" => ["required", "unique:customers,dni"],
                "email" => ["required", "email", "unique:customers,email"],
                "name" => ["required"],
                "last_name" => ["required"],
                "address" => ["nullable"],
                "id_reg" => "required|exists:regions,id_reg",
                "id_com" => ["required", new CommuneRegionRule()],
            ]);

            $customer = new Customer($validatedData);
            $customer->date_reg = Carbon::now();


        if ($customer->save()) {

            $customer->load("region","commune");

            return response()->json([
                "success" => true,
                "data" => [
                    "name" => $customer->name,
                    "last_name" => $customer->last_name,
                    "address" => !empty($customer->address) ? $customer->address : null,
                    "region" => $customer->region->description,
                    "commune" => $customer->commune->description,
                ],
            ], 201);
        }
        } catch (ValidationException$e) {

            return response()->json([
                "success" => false,
                "message"  => $e->validator->getMessageBag()
            ], 422);

        }	catch(\Exception$e) {

           return response()->json([
            "success" => false,
            "message"=> "Error interno del servidor: {$e->getMessage()}"
        ], 500);

       }
    }

    public function search(Request $request)
    {
        try {
            $dni = $request->input("dni");
            $email = $request->input("email");

            if (!$dni && !$email) {
                return response()->json([
                    "success" => false,
                    "message" => "Debes proporcionar al menos uno de los siguientes parámetros: dni o correo"
                ], 400);
            }

            $customerQuery = Customer::query();
            $customerQuery->where("status", "=", "A");

            $customerQuery->where(function ($query) use ($dni, $email) {
                if ($dni) {
                    $query->orWhere("dni", "=", trim($dni));
                }

                if ($email) {
                   $query->orWhere("email", "=", trim($email));
                }
            });


               $customer = $customerQuery->first();

               if (!$customer){
                   return response()->json([
                       "success" => false,
                       "message" => "No se encontró ningún cliente con el dni/email proporcionado."
                   ], 404);
            }

               $customer->load("region", "commune");

            return response()->json([
              "success" => true,
              "data" => [
                  "name" => ucfirst(strtolower(trim($customer->name))),
                  "last_name"=>ucfirst(strtolower(trim($customer->last_name))),
                  "address"=>ucfirst(strtolower(trim($customer->address))),
                  "region"=> optional($customer->region)->description,
                  "commune"=>optional($customer->commune)->description
               ],
           ],200);

        } catch (\Exception$e) {

             return response()->json([
                "success" => false,
                "message"=> "Error interno del servidor: {$e->getMessage()}"
            ], 500);
        }
    }

    public function softDelete($dni)
    {
        try {

            $customer = Customer::where("dni", $dni)->first();

            if (!$customer || $customer->status === "trash") {
                return response()->json([
                    "success" => false,
                    "message" => "Registro no existe"
                ], 404);
            }

            $customer->status = "trash";

               if ($customer->save()) { // Esta línea ha sido corregida
                   return response()->json([
                       "success"=>true,
                       "message"=> "Cliente eliminado correctamente"
                   ],200);
            } else {
                   return response()->json([
                      "success"=>false,
                      "message"=>"Error al eliminar al cliente"
               ],500);
           }
       }	catch(\Exception$e) {

           return response()->json([
                "success" => false,
                "message"=> "{$e->getMessage()}"
          ], 500);

       }
    }
}
