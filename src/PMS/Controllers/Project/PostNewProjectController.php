<?php
/**
 * Created by PhpStorm.
 * User: Grimneon
 * Date: 05.11.2018
 * Time: 21:44
 */

namespace PMS\Controllers\Project;


use PMS\Controllers\BaseController;
use PMS\Models\Project;
use PMS\TokenDecode\RequestingUserData;
use PMS\Enums\ProjectUserRole;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

class PostNewProjectController extends BaseController
{

    public function handleRequest(Request $request, Response $response, array $args = null): Response
    {
        $project = new Project($request->getParsedBody());
        $userId = RequestingUserData::getUserId($request);

        $this->validator->validate($project, [
            'name' => Validator::notBlank()->length(1, 30),
            'description' => Validator::notBlank()->length(1, 100),
            'startDate' => Validator::notBlank()->date(),
            'endDate' => Validator::notBlank()->date()
            ]);

        // $userRole = ProjectUserRole::ADMIN;
        if ($this->validator->isValid()) {
            $sql = "SET @uuid = uuid();";
            $sql .= "INSERT INTO Projects (id ,name, description, startDate, endDate) 
                    VALUES        (@uuid, :name, :description, :startDate, :endDate);";
            $sql .= "INSERT INTO UsersProjects (projectId, userId, role)
                    VALUES  (@uuid, :userId, :userRole);";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('name', $project->name);
                $stmt->bindParam('description', $project->description);
                $stmt->bindParam('startDate', date("Y-m-d", strtotime($project->startDate)));
                $stmt->bindParam('endDate', date("Y-m-d", strtotime($project->endDate)));
                $stmt->bindParam('userId', $userId);
                $stmt->bindParam('userRole', $userRole = ProjectUserRole::ADMIN);
                $stmt->execute();
            } catch (\PDOException $e) {
                return $response->withJson(['uncategorized' => $e->getMessage()], 400);
            }
            return $response->withStatus(204);
        }

        return $response->withJson($this->validator->getErrors(), 400);
    }
}
