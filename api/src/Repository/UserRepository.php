<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;

abstract class UserRepository extends BaseRepository
{
    protected static function entityClass():string
    {
        return User::class;
    }

    public function findOneByEmailOrFail(string $email):User
    {
        if(null === $user = $this->objectRepository->findOneBy(['email'=>$email])){
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    /**
     *
     * @param User $user
     * @return void
     */
    public function save(User $user):void{
        $this->saveEntity($user);
    }


    
    /** 
     * @param User $user
     * @return void
     */
    public function remove(User $user):void{
        $this->removeEntity($user);
    }

    

}