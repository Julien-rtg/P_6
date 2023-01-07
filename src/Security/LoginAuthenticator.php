<?php

namespace App\Security;

use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $userPasswordHasher;

    public function __construct(private UrlGeneratorInterface $urlGenerator, public UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function authenticate(Request $request): Passport
    {
        $login = $request->request->get('login', '');
        $user = $this->utilisateurRepository->findOneBy(['login' => $login]);
        if(isset($user) && $user){
            $request->getSession()->set(Security::LAST_USERNAME, $login);
            if (!$this->userPasswordHasher->isPasswordValid($user, $request->request->get('password', ''))) { // bad password
                throw new AuthenticationException('Email ou mot de passe incorrect.');
            }else if (!$user->isVerified() && $this->userPasswordHasher->isPasswordValid($user, $request->request->get('password', ''))){ // good password and not verified
                throw new AuthenticationException('Veuillez valider votre compte.');
            }else {
                return new Passport(
                    new UserBadge($login),
                    new PasswordCredentials($request->request->get('password', '')),
                    [
                        new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                    ]
                );
            }
        }else {
            throw new AuthenticationException('Email ou mot de passe incorrect.');
        }
    }
    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
