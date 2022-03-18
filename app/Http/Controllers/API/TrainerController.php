<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Trainer as TrainerResource;
use Illuminate\Support\Facades\Validator;

class TrainerController extends BaseController
{

    /**
     * @OA\Post(
     ** path="/api/trainer/add",
     *   tags={"Trainers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Trainer",
     *   operationId="1-Add Trainer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Trainer data",
     *    @OA\JsonContent(
     *       @OA\Property(property="userName", type="string", example="KAIS2"),
     *       @OA\Property(property="firstName", type="string", example="kais"),
     *       @OA\Property(property="lastName", type="string", example="Na"),
     *       @OA\Property(property="email", type="string", example="kais2@gmail.com"),
     *       @OA\Property(property="password", type="number", example=123456789),
     *       @OA\Property(property="c_password", type="number", example=123456789),
     *
     *       @OA\Property(property="age", type="number", example=22),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *      @OA\Property(property="phone", type="number", example=0999990000000),
     *      @OA\Property(property="specialization", type="string", example=" UK "),
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

    public function addTrainer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|numeric',
                'c_password' => 'required|same:password|numeric',
                'location' => 'required|string',
                'specialization' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $user = new User();
        $user->name = $request->firstName . $request->lastName;
        $user->user_name = $request->userName;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->c_password = bcrypt($request->c_password);
        $user->type = 5;
        $user->save();

        $trainer = new Trainer();
        $trainer->userID = $user->id;
        $trainer->firstName = $request->firstName;
        $trainer->lastName = $request->lastName;
        $trainer->specialization = $request->specialization;
        $trainer->location = $request->location;
        $trainer->age = isset($request['age']) ? $request['age'] : null;
        $trainer->phone = isset($request['phone']) ? $request['phone'] : null;
        $trainer->description = isset($request['description']) ? $request['description'] : null;
        $trainer->save();
        return $this->sendResponse(new TrainerResource($trainer), 'Added successfully.');

    }

    /**
     * @OA\Put (
     ** path="/api/trainer/edit",
     *   tags={"Trainers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Trainer",
     *   operationId="2-Edit Trainer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Trainer data",
     *    @OA\JsonContent(
     *       @OA\property(property="trainerID", type="number", example=1),
     *       @OA\property(property="userID", type="number", example=1),
     *       @OA\Property(property="userName", type="string", example="KAIS2"),
     *       @OA\Property(property="firstName", type="string", example="kais"),
     *       @OA\Property(property="lastName", type="string", example="Na"),
     *       @OA\Property(property="email", type="string", example="kais2@gmail.com"),
     *       @OA\Property(property="password", type="number", example=123456789),
     *       @OA\Property(property="c_password", type="number", example=123456789),
     *
     *       @OA\Property(property="age", type="number", example=22),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *      @OA\Property(property="phone", type="number", example=0999990000000),
     *      @OA\Property(property="specialization", type="string", example=" UK "),
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

    public function editTrainer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'trainerID' => 'required|numeric',
                'userID' => 'required|numeric',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|string',
                'location' => 'required|string',
                'specialization' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $trainer = Trainer::where('id', $request->trainerID)->first();
        if (is_null($trainer)) {
            return $this->sendError('Trainer Not Found');
        }
        $user = User::where('id', $request->userID)->first();
        if (is_null($user)) {
            return $this->sendError('UserID Dose\'nt exist');
        }

        $user->name = $request->firstName . $request->lastName;
        $user->user_name = $request->userName;
        $user->email = $request->email;
        $user->save();

        $trainer->userID = $request->userID;
        $trainer->firstName = $request->firstName;
        $trainer->lastName = $request->lastName;
        $trainer->specialization = $request->specialization;
        $trainer->location = $request->location;
        $trainer->age = isset($request['age']) ? $request['age'] : null;
        $trainer->phone = isset($request['phone']) ? $request['phone'] : null;
        $trainer->description = isset($request['description']) ? $request['description'] : null;
        $trainer->save();
        return $this->sendResponse(new TrainerResource($trainer), 'Edit successfully.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/trainer/delete/{id}",
     *   tags={"Trainers"},
     *   summary="Delete from Trainers ",
     *   operationId="3-Delete from Trainers",
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

    public function deleteTrainer($id)
    {
        $trainer = Trainer::find($id);
        if (is_null($trainer)) {
            return $this->sendError('Trainer deleted Unsuccessfully.');
        } else {
            $trainer->delete();
            return $this->sendSuccess('Trainer deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/trainer/showByID/{id}",
     *   tags={"Trainers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from Trainers ",
     *   operationId="4-show from Trainers",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     * description="Returns info  from Trainer",
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

    public function showTrainerByID($id)
    {
        $trainer = Trainer::find($id);
        if (is_null($trainer))
            return $this->sendError(' Trainer Not Found ');
        return $this->sendResponse($trainer, 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/trainer/all",
     *      operationId="5-Get A List Of Trainers",
     *      tags={"Trainers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Trainers",
     *      description="Returns A List Of Trainers",
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
    public function getAllTrainer()
    {
        $trainer = Trainer::all();
        return $this->sendResponse(TrainerResource::collection($trainer), 'Trainers retrieved successfully.');

    }

}
