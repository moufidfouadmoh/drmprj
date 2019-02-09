<?php

namespace App\Handler\Native;


use App\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

interface AnonymousRequestHandlerInterface
{
    public function findUser($value);
    public function request(Form $form,Request $request);
    public function reset(Form $form,Request $request,User $user);
    public function checkTime(User $user,$token);
    public function createBodyMail(User $user);
    public function sendMail($from,$to,$subject,$body);
    public function createFormRequest();
    public function createFormReset(User $user);
}