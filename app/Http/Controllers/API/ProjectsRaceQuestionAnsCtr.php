<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectsRaceQuestionAns;
use App\Models\ProjectsRaceQuestions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as ProjectsRaceQuestionAnsRes;
use Illuminate\Support\Facades\Validator;

class ProjectsRaceQuestionAnsCtr extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/projectsRaceQuestionAnswer/add",
     *   tags={"ProjectsRaceQuestionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add ProcjectsRace Qusetion Answer For Question",
     *   operationId="1- Add ProcjectsRace Qusetion Answer For Question",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Question Answer  data",
     *    @OA\JsonContent(
     *       @OA\property(property="questionID", type="number", example=2),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="state", type="number", example=1),
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
    public function addProjectsRaceQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'questionID' => 'required|numeric',
                'title' => 'required|string',
                'state' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $exist = ProjectsRaceQuestions::where('id', $request->questionID)->first();
        if (is_null($exist)) {
            return $this->sendError('questionID Not Found');
        }
        $answerQuestion = new ProjectsRaceQuestionAns();
        $answerQuestion->questionID = $request->questionID;
        $answerQuestion->title = $request->title;
        $answerQuestion->state = $request->state;
        $answerQuestion->save();
        return $this->sendResponse(new ProjectsRaceQuestionAnsRes($answerQuestion), 'Added successfully.');

    }

    /**
     * @OA\Put(
     ** path="/api/projectsRaceQuestionAnswer/edit",
     *   tags={"ProjectsRaceQuestionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit ProcjectsRace Qusetion Answer For Question",
     *   operationId="2- Edit ProcjectsRace Qusetion Answer For Question",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Question Answer  data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="questionID", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="state", type="number", example=1),
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
    public function editProjectsRaceQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'questionID' => 'required|numeric',
                'title' => 'required|string',
                'state' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $answerQuestion = ProjectsRaceQuestionAns::where('id', $request->id)->first();
        if (is_null($answerQuestion)) {
            return $this->sendError('CourseQuestionAnswer Not Found');
        }
        $exist = ProjectsRaceQuestions::where('id', $request->questionID)->first();
        if (is_null($exist)) {
            return $this->sendError('questionID Not Found');
        }
        $answerQuestion->title = $request->title;
        $answerQuestion->questionID = $request->questionID;
        $answerQuestion->state = $request->state;
        $answerQuestion->save();
        return $this->sendResponse(new ProjectsRaceQuestionAnsRes($answerQuestion), 'The Edit has been Done.');

    }

    /**
     * @OA\Delete  (
     ** path="/api/projectsRaceQuestionAnswer/delete/{id}",
     *   tags={"ProjectsRaceQuestionAnswer"},
     *   summary="Delete from ProcjectsRace Qusetion Answer ",
     *   operationId="3-Delete from ProcjectsRace Qusetion Answer",
     ** security={
     *  {"bearer_token":{}},
     *   },
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

    public function deleteProjectsRaceQuestionAnswer($id)
    {
        $answerQuestion = ProjectsRaceQuestionAns::find($id);
        if (!is_null($answerQuestion)) {
            $answerQuestion->delete();
            return $this->sendSuccess('deleted successfully.');
        }
        return $this->sendError('Not Found');
    }

    /**
     * @OA\Get(
     *      path="/api/projectsRaceQuestionAnswer/showByID/{id}",
     *      operationId="4-Show All Answers Related On Specific Question",
     *      tags={"ProjectsRaceQuestionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *      summary="Get list of Answers Related On Specific Question",
     *      description="Returns list of Answers Related On Specific Question",
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
    public function showProjectsRaceQuestionAnswerById($questionID)
    {
        $answerQuestion = ProjectsRaceQuestionAns::where('questionID', $questionID)->get();

        return $this->sendResponse(ProjectsRaceQuestionAnsRes::collection($answerQuestion), 'We Found it.');

    }

    /**
     * @OA\Get(
     *      path="/api/projectsRaceQuestionAnswer/all",
     *      operationId="5-Get List Of Questions Answers",
     *      tags={"ProjectsRaceQuestionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get List Of Questions Answers",
     *      description="Returns List Of Questions Answers",
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

    public function getAllProjectsRaceQuestionAnswer()
    {
        $answerQuestion = ProjectsRaceQuestionAns::all();

        return $this->sendResponse(ProjectsRaceQuestionAnsRes::collection($answerQuestion), 'ProjectsRaceQuestionAnswer retrieved successfully.');
    }

}
