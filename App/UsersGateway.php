<?php


class UsersGateway{

    private PDO $con;

    public function __construct(DB $db)
    {
        $this->con = $db->getConnection();        
    }


    /**
     * Get all the users records
     */
    public function getAll(){
        $stmt = $this->con->query('SELECT * FROM user');
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if($row !== false){
                array_push($data, $row);
            }
        }

        if(sizeof($data) == 0){
            Response::ClientError("No Records", "No records were found");
        }

        echo json_encode($data);
    }


    /**
     * Check if a user exists
     */
    public function exists(string $id){
        $stmt = $this->con->prepare('SELECT * FROM `user` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->fetch(PDO::FETCH_ASSOC) !== false) ? true : false;
    }

    /**
     * Get a user by Id
     */
    public function get(string $id){
        $stmt = $this->con->prepare('SELECT * FROM `user` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data !== false){
            Response::success('Fetched', 'Successfully', $data, $id);
        }

        Response::notFound($id);
    }


    /**
     * Create new user
     */
    public function create(array $data){
        $stmt = $this->con->prepare(
            "INSERT INTO user (name, username, email, password)
            VALUES (:name, :username, :email, :password)"
        );

        $stmt->bindValue(":name", $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(":username", $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(":password", $data['password'], PDO::PARAM_STR_CHAR);

        
        if($stmt->execute()){
            Response::success('Success', "{$data['name']} Inserted Successfully.", $data, $this->con->lastInsertId());
        }else{
            Response::ClientError();
        }

    }

    /**
     * Update existing user
     */
    public function update(string $id, array $new, array $current = null){
        if(! $this->exists($id)){
            Response::notFound($id);
        }

        $stmt = $this->con->prepare(
            'UPDATE user 
            SET name = :name, username = :username, email = :email, password = :password
            WHERE id = :id'
        );

        $stmt->bindValue(':name', $new['name'], PDO::PARAM_STR);
        $stmt->bindValue(':username', $new['username'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $new['email'], PDO::PARAM_STR);
        $stmt->bindValue(':password', $new['password'], PDO::PARAM_STR);
        $stmt->bindValue(':id', ($current['id']) ?? $id, PDO::PARAM_INT);

        $stmt->execute();

        if($stmt->rowCount() == 0){
            Response::ClientError('Nothing to Update', 'Already Updated');
        }else{
            Response::success('Updated', "User {$id} Updated Successfully");
        }
    }


    /**
     * Delete a user by id
     */
    public function delete(string $id){
        $stmt = $this->con->prepare(
            'DELETE FROM user WHERE id = :id'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();

        if($stmt->rowCount() == 0){
            Response::notFound($id);
        }else{
            Response::success('Delete', "User {$id} Deleted Successfully");
        }
    }


    /**
     * Delete all users
     */
    public function deleteAll(){
        $stmt = $this->con->prepare(
            'DELETE FROM user'
        );

        $stmt->execute();

        if($stmt->rowCount() == 0){
            Response::ClientError("Empty Table", "There are no records to delete");
        }else{
            Response::ClientError("Deleted", "All user records deletes successfuly");
        }
    }

}