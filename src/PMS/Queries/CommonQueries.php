<?php

namespace PMS\Queries;

use PMS\Models\Project;

class CommonQueries
{
    public static function findProjectById( $db ,string $projectId) : ?Project
    {
        $sql = "SELECT * FROM Projects WHERE id=:projectId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? new Project($data) : null;
    }

    public static function findUserRole($db, string $projectId, string $userId) : ?int
    {
        $sql = "SELECT UsersProjects.role as userRole FROM UsersProjects WHERE projectId=:projectId AND userId=:userId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->bindParam("userId", $userId);
        $stmt->execute();
        $data = $stmt->fetch();
        
        return $data? intval($data['userRole']) : null;
    }

}