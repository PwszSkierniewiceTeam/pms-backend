<?php

namespace PMS\Controllers\User;

use PMS\Controllers\BaseController;
use PMS\Models\Project;
use PMS\Models\User;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

final class RegisterController extends BaseController
{
    public function handleRequest(Request $request, Response $response): Response
    {
        $user = new User($request->getParsedBody());

        $this->validator->validate($user, [
            'name' => Validator::notBlank()->length(1, 100),
            'surname' => Validator::notBlank()->length(1, 100),
            'email' => Validator::notBlank()->email(),
            'password' => Validator::notBlank()->length(6)
        ]);

        if ($this->validator->isValid()) {
            // Insert user to database
            $sql = "INSERT INTO users (name, surname, email, password) 
                    VALUES            (:name, :surname, :email, :password)";
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam('name', $user->name);
                $stmt->bindParam('surname', $user->surname);
                $stmt->bindParam('email', $user->email);
                $stmt->bindParam('password', $user->password);
                $stmt->execute();
            } catch (\PDOException $e) {
                // Email already exists
                return $response->withJson(['uncategorized' => 'E-mail already exists in database.'], 400);
            }

            // Insert was successful
            return $response->withStatus(204);
        }

        // Validation failed
        return $response->withJson($this->validator->getErrors(), 400);
    }
}
