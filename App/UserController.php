<?php


class UserController{

    public function __construct(
        private UsersGateway $user
    )
    {
    }

    /**
     * Handle the incoming request
     */
    public function processRequest(string $method, ?string $id){
        if($id){
            $this->respondWithResource($method, $id);
        }else{
            $this->respondWithCollection($method);
        }
    }


    /**
     * return a resource for a single resource
     */
    public function respondWithResource(string $method, string $id){
        switch($method){
            case "GET":
                $this->user->get($id);
            break;

            case "PATCH":
                $this->user->update($id, (array) json_decode(file_get_contents('php://input'), true));
            break;

            case "DELETE":
                $this->user->delete($id);
            break;

            default:
                Response::methodNotAllowed();
        }
    }

    /**
     * Return response global to all the records
     */
    public function respondWithCollection(string $method){
        switch($method){
            case "GET":
                $this->user->getAll();
            break;

            case "POST":
                $this->user->create((array) json_decode(file_get_contents("php://input"), true));
            break;

            case "DELETE":
                $this->user->deleteAll();
            break;

            default:
                Response::methodNotAllowed();
        }
    }

}