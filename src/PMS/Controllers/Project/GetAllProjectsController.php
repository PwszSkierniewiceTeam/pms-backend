<?php
/**
 * Created by PhpStorm.
 * User: Grimneon
 * Date: 05.11.2018
 * Time: 16:50
 */

namespace PMS\Controllers\Project;


use PMS\Controllers\BaseController;
use PMS\Models\Project;
use PMS\TokenDecode\RequestingUserData;
use Slim\Http\Request;
use Slim\Http\Response;

class GetAllProjectsController extends BaseController
{

    public function handleRequest(Request $request, Response $response, $args = null): Response
    {
        $userId = (string)RequestingUserData::getUserId($request);
        $projects = $this->findAllProjects($userId);

        if (!empty($projects)){
            return $response->withJson($projects, 200);
        }else{
            return $response->withJson(['No projects available'], 200);
        }
    }

    private function findAllProjects(string $userId)
    {
        $projects = array();
        $sql = "SELECT Projects.id, Projects.name, Projects.description, Projects.startDate, Projects.endDate, UsersProjects.role as userRole 
                FROM Projects 
                INNER JOIN UsersProjects ON Projects.id = UsersProjects.projectId AND UsersProjects.userId = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('userId', $userId);
        $stmt->execute();
        
        while ($row = $stmt->fetchObject()){
            $projects[] = new Project($row);
        }
        return $projects;
    }
}