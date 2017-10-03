<?php

namespace AppBundle\Security;

use AppBundle\Contract\Service\Token\IJWTManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use InvalidArgumentException;

/**
 * Class JwtKeyAuthenticator
 * @package AppBundle\Security
 */
class JwtKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /** @var SerializerInterface $serializer */
    protected $serializer;

    /** @var IJWTManager $tokenManager */
    protected $tokenManager;

    /**
     * JwtKeyAuthenticator constructor.
     * @param SerializerInterface $serializer
     * @param IJWTManager $tokenManager
     */
    public function __construct(
        SerializerInterface $serializer,
        IJWTManager $tokenManager
    )
    {
        $this->serializer = $serializer;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        $token = null;

        $authHeader = $request->headers->get('Authorization');
        if (strpos(mb_strtolower($authHeader), 'bearer') === 0) {
            $token = substr($authHeader, mb_strlen('Bearer '));
        }

        if (is_null($token)) {
             return null;
        }

        return new PreAuthenticatedToken(
            'anon.',
            $token,
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof JwtKeyUserProvider) {
            throw new InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $jwtToken = $token->getCredentials();
        $userId = $userProvider->parseTokenAndReturnId($jwtToken);

        if (!$userId) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException(
                sprintf('JWT ID key "%s" does not exist.', $userId)
            );
        }

        $user = $userProvider->loadUserByUsername($userId);

        return new PreAuthenticatedToken(
            $user,
            $jwtToken,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'success' => false,
            'data' => null,
            'errors' => strtr($exception->getMessageKey(), $exception->getMessageData()),
            'meta' => null
        ];
        $json = $this->serializer->serialize($data, 'json');

        return new JsonResponse($json, JsonResponse::HTTP_BAD_REQUEST, [], true);
    }
}
