<?php
// tests/Smoke/RoutesSmokeTest.php
namespace App\Tests\Smoke;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RoutesSmokeTest extends WebTestCase
{
    private const SKIP_PREFIXES = ['_profiler','_wdt','_debug','_twig','_errors','_ux'];

    public function test_routes_no_500(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false); // pour capter les exceptions et afficher juste le controller
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        $tested = 0;
        $failures = [];

        foreach ($router->getRouteCollection() as $name => $route) {
            foreach (self::SKIP_PREFIXES as $p) { if (str_starts_with($name, $p)) continue 2; }

            $methods = $route->getMethods();
            $method = (empty($methods) || in_array('GET',$methods,true)) ? 'GET' : (in_array('HEAD',$methods,true) ? 'HEAD' : null);
            if (!$method) continue;

            $compiled = $route->compile();
            $vars = $compiled->getPathVariables();
            $reqs = $route->getRequirements();
            $params = [];
            foreach ($vars as $v) { $params[$v] = self::fake($v, $reqs[$v] ?? null); }

            $server = [];
            $host = $route->getHost();
            if ($host) {
                foreach ($compiled->getHostVariables() as $v) { $host = str_replace('{'.$v.'}', self::fake($v, $reqs[$v] ?? null), $host); }
                $server['HTTP_HOST'] = $host;
            }

            $controller = (string)($route->getDefault('_controller') ?? '(no _controller)');

            try {
                $path = $router->generate($name, $params);
            } catch (\Throwable) {
                $tested++;
                $failures[] = sprintf('[%s] %s (gen failed) => EXCEPTION | Controller: %s', $name, $method, $controller);
                continue;
            }

            try {
                $client->request($method, $path, server: $server);
                $status = $client->getResponse()->getStatusCode();
                $tested++;
                if ($status >= 500) {
                    $failures[] = sprintf('[%s] %s %s => HTTP %d | Controller: %s', $name, $method, $path, $status, $controller);
                }
            } catch (NotFoundHttpException) {
                // 404 (ex: entité résolue via param converter mais inexistante) -> on ignore
                $tested++;
                continue;
            } catch (\Throwable) {
                $tested++;
                $failures[] = sprintf('[%s] %s %s => EXCEPTION | Controller: %s', $name, $method, $path, $controller);
                continue;
            }
        }

        self::assertGreaterThan(0, $tested, 'Aucune route testée.');
        if ($failures) { self::fail("Routes en échec (>=500):\n".implode("\n", array_unique($failures))); }
        self::addToAssertionCount(1);
    }

    private static function fake(string $var, ?string $re = null): string
    {
        $v = strtolower($var);
        if ($v === '_locale' || $v === 'locale') return 'en';
        if ($v === 'uuid') return '00000000-0000-0000-0000-000000000000';
        if ($v === 'slug') return 'test';
        if (in_array($v, ['token','code','hash'], true)) return 'test';
        if ($v === 'id' || str_ends_with($v, 'id')) return '1';
        if ($re) {
            if (preg_match('/\\\d\+|^\d\+$/', $re)) return '1';
            if (preg_match('/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/', $re)) {
                return '00000000-0000-0000-0000-000000000000';
            }
        }
        return 'test';
    }
}
