<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OpportunityAnswer;
use App\Models\OpportunityLoginRequest;
use App\Models\Opportunity;
use App\Models\OpportunityQuestion;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\OpportunityLoginRequest as OpportunityLoginRequestResource;
use Illuminate\Support\Facades\Validator;

class OpportunityLoginRequestController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/OpportunityLoginRequest/add",
     *   tags={"OpportunityLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add OpportunityLoginRequest",
     *   operationId="1-Add OpportunityLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass OpportunityLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="opportunityID", type="number", example=1),
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

    public function addOpportunityLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'opportunityID' => 'required|numeric',
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
        $opportunityID = Opportunity::where('id', $request->opportunityID)->first();
        if (is_null($opportunityID)) {
            return $this->sendError('opportunityID Not Found');
        }
        $OpportunityLoginRequest = OpportunityLoginRequest::where('opportunityID', $request->opportunityID)->where('studentID', $studentId->id)->first();
        if ($OpportunityLoginRequest) {
            return $this->sendError('Cant login request in opportunity  you are already exist');
        }
        $loginRequest = new OpportunityLoginRequest();
        $loginRequest->studentID = $studentId->id;
        $loginRequest->opportunityID = $request->opportunityID;
        $loginRequest->state = $request->state;
        $loginRequest->save();

        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = new OpportunityAnswer();
                $questionid = OpportunityQuestion::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $studentId->id;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }

        return $this->sendResponse(new OpportunityLoginRequestResource($loginRequest), 'LoginRequest Added successfully.');

    }

    /**
     * @OA\Put(
     ** path="/api/OpportunityLoginRequest/edit",
     *   tags={"OpportunityLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit opportunityLoginRequest",
     *   operationId="2-Edit opportunityLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass opportunityLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="opportunityID", type="number", example=1),
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

    public function editOpportunityLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'opportunityID' => 'required|numeric',
                'state' => 'required|numeric',

            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $loginRequest = OpportunityLoginRequest::where('id', $request->id)->first();
        if (is_null($loginRequest)) {
            return $this->sendError('LoginRequest Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $opportunityID = Opportunity::where('id', $request->opportunityID)->first();
        if (is_null($opportunityID)) {
            return $this->sendError('opportunityID Not Found');
        }
        $loginRequest = new OpportunityLoginRequest();
        $loginRequest->studentID = $request->studentID;
        $loginRequest->opportunityID = $request->opportunityID;
        $loginRequest->state = $request->state;
        $loginRequest->save();

        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = OpportunityAnswer::where('id', $value['id'])->first();
                if (is_null($answers)) {
                    return $this->sendError('answers Not Found');
                }
                $questionid = OpportunityQuestion::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $request->studentID;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }

        return $this->sendResponse(new OpportunityLoginRequestResource($loginRequest), 'LoginRequest Edit successfully.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/OpportunityLoginRequest/delete/{id}",
     *   tags={"OpportunityLoginRequest"},
     *   summary="Delete from opportunityLoginRequest ",
     *   operationId="3-Delete from opportunityLoginRequest",
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

    public function deleteOpportunityLoginRequest($id)
    {
        $loginRequest = OpportunityLoginRequest::find($id);
        if (is_null($loginRequest)) {
            return $this->sendError('deleted Unsuccessfully.');
        } else {
            $loginRequest->delete();
            return $this->sendSuccess('not Found Deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/OpportunityLoginRequest/showAcceptedLogs/{opportunityID}",
     *   tags={"OpportunityLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show Accepted LoginRequests from LoginRequest ",
     *   operationId="4-show Accepted LoginRequests from LoginRequest",
     *
     * description="Returns info Accepted LoginRequests",
     *     @OA\Parameter(
     *     name="opportunityID",
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

    public function showAcceptedLoginRequest($opportunityID)
    {
        $state = 3;
        $loginRequest = OpportunityLoginRequest::where('state', $state)->where('opportunityID', $opportunityID)->get();

        return $this->sendResponse(OpportunityLoginRequestResource::collection($loginRequest), 'OpportunityLoginRequest retrieved successfully.');
    }


    /**
     * @OA\Get (
     ** path="/api/OpportunityLoginRequest/showRejectedLogs",
     *   tags={"OpportunityLoginRequest"},
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
        $loginRequest = OpportunityLoginRequest::where('state', $state)->get();

        return $this->sendResponse(OpportunityLoginRequestResource::collection($loginRequest), 'OpportunityLoginRequest retrieved successfully.');
    }


    /**
     * @OA\Get(
     *      path="/api/OpportunityLoginRequest/all",
     *      operationId="6-Get A List Of LoginRequest",
     *      tags={"OpportunityLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of OpportunityLoginRequest",
     *      description="Returns A List Of OpportunityLoginRequest",
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

    public function getAllOpportunityLoginRequest()
    {
        $loginRequest = OpportunityLoginRequest::all();
        return $this->sendResponse(OpportunityLoginRequestResource::collection($loginRequest), 'OpportunityLoginRequest retrieved successfully.');

    }


    /**
     * @OA\Put   (
     ** path="/api/OpportunityLoginRequest/acceptLoginRequest/{studentID}/{opportunityID}",
     *   tags={"OpportunityLoginRequest"},
     *   summary="Accept OpportunityLoginRequestResource for specific Student",
     *   operationId="7-Accept OpportunityLoginRequest for specific Student",
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
     *     name="opportunityID",
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
    public function acceptOpportunityLoginRequest($student, $opportunityID)
    {
        $loginRequest = OpportunityLoginRequest::where('studentID', $student)->where('opportunityID', $opportunityID)->first();
        if (is_null($loginRequest))
            return $this->sendError('OpportunityLoginRequest Not Found');
        else {
            $loginRequest->state = 3;
            $loginRequest->save();
            return $this->sendResponse(new OpportunityLoginRequestResource($loginRequest), 'OpportunityLoginRequest Has been Accepted.');
        }
    }


    /**
     * @OA\Put   (
     ** path="/api/OpportunityLoginRequest/rejectedLoginRequest/{studentID}/{opportunityID}",
     *   tags={"OpportunityLoginRequest"},
     *   summary=" Reject OpportunityLoginRequest for specific Student",
     *   operationId="7- Reject OpportunityLoginRequest for specific Student",
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
     *     name="opportunityID",
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
    public function rejectOpportunityLoginRequest($student, $opportunityID)
    {
        $loginRequest = OpportunityLoginRequest::where('StudentID', $student)->where('opportunityID', $opportunityID)->first();
        if (is_null($loginRequest))
            return $this->sendError('OpportunityLoginRequest Not Found');
        else {
            $loginRequest->state = 2;
            $loginRequest->save();
            return $this->sendResponse(new OpportunityLoginRequestResource($loginRequest), 'OpportunityLoginRequest Has been Rejected.');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/OpportunityLoginRequest/showOpportunityByID/{opportunityID}",
     *      operationId="4-Show OpportunityLoginRequests By OpportunityID",
     *      tags={"OpportunityLoginRequest"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get OpportunityLoginRequests By ID Opportunitys ",
     *      description="Returns info spcific OpportunityLoginRequests",
     *     @OA\Parameter(
     *     name="opportunityID",
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
    public function getAllLoginRequestsByOpportunityID($opportunityID)
    {
        $loginRequests = OpportunityLoginRequest::where('opportunityID', $opportunityID)->get();

        return $this->sendResponse(OpportunityLoginRequestResource::collection($loginRequests), 'Opportunity LoginRequests Has been Rejected.');

    }



    /**
     * @OA\Get(
     *      path="/api/OpportunityLoginRequest/showStudentByID/{studentID}",
     *      operationId="4-Show OpportunityLoginRequests By studentID",
     *      tags={"OpportunityLoginRequest"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get OpportunityLoginRequests By ID studentID ",
     *      description="Returns info spcific OpportunityLoginRequests",
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

        $loginRequests = OpportunityLoginRequest::where('studentID', $studentId->id)->get();

        return $this->sendResponse(OpportunityLoginRequestResource::collection($loginRequests), 'OpportunityLoginRequests Has been Rejected.');

    }
}
