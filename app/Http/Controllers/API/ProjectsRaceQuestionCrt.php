<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectsRace;
use App\Models\ProjectsRaceQuestionAns;
use App\Models\ProjectsRaceQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProjectRaceQuestion as ProjectRaceRes;


class ProjectsRaceQuestionCrt extends BaseController
{

    /**
     * @OA\Post(
     ** path="/api/projectsRacesQuestions/add",
     *   tags={"ProjectsRacesQuestions"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Qusetion For ProjectsRacesQuestion",
     *   operationId="1- Add Qusetion For ProjectsRacesQuestion",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass Question ProjectsRacesQuestion data",
     *    @OA\JsonContent(
     *       @OA\Property(property="projectsRaceId", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="required", type="boolean", example=true),
     *              @OA\Property(
     *      property="questionsAnswers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="title", type="string", example="title12"),
     *                      @OA\Property(property="state", type="number", example=1),
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
    public function addProjectsRacesQues(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string',
                'projectsRaceId' => 'required|numeric',
                'type' => 'required|numeric',
                'required' => 'required|boolean',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $opportunityId = ProjectsRace::find($request->projectsRaceId);
        if (is_null($opportunityId))
            return $this->sendError('projectsRaceId Not Found.');

        $question = new ProjectsRaceQuestions();
        $question->projectsRaceId = $request->projectsRaceId;
        $question->title = $request->title;
        $question->type = $request->type;
        $question->required = $request->required;
        $question->save();

        if (isset($request->questionsAnswers)) {
            foreach ($request->questionsAnswers as $key => $value) {
                $questionAnswer = new ProjectsRaceQuestionAns();
                $questionAnswer->questionID = $question->id;
                $questionAnswer->title = $value['title'];
                $questionAnswer->state = $value['state'];
                $questionAnswer->save();
            }
        }
        return $this->sendResponse(new ProjectRaceRes($question), 'Added successfully.');
    }


    /**
     * @OA\Put (
     ** path="/api/projectsRacesQuestions/edit",
     *   tags={"ProjectsRacesQuestions"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Question From ProjectsRacesQuestions",
     *   operationId="2-Edit From ProjectsRacesQuestions",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass ProjectsRacesQuestions data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="required", type="boolean", example=1),
     *        @OA\Property(
     *     property="questionsAnswers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="title12"),
     *                      @OA\Property(property="state", type="number", example=1),
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
    public function editProjectsRacesQues(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'title' => 'required|string',
                'type' => 'required|numeric',
                'required' => 'required|boolean',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $question = ProjectsRaceQuestions::where('id', $request->id)->first();
        if (is_null($question)) {
            return $this->sendError('ProjectsRaceQuestions Not Found');
        }
        $question->title = $request->title;
        $question->type = $request->type;
        $question->required = $request->required;
        $question->save();

        if (isset($request->questionsAnswers)) {
            foreach ($request->questionsAnswers as $key => $value) {
                $questionAnswer = ProjectsRaceQuestionAns::where('id',$value['id'])->first();
                if (is_null($questionAnswer)) {
                    return $this->sendError('questionAnswer Not Found');
                }
                $questionAnswer->questionID = $question->id;
                $questionAnswer->title = $value['title'];
                $questionAnswer->state = $value['state'];
                $questionAnswer->save();
            }
        }
        return $this->sendResponse(new ProjectRaceRes($question), 'The Edit has been Done.');

    }

    /**
     * @OA\Delete  (
     ** path="/api/projectsRacesQuestions/delete/{id}",
     *   tags={"ProjectsRacesQuestions"},
     *   summary="Delete from  ProjectsRacesQuestion ",
     *   operationId="3-delete ProjectsRacesQuestions",
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

    public function deleteProjectsRacesQues($id)
    {
        $question = ProjectsRaceQuestions::find($id);
        if (is_null($question)) {
            return $this->sendError('Not Found');
        }
        $question->delete();
        return $this->sendSuccess('ProjectsRaceQuestions deleted successfully.');
    }


    /**
     * @OA\Get(
     *      path="/api/projectsRacesQuestions/showByID/{id}",
     *      operationId="4-Show All Questions Related On Specific ProjectsRacesQuestions",
     *      tags={"ProjectsRacesQuestions"},
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
     *      summary="Get list of Questions Related On Specific ProjectsRacesQuestions",
     *      description="Returns list of Questions Related On Specific ProjectsRacesQuestions",
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
    public function showProjectsRacesByIDQues($id)
    {
//        $question = ProjectsRaceQuestions::find($id);
        $question = ProjectsRaceQuestions::where('id', $id)->get();


        return $this->sendResponse(ProjectRaceRes::collection($question), 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/projectsRacesQuestions/all",
     *      operationId="5-Get List Of ProjectsRacesQuestions",
     *      tags={"ProjectsRacesQuestions"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get A list of ProjectsRacesQuestions",
     *      description="Returns list of ProjectsRacesQuestions",
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

    public function getAllProjectsRacesQues()
    {
        $question = ProjectsRaceQuestions::all();
        return $this->sendResponse(ProjectRaceRes::collection($question), 'ProjectsRaceQuestions retrieved successfully.');
    }


}
