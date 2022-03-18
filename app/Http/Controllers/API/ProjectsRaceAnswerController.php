<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectsRaceAnswer;
use App\Models\ProjectsRaceQuestions;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectsRaceAnswerRes as ProjectsRaceAnswerResource;
use Illuminate\Support\Facades\Validator;


class ProjectsRaceAnswerController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/projectsRaceAnswer/add",
     *   tags={"ProjectsRaceAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add ProjectsRaceAnswer",
     *   operationId="1-Add ProjectsRaceAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ProjectsRaceAnswer data",
     *    @OA\JsonContent(
     *      @OA\Property(property="studentID", type="number", example=1),
     *      @OA\Property(property="questionID", type="number", example=1),
     *       @OA\property(property="answer", type="string", example="title"),
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

    public function addProjectsRaceAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'questionID' => 'required|numeric',
                'answer' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $courseQuestion = ProjectsRaceQuestions::where('id', $request->questionID)->first();
        if (is_null($courseQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer = new ProjectsRaceAnswer();
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new ProjectsRaceAnswerResource($answer), 'ProjectsRaceAnswer Added successfully.');
    }


    /**
     * @OA\Put(
     ** path="/api/projectsRaceAnswer/edit",
     *   tags={"ProjectsRaceAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit ProjectsRaceAnswer",
     *   operationId="2-Edit ProjectsRaceAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ProjectsRaceAnswer data",
     *    @OA\JsonContent(
     *     @OA\Property(property="id", type="number", example=1),
     *     @OA\Property(property="studentID", type="number", example=1),
     *      @OA\Property(property="questionID", type="number", example=1),
     *       @OA\property(property="answer", type="string", example="title"),
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
    public function editProjectsRaceAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'studentID' => 'required|numeric',
                'questionID' => 'required|numeric',
                'answer' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $answer = ProjectsRaceAnswer::where('id', $request->id)->first();
        if (is_null($answer)) {
            return $this->sendError('CourseAnswers Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $courseQuestion = ProjectsRaceQuestions::where('id', $request->questionID)->first();
        if (is_null($courseQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new ProjectsRaceAnswerResource($answer), 'The Edit has been Done.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/projectsRaceAnswer/delete/{id}",
     *   tags={"ProjectsRaceAnswer"},
     *   summary="Delete from ProjectsRaceAnswer ",
     *   operationId="3-delete ProjectsRaceAnswer",
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

    public function deleteProjectsRaceAnswer($id)
    {
        $answer = ProjectsRaceAnswer::find($id);
        if (is_null($answer)) {
            return $this->sendError('Not Found Deleted Unsuccessfully.' );
        } else {
            $answer->delete();
            return $this->sendSuccess('deleted successfully.');
        }
    }


    /**
     * @OA\Get (
     ** path="/api/projectsRaceAnswer/showByID/{id}",
     *   tags={"ProjectsRaceAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from ProjectsRaceAnswer ",
     *   operationId="4-Show ProjectsRaceAnswer From ProjectsRaceAnswers",
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
    public function showProjectsRaceAnswerByID($id)
    {
        $answer = ProjectsRaceAnswer::find($id);
        if (is_null($answer))
            return $this->sendError('ProjectsRaceAnswer Not Found ');
        return $this->sendResponse(new ProjectsRaceAnswerResource($answer), 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/projectsRaceAnswer/all",
     *      operationId="5-Get A List Of ProjectsRaceAnswers",
     *      tags={"ProjectsRaceAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of ProjectsRaceAnswers",
     *      description="Returns A List Of ProjectsRaceAnswers",
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

    public function getAllProjectsRaceAnswer()
    {
        $answer = ProjectsRaceAnswer::all();
        return $this->sendResponse(ProjectsRaceAnswerResource::collection($answer), 'ProjectsRaceAnswer retrieved successfully.');

    }

}
