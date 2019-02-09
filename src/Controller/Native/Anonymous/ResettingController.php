<?php

namespace App\Controller\Native\Anonymous;

use App\Entity\User;
use App\Handler\Native\AnonymousRequestHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;


/**
 * @Route("/resetting")
 */
class ResettingController
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var AnonymousRequestHandlerInterface
     */
    private $requestHandler;


    public function __construct(AnonymousRequestHandlerInterface $requestHandler,
                                RouterInterface $router,
                                Environment $twig,
                                TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->requestHandler = $requestHandler;
    }

    /**
     * @Route("/request", name="request_resetting")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function request(Request $request)
    {
        // création d'un formulaire "à la volée", afin que l'internaute puisse renseigner son mail
        $form = $this->requestHandler->createFormRequest();
        /** @var User $user */
        $user = $this->requestHandler->findUser($form->getData()['email']);

        $process = $this->requestHandler->request($form,$request);
        if (is_bool($process) && $process) {
            $bodyMail = $this->requestHandler->createBodyMail($user);
            $this->requestHandler->sendMail('from@email.com', $user->getEmail(), $this->translator->trans('resetting.process.mail.subject',[],'security'), $bodyMail);
            $request->getSession()->getFlashBag()->add('success', $this->translator->trans('resetting.request.result.success',[],'security'));

            return new RedirectResponse($this->router->generate('security_login'));
        }elseif (is_int($process) && $process == -1){
            $request->getSession()->getFlashBag()->add('warning', $this->translator->trans('resetting.request.result.warning',[],'security'));
            return new RedirectResponse($this->router->generate('request_resetting'));
        }

        $html = $this->twig->render('Anonymous/resetting/request.html.twig', [
            'form' => $form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Route("/{id}/{token}", name="resetting")
     * @param User $user
     * @param $token
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function resetting(User $user, $token, Request $request)
    {
        // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 10 minutes
        $this->requestHandler->checkTime($user,$token);
        $form = $this->requestHandler->createFormReset($user);

        if($this->requestHandler->reset($form,$request,$user))
        {
            $request->getSession()->getFlashBag()->add('success', $this->translator->trans('resetting.process.success',[],'security'));

            return new RedirectResponse($this->router->generate('security_login'));

        }

        $html = $this->twig->render('Anonymous/resetting/index.html.twig', [
            'form' => $form->createView()
        ]);

         return new Response($html);

    }

    /**
     * si supérieur à 10min, retourne false
     * sinon retourne false
     * @param \Datetime|null $passwordRequestedAt
     * @return bool
     */
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

}
