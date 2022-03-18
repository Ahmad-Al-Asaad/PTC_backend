<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectsRace;
use App\Models\ProjectsRaceAnswer;
use App\Models\ProjectsRaceQuestions;
use App\Models\projectsRaceRequest;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
//use Illuminate\Http\Resources\Json\JsonResource as ProjectsRaceRequestsRes;
use App\Http\Resources\ProjectsRaceRequests as ProjectsRaceRequestsRes;
class ProjectsRaceRequestController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/projectsRaceLoginRequest/add",
     *   tags={"ProjectsRaceLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add ProjectsRaceLoginRequest",
     *   operationId="1-Add ProjectsRaceLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ProjectsRaceLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="projectsRaceID", type="number", example=1),
     *       @OA\Property(property="state", type="number", example=1),
     *        @OA\Property(
     *                  property="Answers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="questionID", type="number", example=1),
     *                      @OA\Property(property="answer", type="string", example="1"),
     *                  ),
     *              ),
     * ),
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

    public function addProjectsRaceLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'projectsRaceID' => 'required|numeric',
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
        $projectRaceId = ProjectsRace::where('id', $request->projectsRaceID)->first();
        if (is_null($projectRaceId)) {
            return $this->sendError('projectsRaceID Not Found');
        }

        $projectsRaceRequest = projectsRaceRequest::where('projectsRaceID', $request->projectsRaceID)->where('studentID', $studentId->id)->first();
        if ($projectsRaceRequest) {
            return $this->sendError('Cant login request in projectsRace you are already exist');
        }

        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = new ProjectsRaceAnswer();
                $questionid = ProjectsRaceQuestions::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $studentId->id;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }

        $loginRequest = new projectsRaceRequest();
        $loginRequest->studentID = $studentId->id;
        $loginRequest->projectsRaceID = $request->projectsRaceID;
        $loginRequest->state = $request->state;
        $loginRequest->save();

        return $this->sendResponse(new ProjectsRaceRequestsRes($loginRequest), 'projectsRaceRequest Added successfully.');

    }

    /**
     * @OA\Put(
     ** path="/api/projectsRaceLoginRequest/edit",
     *   tags={"ProjectsRaceLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit ProjectsRaceLoginRequest",
     *   operationId="2-Edit ProjectsRaceLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass CourseLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="projectsRaceID", type="number", example=1),
     *       @OA\Property(property="state", type="number", example=1),
     *        @OA\Property(
     *                  property="Answers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="questionID", type="number", example=1),
     *                      @OA\Property(property="answer", type="string", example="1"),
     *                  ),
     *              ),
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

    public function editProjectsRaceLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'projectsRaceID' => 'required|numeric',
                'state' => 'required|numeric',

            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $loginRequest = projectsRaceRequest::where('id', $request->id)->first();
        if (is_null($loginRequest)) {
            return $this->sendError('projectsRaceRequest Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $projectRaceId = ProjectsRace::where('id', $request->projectsRaceID)->first();
        if (is_null($projectRaceId)) {
            return $this->sendError('projectsRaceID Not Found');
        }
        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = ProjectsRaceAnswer::where('id', $value['id'])->first();
                if (is_null($answers)) {
                    return $this->sendError('ProjectsRaceAnswer Not Found');
                }
                $questionid = ProjectsRaceQuestions::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $request->studentID;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }

        $loginRequest->studentID = $request->studentID;
        $loginRequest->projectsRaceID = $request->projectsRaceID;
        $loginRequest->state = $request->state;
        $loginRequest->save();
        return $this->sendResponse(new ProjectsRaceRequestsRes($loginRequest), 'projectsRaceRequest Edit successfully.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/projectsRaceLoginRequest/delete/{id}",
     *   tags={"ProjectsRaceLoginRequest"},
     *   summary="Delete from ProjectsRaceLoginRequest ",
     *   operationId="3-Delete from ProjectsRaceLoginRequest",
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

    public function deleteProjectsRaceLoginRequest($id)
    {
        $loginRequest = projectsRaceRequest::find($id);
        if (is_null($loginRequest)) {
            return $this->sendError('deleted Unsuccessfully.');
        } else {
            $loginRequest->delete();
            return $this->sendSuccess('Deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/projectsRaceLoginRequest/showAcceptedLogs/{projectsRaceID}",
     *   tags={"ProjectsRaceLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show Accepted projectsRaceLoginRequest from LoginRequest ",
     *   operationId="4-show Accepted projectsRaceLoginRequest from LoginRequest",
     *
     * description="Returns info Accepted projectsRaceLoginRequest",
     *     @OA\Parameter(
     *     name="projectsRaceID",
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

    public function showAcceptedProjectsRaceLoginRequest($projectsRaceID)
    {
        $state = 3;
        $loginRequest = projectsRaceRequest::where('state', $state)->where('projectsRaceID', $projectsRaceID)->get();

        return $this->sendResponse(ProjectsRaceRequestsRes::collection($loginRequest), 'projectsRaceRequest retrieved successfully.');
    }


    /**
     * @OA\Get (
     ** path="/api/projectsRaceLoginRequest/showRejectedLogs",
     *   tags={"ProjectsRaceLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from  Rejected LoginRequest ",
     *   operationId="5-show Rejected projectsRaceLoginRequest from LoginRequest",
     *
     * description="Returns Rejected projectsRaceLoginRequest",
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

    public function showRejectedProjectsRaceLoginRequest()
    {
        $state = 2;
        $loginRequest = projectsRaceRequest::where('state', $state)->get();

        return $this->sendResponse(ProjectsRaceRequestsRes::collection($loginRequest), 'projectsRaceRequest retrieved successfully.');
    }


    /**
     * @OA\Get(
     *      path="/api/projectsRaceLoginRequest/all",
     *      operationId="6-Get A List Of LoginRequest",
     *      tags={"ProjectsRaceLoginRequest"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of projectsRaceLoginRequests",
     *      description="Returns A List Of projectsRaceLoginRequests",
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

    public function getAllProjectsRaceLoginRequest()
    {
        $loginRequest = projectsRaceRequest::all();
        return $this->sendResponse(ProjectsRaceRequestsRes::collection($loginRequest), 'projectsRaceRequest retrieved successfully.');

    }


    /**
     * @OA\Put   (
     ** path="/api/projectsRaceLoginRequest/acceptLoginRequest/{studentID}/{projectsRaceID}",
     *   tags={"ProjectsRaceLoginRequest"},
     *   summary="Accept projectsRaceLoginRequest for specific Student",
     *   operationId="7-Accept projectsRaceLoginRequest for specific Student",
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
     *     name="projectsRaceID",
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
    public function acceptProjectsRaceLoginRequest($student, $projectRaceId)
    {
        $loginRequest = projectsRaceRequest::where('StudentID', $student)->where('projectsRaceID', $projectRaceId)->first();
        if (is_null($loginRequest))
            return $this->sendError('projectsRaceRequest Not Found');
        else {
            $loginRequest->state = 3;
            $loginRequest->save();
            return $this->sendResponse(new ProjectsRaceRequestsRes($loginRequest), 'projectsRaceRequest Has been Accepted.');
        }
    }


    /**
     * @OA\Put   (
     ** path="/api/projectsRaceLoginRequest/rejectedLoginRequest/{studentID}/{projectsRaceID}",
     *   tags={"ProjectsRaceLoginRequest"},
     *   summary=" Reject projectsRaceLoginRequest for specific Student",
     *   operationId="7- Reject projectsRaceLoginRequest for specific Student",
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
     *     name="projectsRaceID",
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
    public function rejectProjectsRaceLoginRequest($student, $projectRaceId)
    {
        $loginRequest = projectsRaceRequest::where('StudentID', $student)->where('projectsRaceID', $projectRaceId)->first();
        if (is_null($loginRequest))
            return $this->sendError('projectsRaceRequest Not Found');
        else {
            $loginRequest->state = 2;
            $loginRequest->save();
            return $this->sendResponse(new ProjectsRaceRequestsRes($loginRequest), 'projectsRaceRequest Has been Rejected.');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/projectsRaceLoginRequest/showProjectsRaceByID/{projectsRaceID}",
     *      operationId="4-Show ProjectsRaceLoginRequests By ProjectsRaceID",
     *      tags={"ProjectsRaceLoginRequest"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get ProjectsRaceLoginRequests By ID ProjectsRaces ",
     *      description="Returns info spcific ProjectsRaceLoginRequests",
     *     @OA\Parameter(
     *     name="projectsRaceID",
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
    public function getAllLoginRequestsByProjectsRaceID($projectsRaceID)
    {
        $loginRequests = projectsRaceRequest::where('projectsRaceID', $projectsRaceID)->get();

        return $this->sendResponse(ProjectsRaceRequestsRes::collection($loginRequests), 'ProjectsRace LoginRequests Has been Rejected.');

    }



    /**
     * @OA\Get(
     *      path="/api/projectsRaceLoginRequest/showStudentByID/{studentID}",
     *      operationId="4-Show ProjectsRaceLoginRequests By studentID",
     *      tags={"ProjectsRaceLoginRequest"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get ProjectsRaceLoginRequests By ID studentID ",
     *      description="Returns info spcific ProjectsRaceLoginRequests",
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

        $loginRequests = projectsRaceRequest::where('studentID', $studentId->id)->get();

        return $this->sendResponse(ProjectsRaceRequestsRes::collection($loginRequests), 'ProjectsRaceLoginRequests Has been Rejected.');

    }

}
