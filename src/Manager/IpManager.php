<?php

namespace App\Manager;

use App\Entity\Ip;
use App\Repository\IpRepository;

class IpManager implements IpManagerInterface
{
    /**
     * @var IpRepository
     */
    private $ipRepository;

    public function __construct(IpRepository $ipRepository)
    {
        $this->ipRepository = $ipRepository;
    }

    /**
     * @return IpRepository
     */
    public function getRepository()
    {
        return $this->ipRepository;
    }

    public function save($ip)
    {
        if($ip instanceof Ip){
            if(is_null($ip->getId())){
                $this->ipRepository->getEm()->persist($ip);
            }
            $this->ipRepository->getEm()->flush();
            return $ip;
        }
        return null;
    }

    public function delete($ip)
    {
        if($ip instanceof Ip){
            $this->ipRepository->getEm()->remove($ip);
            $this->ipRepository->getEm()->flush();
            return true;
        }
        return false;
    }
}