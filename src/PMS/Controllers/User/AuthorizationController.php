<?php

namespace PMS\Controllers\User;

use Exception;
use Firebase\JWT\JWT;
use PMS\Controllers\BaseController;
use PMS\Models\Token;
use PMS\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

final class AuthorizationController extends BaseController
{
    public function handleRequest(Request $request, Response $response, array $args = null): Response
    {
        $data = $request->getParsedBody();


        if (!isset($data['email']) || !isset($data['password'])) {
            return $response->withJson([
                'error' => 'Invalid parameters'
            ]);
        }

        // Find user in database
        $user = $this->findUserByEmailAndPassword($data['email'], $data['password']);

        // If user doesn't exists return error response
        if (!$user) {
            $data = [
                "uncategorized" => "Password is invalid or user doesn't exist.",
            ];

            return $response->withJson($data, 400);
        }

        return $response->withJson([
            'jwt' => $this->createToken($user)
        ]);
    }

    private function findUserByEmailAndPassword(string $email, string $password): ?User
    {
        $sql = "SELECT * FROM users WHERE email=:email AND password=:password";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("password", $password);
        $stmt->execute();
        $data = $stmt->fetchObject();

        return $data ? new User($data) : null;
    }

    private function createToken(User $user): string
    {
        global $app;
        $jwtConfig = $app->getContainer()['settings']['jwt'];
        $expires = $jwtConfig['expires'];
        $secret = $jwtConfig['secret'];
        $now = new \DateTime();
        $future = new \DateTime($expires);

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "validFor" => $future->getTimeStamp() - $now->getTimeStamp(),
            "user" => $user
        ];

        try {
            $jwt = JWT::encode($payload, $secret);
            $token = new Token([
                "dateExpiration" => $payload['exp'],
                "dateCreated" => $payload['iat'],
                "value" => $jwt,
                "userId" => $user->id
            ]);

        } catch (Exception $e) {
            echo json_encode($e);
            die();
        }

        return $token->value;
    }
}
