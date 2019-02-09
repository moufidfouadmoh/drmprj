<?php

namespace App\Handler\Native;


use App\Entity\User;
use App\Event\Mailer\Mailer;
use App\Form\Type\ResettingType;
use App\Manager\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnonymousRequestHandler implements AnonymousRequestHandlerInterface
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var FormFactoryInterface
     */
    private $form;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    public function __construct(UserManagerInterface $userManager,
                                Mailer $mailer,FormFactoryInterface $form,
                                UserPasswordEncoderInterface $encoder,
                                TokenGeneratorInterface $tokenGenerator)
    {
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->form = $form;
        $this->encoder = $encoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function findUser($value)
    {
        $user = $this->userManager->getUserByUsernameOrEmail($value);
        return $user;
    }
    public function request(Form $form,Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->userManager->getUserByUsernameOrEmail($form->getData()['email']);
            if (!$user) {
                return -1;
            }
            $user->setToken($this->tokenGenerator->generateToken());
            $user->setPasswordRequestedAt(new \Datetime());
            $save = $this->userManager->save($user);
            return !is_null($save);
        }
    }

    public function reset(Form $form,Request $request,User $user)
    {
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setToken(null);
            $user->setPasswordRequestedAt(null);

            $save = $this->userManager->save($user);
            return !is_null($save);
        }

        return false;
    }

    public function createBodyMail(User $user)
    {
        $bodyMail = $this->mailer->createBodyMail('resetting/mail.html.twig', [
            'user' => $user
        ]);

        return $bodyMail;
    }

    public function createFormRequest()
    {
        $form = $this->form->createBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                ]
            ])
            ->getForm();

        return $form;
    }

    public function createFormReset(User $user)
    {
        $form = $this->form->create(ResettingType::class, $user);
        return $form;
    }

    public function checkTime(User $user,$token)
    {
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw new AccessDeniedHttpException();
        }
    }

    // si supérieur à 10min, retourne false
    // sinon retourne false
    private function isRequestInTime(\Datetime $passwordRequestedAt = null)
    {
        if ($passwordRequestedAt === null)
        {
            return false;
        }

        $now = new \DateTime();
        $interval = $now->getTimestamp() - $passwordRequestedAt->getTimestamp();

        $daySeconds = 60 * 10;
        $response = $interval > $daySeconds ? false : $reponse = true;
        return $response;
    }

    public function sendMail($from, $to, $subject, $body)
    {
        $this->mailer->sendMessage($from, $to, $subject, $body);
    }
}