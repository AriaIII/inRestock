<?php
// /src/AppBundle/Event/Listener/JWTCreatedListener.php
namespace App\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
class JWTListener
{
    /**
 * @param RequestStack $requestStack
 */
public function __construct(RequestStack $requestStack)
{
    $this->requestStack = $requestStack;
}
    /**
     * Replaces the data in the generated Token
     *
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \App\Entity\AppUser */
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();
    
        $payload       = $event->getData();
        $payload['userId'] = $user->getId();
    
        $event->setData($payload);
        
        $header        = $event->getHeader();
        $header['cty'] = 'JWT';
    
        $event->setHeader($header);
    }
}