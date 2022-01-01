<?php 
namespace App\Http\Middleware;

class CorsMiddleware {

  public function handle($request, \Closure $next)
  {
    if(strpos($request->url(),"oauth/token"))return  $next($request);
    if(strpos($request->url(),"sslcommerz/success-path"))return  $next($request);
    $headers = [
      'Access-Control-Allow-Origin'      => '*',
      'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
      'Access-Control-Allow-Credentials' => 'true',
      'Access-Control-Max-Age'           => '86400',
      'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
  ];

  if ($request->isMethod('OPTIONS'))
  {
      return response()->json('{"method":"OPTIONS"}', 200, $headers);
  }

  $response = $next($request);
  foreach($headers as $key => $value)
  {
      $response->header($key, $value);
  }

  return $response;
}

}