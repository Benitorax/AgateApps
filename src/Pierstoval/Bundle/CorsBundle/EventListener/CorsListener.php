<?php

namespace Pierstoval\Bundle\CorsBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Pierstoval\Bundle\CorsBundle\Options\ResolverInterface;

class CorsListener
{
    /**
     * Simple headers as defined in the spec should always be accepted
     */
    protected static $simpleHeaders = array(
        'accept',
        'accept-language',
        'content-language',
        'origin',
    );

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var array */
    protected $options;

    /** @var ResolverInterface */
    protected $configurationResolver;

    public function __construct(EventDispatcherInterface $dispatcher, ResolverInterface $configurationResolver)
    {
        $this->dispatcher = $dispatcher;
        $this->configurationResolver = $configurationResolver;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        // skip if not a CORS request
        if (!$request->headers->has('Origin') || $request->headers->get('Origin') == $request->getSchemeAndHttpHost()) {
            return;
        }

        $options = $this->configurationResolver->getOptions($request);

        if (!$options) {
            return;
        }

        // perform preflight checks
        if ('OPTIONS' === $request->getMethod()) {
            $event->setResponse($this->getPreflightResponse($request, $options));

            return;
        }

        $this->dispatcher->addListener('kernel.response', array($this, 'onKernelResponse'));
          $this->options = $options;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();
        $request = $event->getRequest();
        // add CORS response headers
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        if ($this->options['allow_credentials']) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }
        if ($this->options['expose_headers']) {
            $response->headers->set('Access-Control-Expose-Headers', strtolower(implode(', ', $this->options['expose_headers'])));
        }
    }

    protected function getPreflightResponse(Request $request, array $options)
    {
        $response = new Response();

        if ($options['allow_credentials']) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }
        if ($options['allow_methods']) {
            $response->headers->set('Access-Control-Allow-Methods', implode(', ', $options['allow_methods']));
        }
        if ($options['allow_headers']) {
            $headers = $options['allow_headers'] === true
                ? $request->headers->get('Access-Control-Request-Headers')
                : implode(', ', $options['allow_headers']);
            $response->headers->set('Access-Control-Allow-Headers', $headers);
        }
        if ($options['max_age']) {
            $response->headers->set('Access-Control-Max-Age', $options['max_age']);
        }

        if (!$this->checkOrigin($request, $options)) {
            $response->headers->set('Access-Control-Allow-Origin', 'null');

            $response->setContent($response->getContent());

            return $response;
        }

        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));

        // check request method
        if (!in_array(strtoupper($request->headers->get('Access-Control-Request-Method')), $options['allow_methods'], true)) {
            $response->setStatusCode(405);

            return $response;
        }

        /**
         * We have to allow the header in the case-set as we received it by the client.
         * Firefox f.e. sends the LINK method as "Link", and we have to allow it like this or the browser will deny the
         * request.
         */
        if (!in_array($request->headers->get('Access-Control-Request-Method'), $options['allow_methods'], true)) {
            $options['allow_methods'][] = $request->headers->get('Access-Control-Request-Method');
            $response->headers->set('Access-Control-Allow-Methods', implode(', ', $options['allow_methods']));
        }

        // check request headers
        $headers = $request->headers->get('Access-Control-Request-Headers');
        if ($options['allow_headers'] !== true && $headers) {
            $headers = trim(strtolower($headers));
            foreach (preg_split('{, *}', $headers) as $header) {
                if (in_array($header, self::$simpleHeaders, true)) {
                    continue;
                }
                if (!in_array($header, $options['allow_headers'], true)) {
                    $response->setStatusCode(400);
                    $response->setContent('Unauthorized header '.$header);
                    break;
                }
            }
        }

        return $response;
    }

    protected function checkOrigin(Request $request, array $options)
    {
        // check origin
        $origin = $request->headers->get('Origin');

        if (
            $options['allow_origin'] === true
            || in_array($origin, $options['allow_origin'])
            || in_array(preg_replace('~https?://~isUu', '', $origin), $options['allow_origin'])
        ) {
            return true;
        }

        return false;
    }
}