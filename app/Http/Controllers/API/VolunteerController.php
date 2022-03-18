<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\JsonResource as VolunteerResource;
use Illuminate\Support\Facades\Validator;

class VolunteerController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/volunteer/add",
     *   tags={"Volunteers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Volunteer",
     *   operationId="1-Add Volunteer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Volunteer data",
     *    @OA\JsonContent(
     *       @OA\Property(property="user_name", type="string", example="KAIS3"),
     *       @OA\Property(property="firstName", type="string", example="kais"),
     *       @OA\Property(property="lastName", type="string", example="Na"),
     *       @OA\Property(property="email", type="string", example="kais3@gmail.com"),
     *       @OA\Property(property="password", type="number", example=123456789),
     *       @OA\Property(property="c_password", type="number", example=123456789),
     *
     *      @OA\Property(property="age", type="number", example=22),
     *       @OA\Property(property="volunteerTitle", type="string", example="Java"),
     *      @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="specialization", type="string", example="Java"),
     *       @OA\Property(property="section", type="string", example="Java"),
     *      @OA\Property(property="phone", type="number", example=0938954497),
     *      @OA\Property(property="college", type="string", example="USA"),
     *      @OA\Property(property="description", type="string", example="   "),
     *
     *    ),
     * ),
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

    public function addVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_name' => 'required|string|unique:users',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'age' => 'required|numeric',
                'volunteerTitle' => 'required|string',
                'specialization' => 'required|string',
                'section' => 'required|string',
                'email' => 'required|unique:users',
                'password' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $user = new User();
        $user->name = $request->firstName . $request->lastName;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->c_password = bcrypt($request->c_password);
        $user->type = 3;
        $user->save();

        $volunteer = new Volunteer();
        $volunteer->userID = $user->id;
        $volunteer->firstName = $request->firstName;
        $volunteer->lastName = $request->lastName;
        $volunteer->age = $request->age;
        $volunteer->volunteerTitle = $request->volunteerTitle;
        $volunteer->specialization = $request->specialization;
        $volunteer->section = $request->section;
        $volunteer->location = isset($request['location']) ? $request['location'] : null;
        $volunteer->phone = isset($request['phone']) ? $request['phone'] : null;
        $volunteer->college = isset($request['college']) ? $request['college'] : null;
        $volunteer->description = isset($request['description']) ? $request['description'] : null;
        $volunteer->save();
        return $this->sendResponse(new VolunteerResource($volunteer), 'Volunteer Added successfully.');

    }

    /**
     * @OA\Put (
     ** path="/api/volunteer/edit",
     *   tags={"Volunteers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Volunteer",
     *   operationId="2-Edit Volunteer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Volunteer data",
     *    @OA\JsonContent(
     *      @OA\property(property="volunteerID", type="number", example=1),
     *      @OA\property(property="userID", type="number", example=1),
     *       @OA\Property(property="userName", type="string", example="KAIs3"),
     *       @OA\Property(property="firstName", type="string", example="kais"),
     *       @OA\Property(property="lastName", type="string", example="Na"),
     *       @OA\Property(property="email", type="string", example="kais3@gmail.com"),
     *       @OA\Property(property="password", type="number", example=123456789),
     *       @OA\Property(property="c_password", type="number", example=123456789),
     *
     *      @OA\Property(property="age", type="number", example=22),
     *       @OA\Property(property="volunteerTitle", type="string", example="Java"),
     *      @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="specialization", type="string", example="Java"),
     *       @OA\Property(property="section", type="string", example="Java"),
     *      @OA\Property(property="phone", type="number", example=0938954497),
     *      @OA\Property(property="college", type="string", example="USA"),
     *      @OA\Property(property="description", type="string", example="   "),
     *
     *    ),
     * ),
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

    public function editVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'volunteerID' => 'required|numeric',
                'userID' => 'required|numeric',
                'userName' => 'required|string',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'age' => 'required|numeric',
                'volunteerTitle' => 'required|string',
                'specialization' => 'required|string',
                'section' => 'required|string',
                'email' => 'required|unique:users',
                'password' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $volunteer = Volunteer::where('id', $request->volunteerID)->first();
        if (is_null($volunteer)) {
            return $this->sendError('Volunteer Not Found');
        }
        $user = User::where('id', $request->userID)->first();
        if (is_null($user)) {
            return $this->sendError('UserID Dose\'nt exist');
        }

        $user->name = $request->firstName . $request->lastName;
        $user->user_name = $request->userName;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->c_password = $request->c_password;
        $user->save();

        $volunteer->firstName = $request->firstName;
        $volunteer->lastName = $request->lastName;
        $volunteer->age = $request->age;
        $volunteer->specialization = $request->specialization;
        $volunteer->section = $request->section;
        $volunteer->volunteerTitle = $request->volunteerTitle;
        $volunteer->location = isset($input['location']) ? $request['location'] : null;
        $volunteer->phone = isset($input['phone']) ? $request['phone'] : null;
        $volunteer->college = isset($input['college']) ? $request['college'] : null;
        $volunteer->description = isset($input['description']) ? $request['description'] : null;
        $volunteer->save();
        return $this->sendResponse([new VolunteerResource($volunteer)], 'Edit Volunteer successfully.');
    }

    /**
     * @OA\Delete  (
     ** path="/api/volunteer/delete/{id}",
     *   tags={"Volunteers"},
     *   summary="Delete  Volunteer ",
     *   operationId="3-Delete Volunteer",
     *security={
     *  {"bearer_token":{}},
     *   },
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
    public function deleteVolunteer($id)
    {
        $volunteer = Volunteer::find($id);
        if (is_null($volunteer)) {
            return $this->sendError('Volunteer deleted Unsuccessfully.');
        } else {
            $volunteer->delete();
            return $this->sendSuccess('Volunteer deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/volunteer/showByID/{id}",
     *   tags={"Volunteers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show Volunteer By ID ",
     *   operationId="4-Show Event From Volunteer By ID",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     * description="Returns info spcific event",
     * *   @OA\Response(
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
    public function showVolunteerByID($id)
    {
        $volunteer = Volunteer::find($id);
        if (is_null($volunteer))
            return $this->sendError('Volunteer Not Found ');
        return $this->sendResponse($volunteer, 'We Found it.');

    }

    /**
     * @OA\Get(
     *      path="/api/volunteer/all",
     *      operationId="5-Get A List Of Volunteer",
     *      tags={"Volunteers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Volunteer",
     *      description="Returns A List Of Volunteer",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    public function getAllVolunteers()
    {
        $volunteer = Volunteer::all();
        return $this->sendResponse(VolunteerResource::collection($volunteer), 'Volunteer retrieved successfully.');

    }
}
