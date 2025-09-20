<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Support\Facades\Log;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $response = $next($request);

        if (
            config('app.minify_html', false) &&
            !app()->environment(['local', 'testing']) &&
            $response instanceof Response &&
            $this->isHtmlResponse($response)
        ) {
            $content = $response->getContent();

            if (!is_string($content) || empty($content)) {
                return $response;
            }

            try {
                $minifiedContent = $this->minifyHtml($content);

                $response->setContent($minifiedContent);
                $response->headers->set('X-Minified', 'true');

                Log::info('HTML minified successfully', [
                    'original_size' => strlen($content),
                    'minified_size' => strlen($minifiedContent),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip()
                ]);

            } catch (\Exception $e) {
                Log::error('HTML minification failed', [
                    'error' => $e->getMessage(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip()
                ]);
            }
        }

        return $response;
    }

    protected function isHtmlResponse($response): bool
    {
        $contentType = $response->headers->get('Content-Type');
        return $contentType && strpos($contentType, 'text/html') !== false;
    }

    protected function minifyHtml($html): string
    {
        // Hanya minify dasar — tidak aman untuk <pre>, <textarea>, <script>, <style>
        $search = [
            '/\>[^\S ]+/s',      // strip whitespaces after tags
            '/[^\S ]+\</s',      // strip whitespaces before tags
            '/(\s)+/s',          // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/', // Remove HTML comments
        ];

        $replace = ['>', '<', '\\1', ''];

        return preg_replace($search, $replace, $html);
    }
}