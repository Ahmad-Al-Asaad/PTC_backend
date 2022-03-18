<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Opportunity as OpportunityResource;
use App\Models\Company;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Company as CompanyResource;
use App\Http\Controllers\API\BaseController as BaseController;

class CompanyController extends BaseController
{

    /**
     * @OA\Post(
     ** path="/api/company/addCompany",
     *   tags={"Companies"},
     *   summary="add for new Companies ",
     *   operationId="1-addCompany",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="name", type="string", example="sous"),
     *       @OA\Property(property="Email", type="string", example="kais@gmail.com"),
     *       @OA\Property(property="Location", type="string", example="Damascus/Mazaa"),
     *       @OA\Property(property="scope", type="string", example="Web Developer"),
     *       @OA\Property(property="PhoneNumber", type="number", example=0945215778),
     *       @OA\Property(property="description", type="string", example=""),
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function addCompany(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'Location' => 'required',
                'Email' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $company = new Company;
        $company->name = $request->name;
        $company->Location = $request->Location;
        $company->Email = $request->Email;
        $company->scope = isset($request->scope) ? $request->scope : null;
        $company->PhoneNumber = isset($request->PhoneNumber) ? $request->PhoneNumber : null;
        $company->description = isset($request->description) ? $request->description : null;
        $company->save();

        return $this->sendResponse(new CompanyResource($company), 'Add Company successfully.');
    }

    /**
     * @OA\delete(
     ** path="/api/company/deleteCompany/{id}",
     *   tags={"Companies"},
     *   summary="delet from Companies ",
     *   operationId="2-deleteCompany",
     *   security={{ "bearer_token":{} }},
     *   description="delet from Table Companies by idCompany",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function deleteCompany($id)
    {
        $company = Company::find($id);
        if (is_null($company)) {
            return $this->sendError('Not Found Company.');
        } else {
            $company->delete();
            return $this->sendSuccess('Company delete successfully.');
        }
    }

    /**
     * @OA\Get(
     ** path="/api/company/showAllCompany",
     *   tags={"Companies"},
     *   summary="show from Companies ",
     *   operationId="3-showAllCompany",
     *   description="Returns info spcific Companies",
     *   security={{ "bearer_token":{} }},
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *   )
     *
     **/
    public function showCompany()
    {
        $company = Company::all();

        return $this->sendResponse(CompanyResource::collection($company), 'companies retrieved successfully.');
    }

    /**
     * @OA\Get(
     ** path="/api/company/showCompanyByID/{id}",
     *   tags={"Companies"},
     *   summary="show from Companies By ID",
     *   operationId="4-showCompanyByID",
     *   description="Returns info spcific Companies",
     *   security={{ "bearer_token":{} }},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */

    public function showCompanyByID($id)
    {
        $company = Company::find($id);
        if (is_null($company))
            return $this->sendError('Not Found Company');
        return $this->sendResponse($company, 'Found it');
    }

    /**
     * @OA\put(
     ** path="/api/company/editCompany",
     *   tags={"Companies"},
     *   summary="edit for Companies ",
     *   operationId="5-editCompany",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *    @OA\RequestBody(
     *    required=false,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="name", type="string", example="sous"),
     *       @OA\Property(property="Email", type="string", example="kais@gmail.com"),
     *       @OA\Property(property="Location", type="string", example="Damascus/Mazaa"),
     *       @OA\Property(property="scope", type="string", example="Web Developer"),
     *       @OA\Property(property="PhoneNumber", type="number", example=0945215778),
     *       @OA\Property(property="description", type="string", example=""),
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function editCompany(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'Location' => 'required',
                'Email' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $company = Company::where('id', $request->id)->first();
        if (is_null($company)) {
            return $this->sendError('Not Found ');
        } else {
            $company->name = $request->name;
            $company->Location = $request->Location;
            $company->Email = $request->Email;
            $company->scope = isset($request->scope) ? $request->scope : null;
            $company->PhoneNumber = isset($request->PhoneNumber) ? $request->PhoneNumber : null;
            $company->description = isset($request->description) ? $request->description : null;
            $company->save();
            return $this->sendResponse(new CompanyResource($company), 'company edit successfully.');
        }
    }
}
