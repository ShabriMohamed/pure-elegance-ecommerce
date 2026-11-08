<?php

// Read by Laravel's TrustProxies middleware. Behind an HTTPS tunnel or load
// balancer, set TRUSTED_PROXIES (e.g. "*") so X-Forwarded-Proto is honoured and
// asset URLs come out as https. Unset = no proxy trusted.
return [
    'proxies' => env('TRUSTED_PROXIES'),
];
