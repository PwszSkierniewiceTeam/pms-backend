<?php


namespace PMS\Queries;


use PMS\Models\Project;
use PMS\Models\Task;
use PMS\Models\User;


class CommonQueries
{

    public static function findUserById($db, string $userId): ?User
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $userId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data ? new User($data) : null;
    }


    public static function findProjectById( $db ,string $projectId) : ?Project
    {
        $sql = "SELECT * FROM projects WHERE id=:projectId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? new Project($data) : null;
    }
    public static function findTaskById( $db ,string $taskId) : ?Task
    {
        $sql = "SELECT * FROM tasks WHERE id=:taskId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("taskId", $taskId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? new Task($data) : null;
    }

    public static function findProjectIdByTaskId( $db ,string $taskId)
    {
        $sql = "SELECT projectId FROM tasks WHERE id=:taskId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("taskId", $taskId);
        $stmt->execute();
        $data = $stmt->fetch()['projectId'];

        return $data;
    }

    public static function findUserRole($db, string $projectId, string $userId)// : ?int
    {
        $sql = "SELECT role FROM usersprojects WHERE projectId=:projectId AND userId=:userId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("projectId", $projectId);
        $stmt->bindParam("userId", $userId);
        $stmt->execute();
        $data = $stmt->fetch()['role'];

        return $data;//? intval($data['userRole']) : null;
    }

    public static  function UserInTask($db, string $userId, string $taskId): ?bool
    {
        $sql = "SELECT * FROM userstasks WHERE taskId=:taskId AND userId=:userId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("taskId", $taskId);
        $stmt->bindParam("userId", $userId);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data? true : false;
    }

}
