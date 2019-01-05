<?php

namespace PMS\Controllers\Project;

use PMS\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use PMS\TokenDecode\RequestingUserData;
use PMS\Models\Project;
use PMS\Enums\ProjectUserRole;
use Respect\Validation\Validator;
use PMS\Queries\CommonQueries;



class UpdateProjectController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args): Response
    {
        $updatedProject = new Project($request->getParsedBody());
        $projectId = $args['projectId'];
        $userId = RequestingUserData::getUserId($request);

        $this->validator->validate($updatedProject, [
            'name' => Validator::notBlank()->length(1, 30),
            'description' => Validator::notBlank()->length(1, 100),
            'startDate' => Validator::notBlank()->date(),
            'endDate' => Validator::notBlank()->date()
            ]);

        if (!$this->validator->isValid()) {
            return $response->withJson($this->validator->getErrors(), 400);
        }
        
        $project = CommonQueries::findProjectById($this->db, $projectId);

        if ($project == null){
            return $response->withJson(["uncategorized" => "Doesn't exist"], 404);
        }
            
        $userRole = CommonQueries::findUserRole($this->db, $projectId, $userId);

        if ($userRole == ProjectUserRole::USER || $userRole == null){
            return $response->withJson(["uncategorized" => "Insufficient privileges"],403);
        }
        
        $sql = "UPDATE Projects
                SET name=:name, description=:description, startDate=:startDate, endDate=:endDate
                WHERE id=:projectId";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $updatedProject->name);
            $stmt->bindParam("description", $updatedProject->description);
            $stmt->bindParam("startDate", $updatedProject->startDate);
            $stmt->bindParam("endDate", $updatedProject->endDate);
            $stmt->bindParam("projectId", $projectId);
            $stmt->execute();
            return $response->withStatus(204);
        }catch(\PDOException $e) {
            return $response->withJson(['uncategorize' => $e->getMessage()], 400);
        }
    }
}