<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExhibitionLoginRequest as ExhibitionLoginRequestResource;
use App\Models\Exhibition;
use App\Models\ExhibitionAnswer;
use App\Models\ExhibitionLoginRequest;
use App\Models\ExhibitionQuestion;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExhibitionLoginRequestController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/ExhibitionLoginRequest/add",
     *   tags={"ExhibitionLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add ExhibitionLoginRequest",
     *   operationId="1-Add ExhibitionLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ExhibitionLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="exhibitionID", type="number", example=1),
     *       @OA\Property(property="state", type="number", example=1),
     *      @OA\Property(
     *                  property="Answers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="questionID", type="number", example=1),
     *                      @OA\Property(property="answer", type="string", example=1),
     *                  ),
     *       ),
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

    public function addExhibitionLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'exhibitionID' => 'required|numeric',
                'state' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $studentId = Student::where('userID', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $exhibitionID = Exhibition::where('id', $request->exhibitionID)->first();
        if (is_null($exhibitionID)) {
            return $this->sendError('exhibitionID Not Found');
        }
        $exhibitionLoginRequest = ExhibitionLoginRequest::where('exhibitionsID', $request->exhibitionID)->where('studentID', $studentId->id)->first();
        if ($exhibitionLoginRequest) {
            return $this->sendError('Cant login request in exhibition you are already exist');
        }
        $loginRequest = new ExhibitionLoginRequest();
        $loginRequest->studentID = $studentId->id;
        $loginRequest->exhibitionsID = $request->exhibitionID;
        $loginRequest->state = $request->state;
        $loginRequest->save();

        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = new ExhibitionAnswer();
                $questionid = ExhibitionQuestion::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $studentId->id;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }

        return $this->sendResponse(new ExhibitionLoginRequestResource($loginRequest), 'LoginRequest Added successfully.');

    }

    /**
     * @OA\Put(
     ** path="/api/ExhibitionLoginRequest/edit",
     *   tags={"ExhibitionLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit ExhibitionLoginRequest",
     *   operationId="2-Edit ExhibitionLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ExhibitionLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="exhibitionID", type="number", example=1),
     *       @OA\Property(property="state", type="number", example=1),
     *       @OA\Property(
     *                type="array" ,
     *                property="Answers",
     *                @OA\Items(
     *                @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="questionID", type="number", example=1),
     *                      @OA\Property(property="answer", type="string", example=1),
     *                ),
     *       ),
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

    public function editExhibitionLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'exhibitionID' => 'required|numeric',
                'state' => 'required|numeric',

            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $loginRequest = ExhibitionLoginRequest::where('id', $request->id)->first();
        if (is_null($loginRequest)) {
            return $this->sendError('LoginRequest Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $exhibitionID = Exhibition::where('id', $request->exhibitionID)->first();
        if (is_null($exhibitionID)) {
            return $this->sendError('exhibitionID Not Found');
        }
        $loginRequest = new ExhibitionLoginRequest();
        $loginRequest->studentID = $request->studentID;
        $loginRequest->exhibitionsID = $request->exhibitionID;
        $loginRequest->state = $request->state;
        $loginRequest->save();

        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = ExhibitionAnswer::where('id', $value['id'])->first();
                if (is_null($answers)) {
                    return $this->sendError('answers Not Found');
                }
                $questionid = ExhibitionQuestion::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $request->studentID;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }

        return $this->sendResponse(new ExhibitionLoginRequestResource($loginRequest), 'LoginRequest Edit successfully.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/ExhibitionLoginRequest/delete/{id}",
     *   tags={"ExhibitionLoginRequest"},
     *   summary="Delete from ExhibitionLoginRequest ",
     *   operationId="3-Delete from ExhibitionLoginRequest",
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

    public function deleteExhibitionLoginRequest($id)
    {
        $loginRequest = ExhibitionLoginRequest::find($id);
        if (is_null($loginRequest)) {
            return $this->sendError('deleted Unsuccessfully.');
        } else {
            $loginRequest->delete();
            return $this->sendSuccess('not Found Deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/ExhibitionLoginRequest/showAcceptedLogs/{exhibitionID}",
     *   tags={"ExhibitionLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show Accepted LoginRequests from LoginRequest ",
     *   operationId="4-show Accepted LoginRequests from LoginRequest",
     *
     * description="Returns info Accepted LoginRequests",
     *     @OA\Parameter(
     *     name="exhibitionID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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

    public function showAcceptedLoginRequest($exhibitionID)
    {
        $state = 3;
        $loginRequest = ExhibitionLoginRequest::where('state', $state)->where('exhibitionsID', $exhibitionID)->get();

        return $this->sendResponse(ExhibitionLoginRequestResource::collection($loginRequest), 'ExhibitionLoginRequest retrieved successfully.');
    }


    /**
     * @OA\Get (
     ** path="/api/ExhibitionLoginRequest/showRejectedLogs",
     *   tags={"ExhibitionLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from  Rejected LoginRequest ",
     *   operationId="5-show Rejected LoginRequests from LoginRequest",
     *
     * description="Returns Rejected LoginRequest",
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

    public function showRejectedLoginRequest()
    {
        $state = 2;
        $loginRequest = ExhibitionLoginRequest::where('state', $state)->get();

        return $this->sendResponse(ExhibitionLoginRequestResource::collection($loginRequest), 'ExhibitionLoginRequest retrieved successfully.');
    }


    /**
     * @OA\Get(
     *      path="/api/ExhibitionLoginRequest/all",
     *      operationId="6-Get A List Of LoginRequest",
     *      tags={"ExhibitionLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of ExhibitionLoginRequest",
     *      description="Returns A List Of ExhibitionLoginRequest",
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

    public function getAllExhibitionLoginRequest()
    {
        $loginRequest = ExhibitionLoginRequest::all();
        return $this->sendResponse(ExhibitionLoginRequestResource::collection($loginRequest), 'ExhibitionLoginRequest retrieved successfully.');

    }


    /**
     * @OA\Put   (
     ** path="/api/ExhibitionLoginRequest/acceptLoginRequest/{studentID}/{exhibitionID}",
     *   tags={"ExhibitionLoginRequest"},
     *   summary="Accept ExhibitionLoginRequestResource for specific Student",
     *   operationId="7-Accept ExhibitionLoginRequest for specific Student",
     *security={
     *  {"bearer_token":{}},
     *   },
     *
     *   @OA\Parameter(
     *     name="studentID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *     @OA\Parameter(
     *     name="exhibitionID",
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
    public function acceptExhibitionLoginRequest($studentID, $exhibitionID)
    {
        $loginRequest = ExhibitionLoginRequest::where('StudentID', $studentID)->where('exhibitionsID', $exhibitionID)->first();

        if (is_null($loginRequest))
            return $this->sendError('ExhibitionLoginRequest Not Found');
        else {
            $loginRequest->state = 3;
            $loginRequest->save();
            return $this->sendResponse(new ExhibitionLoginRequestResource($loginRequest), 'ExhibitionLoginRequest Has been Accepted.');
        }
    }


    /**
     * @OA\Put   (
     ** path="/api/ExhibitionLoginRequest/rejectedLoginRequest/{studentID}/{exhibitionID}",
     *   tags={"ExhibitionLoginRequest"},
     *   summary=" Reject ExhibitionLoginRequest for specific Student",
     *   operationId="7- Reject ExhibitionLoginRequest for specific Student",
     *security={
     *  {"bearer_token":{}},
     *   },
     *
     *   @OA\Parameter(
     *     name="studentID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ), @OA\Parameter(
     *     name="exhibitionID",
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
    public function rejectExhibitionLoginRequest($student, $exhibitionID)
    {
        $loginRequest = ExhibitionLoginRequest::where('StudentID', $student)->where('exhibitionsID', $exhibitionID)->first();
        if (is_null($loginRequest))
            return $this->sendError('ExhibitionLoginRequest Not Found');
        else {
            $loginRequest->state = 2;
            $loginRequest->save();
            return $this->sendResponse(new ExhibitionLoginRequestResource($loginRequest), 'ExhibitionLoginRequest Has been Rejected.');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/ExhibitionLoginRequest/showExhibitionByID/{exhibitionID}",
     *      operationId="4-Show exhibitionLoginRequests By exhibitionID",
     *      tags={"ExhibitionLoginRequest"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get exhibitionLoginRequests By ID Exhibitions ",
     *      description="Returns info spcific exhibitionLoginRequests",
     *     @OA\Parameter(
     *     name="exhibitionID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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
    public function getAllLoginRequestsByExhibitionID($exhibitionID)
    {
        $loginRequests = ExhibitionLoginRequest::where('exhibitionsID', $exhibitionID)->get();

        return $this->sendResponse(ExhibitionLoginRequestResource::collection($loginRequests), 'exhibition LoginRequests Has been Rejected.');

    }



    /**
     * @OA\Get(
     *      path="/api/ExhibitionLoginRequest/showStudentByID/{studentID}",
     *      operationId="4-Show exhibitionLoginRequests By studentID",
     *      tags={"ExhibitionLoginRequest"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get ExhibitionLoginRequests By ID studentID ",
     *      description="Returns info spcific ExhibitionLoginRequests",
     *     @OA\Parameter(
     *     name="studentID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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
    public function getAllLoginRequestsByStudentID($studentID)
    {
        $studentId = Student::where('userID', $studentID)->first();

        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }

        $loginRequests = ExhibitionLoginRequest::where('studentID', $studentId->id)->get();

        return $this->sendResponse(ExhibitionLoginRequestResource::collection($loginRequests), 'ExhibitionLoginRequests Has been Rejected.');

    }
}
