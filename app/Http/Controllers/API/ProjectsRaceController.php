<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectsRace;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as ProjectsRaceResource;
use Illuminate\Support\Facades\Validator;

class ProjectsRaceController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/projectsRaces/add",
     *   tags={"ProjectsRaces"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add ProjectsRace",
     *   operationId="1-Add ProjectsRace",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ProjectsRace data",
     *    @OA\JsonContent(
     *       @OA\property(property="title", type="string", example="title"),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="manager", type="string", example="Ahmad"),
     *       @OA\Property(property="startDate", type="date", example="2020/01/20"),
     *       @OA\Property(property="endDate", type="date", example="2020/03/20"),
     *      @OA\Property(property="description", type="string", example="AAA"),
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

    public function addProjectsRaces(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string|unique:projects_races',
                'location' => 'required|string',
                'startDate' => 'required|dateFormat:' . config('app.dateFormat'),
                'endDate' => 'required|date_format:' . config('app.dateFormat'),
                'description' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $projectsRace = new ProjectsRace();
        $projectsRace->title = $request->title;
        $projectsRace->location = $request->location;
        $projectsRace->manager = isset($request->manager) ? $request->manager : null;
        $projectsRace->startDate = $request->startDate;
        $projectsRace->endDate = $request->endDate;
        $projectsRace->description = $request->description;

        $projectsRace->save();
        return $this->sendResponse(new ProjectsRaceResource($projectsRace), 'Added successfully.');
    }

    /**
     * @OA\Put(
     ** path="/api/projectsRaces/edit",
     *   tags={"ProjectsRaces"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit ProjectsRace",
     *   operationId="2-Edit ProjectsRace",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ProjectsRace data",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="title", type="string", example="title"),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="manager", type="string", example="Ahmad"),
     *       @OA\Property(property="startDate", type="date", example="2020/01/20"),
     *       @OA\Property(property="endDate", type="date", example="2020/03/20"),
     *      @OA\Property(property="description", type="string", example="AAA"),
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
    public function editProjectsRaces(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|numeric',
                'title' => 'required|string',
                'location' => 'required|string',
                'startDate' => 'required|dateFormat:' . config('app.dateFormat'),
                'endDate' => 'required|date_format:' . config('app.dateFormat'),
                'description' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $projectsRace = ProjectsRace::where('id', $request->id)->first();
        if (is_null($projectsRace)) {
            return $this->sendError('ProjectsRace Not Found');
        }
        $projectsRace->title = $request->title;
        $projectsRace->location = $request->location;
        $projectsRace->manager = isset($request->manager) ? $request->manager : null;
        $projectsRace->startDate = $request->startDate;
        $projectsRace->endDate = $request->endDate;
        $projectsRace->description = $request->description;
        $projectsRace->save();
        return $this->sendResponse(new ProjectsRaceResource($projectsRace), 'The Edit has been Done.');
    }


    /**
     * @OA\Delete  (
     ** path="/api/projectsRaces/delete/{id}",
     *   tags={"ProjectsRaces"},
     *   summary="Delete from ProjectsRace ",
     *   operationId="3-Delete From ProjectsRace",
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
    public function deleteProjectsRaces($id)
    {
        $projectsRace = ProjectsRace::find($id);
        if (is_null($projectsRace)) {
            return $this->sendError('ProjectsRace Not Found');
        } else {
            $projectsRace->delete();
            return $this->sendSuccess( 'Delete ProjectsRace has been done.');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/projectsRaces/showByID/{id}",
     *      operationId="4-Show Course From ProjectsRace By ID",
     *      tags={"ProjectsRaces"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get Course From ProjectsRace By ID",
     *      description="Returns info spcific ProjectsRace",
     *     @OA\Parameter(
     *     name="id",
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
    public function showProjectsRacesByID($id)
    {
        $projectsRace = ProjectsRace::find($id);
        if (is_null($projectsRace)) {
            return $this->sendError('Not Found ProjectsRace ');
        }
        return $this->sendResponse($projectsRace, 'We Found it.');
    }
    /**
     * @OA\Get(
     *      path="/api/projectsRaces/all",
     *      operationId="5-Get A list Of ProjectsRace",
     *      tags={"ProjectsRaces"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get a list of ProjectsRace",
     *      description="Returns a list of ProjectsRace",
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

    public function getAllProjectsRaces()
    {
        $projectsRace = ProjectsRace::all();
        return $this->sendResponse(ProjectsRaceResource::collection($projectsRace), 'ProjectsRace retrieved successfully.');
    }
}
