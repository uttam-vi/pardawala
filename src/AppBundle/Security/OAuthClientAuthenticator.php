<?php

namespace FM\ApiBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\HttpUtils;

class OAuthClientAuthenticator implements SimplePreAuthenticatorInterface
{
    
    protected $httpUtils;

    protected $logger;
    
    /**
     * 
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     */
    public function __construct(HttpUtils $httpUtils, $logger)
    {
        $this->httpUtils = $httpUtils;
        
        $this->logger = $logger;
    }
    
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $providerKey
     * @return \Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken
     * @throws BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        // look for an apikey query parameter
        $apiKey = $request->query->get('apikey');

        // or if you want to use an "apikey" header, then do something like this:
        // $apiKey = $request->headers->get('apikey');

        if (!$apiKey) {
            throw new BadCredentialsException();

            // or to just skip api key authentication
            // return null;
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {

        if (!$userProvider instanceof \FM\ApiBundle\Security\OAuthClientUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of OAuthClientUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();
        $username = $userProvider->getUsernameForApiKey($apiKey);

        
        if (!$username) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiKey)
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        $this->logger->error('in authenticateToken');

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        
        return new Response(
            // this contains information about *why* authentication failed
            // use it, or return your own message
            strtr($exception->getMessageKey(), $exception->getMessageData()),
            401
        );
    }
}