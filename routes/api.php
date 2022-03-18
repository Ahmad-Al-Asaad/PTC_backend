<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');
Route::post('add', 'API\StudentController@addStudent');                 // Add Student


Route::middleware('auth:api')->group(function () {
    Route::get('all-user', 'API\UserController@getAllUser');                        // All Users
    Route::post('update', 'API\UserController@updateUser');                         // Update Users

    // Courses
    Route::group(['prefix' => 'course'], function () {
        Route::post('add', 'API\CoursesController@addCourse');                       // Add Course
        Route::delete('delete/{id}', 'API\CoursesController@deleteCourse');          // Delete Course
        Route::get('showByID/{id}', 'API\CoursesController@showCourseByID');         // Show Course By ID
        Route::put('edit', 'API\CoursesController@editCourse');                      //  Edit Course
        Route::put('changeState/{id}/{state}', 'API\CoursesController@changeState'); // Change State
        Route::get('all', 'API\CoursesController@getAllCourses');                    // Show All Courses
        Route::get('courseQuestionAnswers/{id}', 'API\CoursesController@getQuestionAndItsAnswersQuestionsForSpecificCourse');
    });

    // Events
    Route::group(['prefix' => 'event'], function () {
        Route::get('all', 'API\EventController@showEvents');                        // Show All Events
        Route::delete('delete/{id}', 'API\EventController@deleteEvent');           // Delete Events
        Route::get('showByID/{id}', 'API\EventController@showEventByID');           // Show Events By ID
        Route::put('edit', 'API\EventController@editEvent');                        //  Edit Events
        Route::post('add', 'API\EventController@addEvent');                         // Add Events
    });

    // opportunity
    Route::group(['prefix'=>'opportunity'],function(){
        Route::post('addOpportunity', 'API\OpportunityController@addOpportunity');
        Route::put('deleteOpportunity/{id}', 'API\OpportunityController@deleteOpportunity');
        Route::put('editOpportunity', 'API\OpportunityController@editOpportunity');
        Route::get('showOpportunityById/{id}', 'API\OpportunityController@showOpportunityById');
        Route::get('showAllOpportunity', 'API\OpportunityController@getAllOpportunity');
        Route::put('changeStateOpportunity/{id}/{state}', 'API\OpportunityController@changeState');
    });

    // Company
    Route::group(['prefix'=>'company'],function () {
        Route::get('showAllCompany', 'API\CompanyController@showCompany');// Show All Company
        Route::delete('deleteCompany/{id}', 'API\CompanyController@deleteCompany');// Delete Company
        Route::get('showCompanyByID/{id}', 'API\CompanyController@showCompanyByID');// Show Company By ID
        Route::put('editCompany', 'API\CompanyController@editCompany');//  Edit Company
        Route::post('addCompany', 'API\CompanyController@addCompany');// Add Company
    });

    // opportunity Question
    Route::group(['prefix'=>'opportunityQuestion'],function () {
        Route::post('addOpportunityQuestion', 'API\OpportunityQuestionController@addQuestion');
        Route::put('editOpportunityQuestion', 'API\OpportunityQuestionController@editQuestion');
        Route::delete('deleteOpportunityQuestion/{id}', 'API\OpportunityQuestionController@deleteQuestion');
        Route::get('showOpportunityQuestionsByOpportunityID/{id}', 'API\OpportunityQuestionController@showQuestionsByOpportunityID');
        Route::get('showAllOpportunityQuestions', 'API\OpportunityQuestionController@showAllQuestions');
    });

    // opportunity Question Answer
    Route::group(['prefix'=>'opportunityQuestionAnswer'],function () {
        Route::post('addAnswer', 'API\OpportunityQuestionAnswersController@addOpportunityQuestionAnswer');
        Route::put('editAnswer', 'API\OpportunityQuestionAnswersController@editOpportunityQuestionAnswer');
        Route::delete('deleteAnswer/{id}', 'API\OpportunityQuestionAnswersController@deleteOpportunityQuestionAnswer');
        Route::get('showAnswersByQuestionID/{id}', 'API\OpportunityQuestionAnswersController@showOpportunityQuestionAnswerById');
        Route::get('showAllAnswer', 'API\OpportunityQuestionAnswersController@showAllOpportunityQuestionAnswer');
        Route::put('changeState/{id}/{state}', 'API\OpportunityQuestionAnswersController@changeStateOpportunityQuestionAnswerById');
    });

    //Student opportunityAnswer
    Route::group(['prefix' => 'OpportunityAnswer'], function () {
        Route::post('add', 'API\OpportunityAnswerController@addOpportunityAnswer');                 // Add opportunityAnswers
        Route::put('edit', 'API\OpportunityAnswerController@editOpportunityAnswer');                // Edit opportunityAnswers
        Route::delete('delete/{id}', 'API\OpportunityAnswerController@deleteOpportunityAnswer');    // Delete opportunityAnswers
        Route::get('showByID/{id}', 'API\OpportunityAnswerController@showOpportunityAnswerByID');// Show opportunityAnswers By ID
        Route::get('all', 'API\OpportunityAnswerController@getAllOpportunityAnswer');          // Show All opportunityAnswers
        Route::get('showStudentAnswers/{id}', 'API\OpportunityAnswerController@getAllStudentAnswers');
        Route::get('showStudentAnswersInSpecificQuestion/{studentId}/{questionId}', 'API\OpportunityAnswerController@showStudentAnswersInSpecificQuestion');
    });

    //opportunityLoginRequest
    Route::group(['prefix' => 'OpportunityLoginRequest'], function () {
        Route::post('add', 'API\OpportunityLoginRequestController@addOpportunityLoginRequest');                 // Add LoginRequest
        Route::put('edit', 'API\OpportunityLoginRequestController@editOpportunityLoginRequest');                // Edit LoginRequest
        Route::delete('delete/{id}', 'API\OpportunityLoginRequestController@deleteOpportunityLoginRequest');    // Delete LoginRequest
        Route::get('showAcceptedLogs/{opportunityID}', 'API\OpportunityLoginRequestController@showAcceptedLoginRequest');// Show Accepted LoginRequest
        Route::get('showRejectedLogs', 'API\OpportunityLoginRequestController@showRejectedLoginRequest');// Show Rejected LoginRequest
        Route::put('acceptLoginRequest/{studentID}/{courseID}', 'API\OpportunityLoginRequestController@acceptOpportunityLoginRequest');// Accept LoginRequest
        Route::put('rejectedLoginRequest/{studentID}/{courseID}', 'API\OpportunityLoginRequestController@rejectOpportunityLoginRequest');//  Rejected LoginRequest
        Route::get('all', 'API\OpportunityLoginRequestController@getAllOpportunityLoginRequest');          // Show All LoginRequests
        Route::get('showOpportunityByID/{courseID}', 'API\OpportunityLoginRequestController@getAllLoginRequestsByOpportunityID');
        Route::get('showStudentByID/{studentID}', 'API\OpportunityLoginRequestController@getAllLoginRequestsByStudentID');

    });

    // Exhibition
    Route::group(['prefix'=>'Exhibition'],function(){
        Route::post('addExhibition', 'API\ExhibitionController@addExhibition');
        Route::put('deleteExhibition/{id}', 'API\ExhibitionController@deleteExhibition');
        Route::put('editExhibition', 'API\ExhibitionController@editExhibition');
        Route::get('showExhibitionById/{id}', 'API\ExhibitionController@showExhibitionById');
        Route::get('showAllExhibition', 'API\ExhibitionController@getAllExhibition');
    });

    // Exhibition Question
    Route::group(['prefix'=>'ExhibitionQuestion'],function () {
        Route::post('addExhibitionQuestion', 'API\ExhibitionQuestionController@addQuestion');
        Route::put('editExhibitionQuestion', 'API\ExhibitionQuestionController@editQuestion');
        Route::delete('deleteExhibitionQuestion/{id}', 'API\ExhibitionQuestionController@deleteQuestion');
        Route::get('showExhibitionQuestionsByExhibitionId/{id}', 'API\ExhibitionQuestionController@showQuestionsByExhibitionID');
        Route::get('showAllExhibitionQuestions', 'API\ExhibitionQuestionController@showAllQuestions');
    });

    // Exhibition Question Answer
    Route::group(['prefix'=>'ExhibitionQuestionAnswer'],function () {
        Route::post('addAnswer', 'API\ExhibitionQuestionAnswersController@addExhibitionQuestionAnswer');
        Route::put('editAnswer', 'API\ExhibitionQuestionAnswersController@editExhibitionQuestionAnswer');
        Route::delete('deleteAnswer/{id}', 'API\ExhibitionQuestionAnswersController@deleteExhibitionQuestionAnswer');
        Route::get('showAnswersByQuestionID/{id}', 'API\ExhibitionQuestionAnswersController@showExhibitionQuestionAnswerById');
        Route::get('showAllAnswer', 'API\ExhibitionQuestionAnswersController@showAllExhibitionQuestionAnswer');
        Route::put('changeState/{id}/{state}', 'API\ExhibitionQuestionAnswersController@changeStateExhibitionQuestionAnswerById');
    });

    //Student ExhibitionAnswer
    Route::group(['prefix' => 'ExhibitionAnswer'], function () {
        Route::post('add', 'API\ExhibitionAnswerController@addExhibitionAnswer');
        Route::put('edit', 'API\ExhibitionAnswerController@editExhibitionAnswer');
        Route::delete('delete/{id}', 'API\ExhibitionAnswerController@deleteExhibitionAnswer');
        Route::get('showByID/{id}', 'API\ExhibitionAnswerController@showExhibitionAnswerByID');
        Route::get('all', 'API\ExhibitionAnswerController@getAllExhibitionAnswer');
        Route::get('showStudentAnswers/{id}', 'API\ExhibitionAnswerController@getAllStudentAnswer');
        Route::get('showStudentAnswersInSpecificQuestion/{studentId}/{questionId}', 'API\ExhibitionAnswerController@showStudentAnswersInSpecificQuestion');
    });

    //ExhibitionLoginRequest
    Route::group(['prefix' => 'ExhibitionLoginRequest'], function () {
        Route::post('add', 'API\ExhibitionLoginRequestController@addExhibitionLoginRequest');                 // Add LoginRequest
        Route::put('edit', 'API\ExhibitionLoginRequestController@editExhibitionLoginRequest');                // Edit LoginRequest
        Route::delete('delete/{id}', 'API\ExhibitionLoginRequestController@deleteExhibitionLoginRequest');    // Delete LoginRequest
        Route::get('showAcceptedLogs/{exhibitionID}', 'API\ExhibitionLoginRequestController@showAcceptedLoginRequest');// Show Accepted LoginRequest
        Route::get('showRejectedLogs', 'API\ExhibitionLoginRequestController@showRejectedLoginRequest');// Show Rejected LoginRequest
        Route::put('acceptLoginRequest/{studentID}/{courseID}', 'API\ExhibitionLoginRequestController@acceptExhibitionLoginRequest');// Accept LoginRequest
        Route::put('rejectedLoginRequest/{studentID}/{courseID}', 'API\ExhibitionLoginRequestController@rejectExhibitionLoginRequest');//  Rejected LoginRequest
        Route::get('all', 'API\ExhibitionLoginRequestController@getAllExhibitionLoginRequest');          // Show All LoginRequests
        Route::get('showExhibitionByID/{courseID}', 'API\ExhibitionLoginRequestController@getAllLoginRequestsByExhibitionID');
        Route::get('showStudentByID/{studentID}', 'API\OpportunityLoginRequestController@getAllLoginRequestsByStudentID');

    });

    // ItemCost
    Route::group(['prefix'=>'ItemCost'],function () {
        Route::post('addItemCost', 'API\ItemCostController@addItemCost');
        Route::put('editItemCost', 'API\ItemCostController@editItemCost');
        Route::delete('deleteItemCost/{code}', 'API\ItemCostController@deleteItemCost');
        Route::get('showItemCostByCode/{code}', 'API\ItemCostController@showItemCostByCode');
        Route::get('showAllItemCost', 'API\ItemCostController@showAllItemCost');
    });

    // ExpansiveCost
    Route::group(['prefix'=>'ExpansiveCost'],function () {
        Route::post('addExpansiveCost', 'API\ExpansiveCostController@addExpansiveCost');
        Route::put('editExpansiveCost', 'API\ExpansiveCostController@editExpansiveCost');
        Route::delete('deleteExpansiveCost/{id}', 'API\ExpansiveCostController@deleteExpansiveCost');
        Route::get('showExpansiveCostById/{id}', 'API\ExpansiveCostController@showExpansiveCostById');
        Route::get('showAllExpansiveCost', 'API\ExpansiveCostController@showAllExpansiveCost');
        Route::get('showItemsByExpansiveCode/{code}', 'API\ExpansiveCostController@showExpansiveCostByItemCostCode');
        Route::put('changeState/{id}/{state}', 'API\ExpansiveCostController@changeState');
        Route::post('endPointMonth', 'API\ExpansiveCostController@endPointMonth');
        Route::post('endPointYear', 'API\ExpansiveCostController@endPointYear');
        Route::post('endPointAllMonthInYear', 'API\ExpansiveCostController@endPointAllMonthInYear');
    });

    //CourseQuestion
    Route::group(['prefix' => 'question'], function () {
        Route::post('add', 'API\QuestionCourseController@addQuestion');                 // Add Question
        Route::put('edit', 'API\QuestionCourseController@editQuestion');                // Edit Question
        Route::delete('delete/{id}', 'API\QuestionCourseController@deleteQuestion');    // Delete Question
        Route::get('showByID/{id}', 'API\QuestionCourseController@showCourseQuestions');// Show Question By ID
        Route::get('all', 'API\QuestionCourseController@getAllQuestions');          // Show All Questions
    });
    //CourseQuestionAnswer
    Route::group(['prefix' => 'courseQuestionAnswer'], function () {
        Route::post('add', 'API\CourseQuestionAnswerController@addCourseQuestionAnswer');                 // Add A Course Question Answer
        Route::put('edit', 'API\CourseQuestionAnswerController@editCourseQuestionAnswer');                // Edit A Course Question Answer
        Route::delete('delete/{id}', 'API\CourseQuestionAnswerController@deleteCourseQuestionAnswer');    // Delete A Course Question Answer
        Route::get('showByID/{id}', 'API\CourseQuestionAnswerController@showCourseQuestionAnswerById'); // Show A Course Question Answer By ID
        Route::get('all', 'API\CourseQuestionAnswerController@getAllCourseQuestionAnswer');             // Show All Course Question Answer
        Route::put('changeState/{id}/{state}', 'API\CourseQuestionAnswerController@changeStateCourseQuestionAnswerById');      // Show All Questions And Answers Related On specific Course
    });


    //Volunteer
    Route::group(['prefix' => 'volunteer'], function () {
        Route::post('add', 'API\VolunteerController@addVolunteer');                 // Add Volunteer
        Route::put('edit', 'API\VolunteerController@editVolunteer');                // Edit Volunteer
        Route::delete('delete/{id}', 'API\VolunteerController@deleteVolunteer');    // Delete Volunteer
        Route::get('showByID/{id}', 'API\VolunteerController@showVolunteerByID');// Show Volunteer By ID
        Route::get('all', 'API\VolunteerController@getAllVolunteers');          // Show All Volunteers
    });
    //Student
    Route::group(['prefix' => 'student'], function () {
        Route::put('edit', 'API\StudentController@editStudent');                // Edit Student
        Route::delete('delete/{id}', 'API\StudentController@deleteStudent');    // Delete Student
        Route::get('showByID/{id}', 'API\StudentController@showStudentByID');// Show Student By ID
        Route::get('all', 'API\StudentController@getAllStudents');          // Show All Students
    });
    //CourseLoginRequest
    Route::group(['prefix' => 'courseLoginRequest'], function () {
        Route::post('add', 'API\CourseLoginRequestController@addCourseLoginRequest');                 // Add LoginRequest
        Route::put('edit', 'API\CourseLoginRequestController@editCourseLoginRequest');                // Edit LoginRequest
        Route::delete('delete/{id}', 'API\CourseLoginRequestController@deleteCourseLoginRequest');    // Delete LoginRequest
        Route::get('showAcceptedLogs/{courseID}', 'API\CourseLoginRequestController@showAcceptedLoginRequest');// Show Accepted LoginRequest
        Route::get('showRejectedLogs', 'API\CourseLoginRequestController@showRejectedLoginRequest');// Show Rejected LoginRequest
        Route::put('acceptLoginRequest/{studentID}/{courseID}', 'API\CourseLoginRequestController@acceptCourseLoginRequest');// Accept LoginRequest
        Route::put('rejectedLoginRequest/{studentID}/{courseID}', 'API\CourseLoginRequestController@rejectCourseLoginRequest');//  Rejected LoginRequest
        Route::get('all', 'API\CourseLoginRequestController@getAllCourseLoginRequests');          // Show All LoginRequests
        Route::get('showCourseByID/{courseID}', 'API\CourseLoginRequestController@getAllLoginRequestsByCourseID');
        Route::get('showStudentByID/{studentID}', 'API\CourseLoginRequestController@getAllLoginRequestsByStudentID');
    });
    //Student
    Route::group(['prefix' => 'trainer'], function () {
        Route::post('add', 'API\TrainerController@addTrainer');                 // Add Trainer
        Route::put('edit', 'API\TrainerController@editTrainer');                // Edit Trainer
        Route::delete('delete/{id}', 'API\TrainerController@deleteTrainer');    // Delete Trainer
        Route::get('showByID/{id}', 'API\TrainerController@showTrainerByID');// Show Trainer By ID
        Route::get('all', 'API\TrainerController@getAllTrainer');          // Show All Trainer
    });
    //CourseAnswer
    Route::group(['prefix' => 'courseAnswer'], function () {
        Route::post('add', 'API\CourseAnswersController@addCourseAnswers');                 // Add CourseAnswers
        Route::put('edit', 'API\CourseAnswersController@editCourseAnswers');                // Edit CourseAnswers
        Route::delete('delete/{id}', 'API\CourseAnswersController@deleteCourseAnswer');    // Delete CourseAnswers
        Route::get('showByID/{id}', 'API\CourseAnswersController@showCourseAnswerByID');// Show CourseAnswers By ID
        Route::get('all', 'API\CourseAnswersController@getAllCourseAnswers');          // Show All CourseAnswers
        Route::get('showStudentAnswers/{id}', 'API\CourseAnswersController@getAllStudentAnswers');
        Route::get('showStudentAnswersInSpecificQuestion/{studentId}/{questionId}', 'API\CourseAnswersController@showStudentAnswersInSpecificQuestion');
    });

    //projectsRaces
    Route::group(['prefix' => 'projectsRaces'], function () {
        Route::post('add', 'API\ProjectsRaceController@addProjectsRaces');                 // Add projectsRaces
        Route::put('edit', 'API\ProjectsRaceController@editProjectsRaces');                // Edit projectsRaces
        Route::delete('delete/{id}', 'API\ProjectsRaceController@deleteProjectsRaces');    // Delete projectsRaces
        Route::get('showByID/{id}', 'API\ProjectsRaceController@showProjectsRacesByID');// Show projectsRaces By ID
        Route::get('all', 'API\ProjectsRaceController@getAllProjectsRaces');          // Show All projectsRaces
    });
    //projectsRacesQuestions
    Route::group(['prefix' => 'projectsRacesQuestions'], function () {
        Route::post('add', 'API\ProjectsRaceQuestionCrt@addProjectsRacesQues');                 // Add projectsRaces
        Route::put('edit', 'API\ProjectsRaceQuestionCrt@editProjectsRacesQues');                // Edit projectsRaces
        Route::delete('delete/{id}', 'API\ProjectsRaceQuestionCrt@deleteProjectsRacesQues');    // Delete projectsRaces
        Route::get('showByID/{id}', 'API\ProjectsRaceQuestionCrt@showProjectsRacesByIDQues');// Show projectsRaces By ID
        Route::get('all', 'API\ProjectsRaceQuestionCrt@getAllProjectsRacesQues');          // Show All projectsRaces
    });

    //ProjectsRaceQuestionAnswer
    Route::group(['prefix' => 'projectsRaceQuestionAnswer'], function () {
        Route::post('add', 'API\ProjectsRaceQuestionAnsCtr@addProjectsRaceQuestionAnswer');                 // Add A ProjectsRaceQuestionAnswer
        Route::put('edit', 'API\ProjectsRaceQuestionAnsCtr@editProjectsRaceQuestionAnswer');                // Edit A ProjectsRaceQuestionAnswer
        Route::delete('delete/{id}', 'API\ProjectsRaceQuestionAnsCtr@deleteProjectsRaceQuestionAnswer');    // Delete A ProjectsRaceQuestionAnswer
        Route::get('showByID/{id}', 'API\ProjectsRaceQuestionAnsCtr@showProjectsRaceQuestionAnswerById'); // Show A ProjectsRaceQuestionAnswerBy ID
        Route::get('all', 'API\ProjectsRaceQuestionAnsCtr@getAllProjectsRaceQuestionAnswer');             // Show All ProjectsRaceQuestionAnswer
    });
    //ProjectsRaceAnswerRes
    Route::group(['prefix' => 'projectsRaceAnswer'], function () {
        Route::post('add', 'API\ProjectsRaceAnswerController@addProjectsRaceAnswer');                 // Add ProjectsRaceAnswerRes
        Route::put('edit', 'API\ProjectsRaceAnswerController@editProjectsRaceAnswer');                // Edit ProjectsRaceAnswerRes
        Route::delete('delete/{id}', 'API\ProjectsRaceAnswerController@deleteProjectsRaceAnswer');    // Delete ProjectsRaceAnswerRes
        Route::get('showByID/{id}', 'API\ProjectsRaceAnswerController@showProjectsRaceAnswerByID');// Show ProjectsRaceAnswerRes By ID
        Route::get('all', 'API\ProjectsRaceAnswerController@getAllProjectsRaceAnswer');          // Show All ProjectsRaceAnswerRes
    });
    //CourseLoginRequest
    Route::group(['prefix' => 'projectsRaceLoginRequest'], function () {
        Route::post('add', 'API\ProjectsRaceRequestController@addProjectsRaceLoginRequest');                 // Add ProjectsRaceLoginRequest
        Route::put('edit', 'API\ProjectsRaceRequestController@editProjectsRaceLoginRequest');                // Edit ProjectsRaceLoginRequest
        Route::delete('delete/{id}', 'API\ProjectsRaceRequestController@deleteProjectsRaceLoginRequest');    // Delete ProjectsRaceLoginRequest
        Route::get('showAcceptedLogs/{projectsRaceID}', 'API\ProjectsRaceRequestController@showAcceptedProjectsRaceLoginRequest');// Show Accepted ProjectsRaceLoginRequest
        Route::get('showRejectedLogs', 'API\ProjectsRaceRequestController@showRejectedProjectsRaceLoginRequest');// Show Rejected ProjectsRaceLoginRequest
        Route::put('acceptLoginRequest/{studentID}/{projectsRaceID}', 'API\ProjectsRaceRequestController@acceptProjectsRaceLoginRequest');// Accept ProjectsRaceLoginRequest
        Route::put('rejectedLoginRequest/{studentID}/{projectsRaceID}', 'API\ProjectsRaceRequestController@rejectProjectsRaceLoginRequest');//  Rejected ProjectsRaceLoginRequest
        Route::get('all', 'API\ProjectsRaceRequestController@getAllProjectsRaceLoginRequest');          // Show All ProjectsRaceLoginRequest
        Route::get('showProjectsRaceByID/{projectsRaceID}', 'API\ProjectsRaceRequestController@getAllLoginRequestsByProjectsRaceID');
        Route::get('showStudentByID/{studentID}', 'API\ProjectsRaceRequestController@getAllLoginRequestsByStudentID');

    });

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



