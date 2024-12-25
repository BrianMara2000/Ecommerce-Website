<?php
return [
  /*
|--------------------------------------------------------------------------
| Laravel CORS Configuration
|--------------------------------------------------------------------------
|
| Define your CORS settings here. These settings determine which
| cross-origin requests are allowed to access your application.
|
*/

  'paths' => ['api/*', '*'], // Include all paths you want CORS to apply to.
  'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, etc.)
  'allowed_origins' => ['*'], // Allow all origins, or specify frontend URL(s)
  'allowed_origins_patterns' => [],
  'allowed_headers' => ['*'], // Allow all headers
  'exposed_headers' => [],
  'max_age' => 0, // Cache duration for CORS preflight requests
  'supports_credentials' => false, // Set to true if you use cookies/auth headers
];
