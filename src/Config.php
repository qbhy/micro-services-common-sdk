<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午5:00
 */

namespace Qbhy\MicroServicesCommonSdk;

use Illuminate\Support\Collection;
use Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException;

class Config extends Collection
{
    /**
     * @param string $name
     *
     * @return $this
     * @throws UndefinedAppException
     */
    public function use(string $name)
    {
        $apps = $this->get('apps', []);

        if (isset($apps[$name])) {
            $this->offsetSet('use', $name);
            return $this;
        }

        foreach ($apps as $key => $app) {
            if ($app['id'] == $name) {
                $this->offsetSet('use', $name);
                return $this;
            }
        }

        throw new UndefinedAppException('未定义的应用: ' . $name);
    }

    /**
     * @param null $name
     *
     * @return array
     * @throws UndefinedAppException
     */
    public function getAppConfig($name = null)
    {
        $name = $name ?? $this->get('use', 'default');

        $apps = $this->get('apps', []);

        if (isset($apps[$name])) {
            return $apps[$name];
        }

        foreach ($apps as $key => $app) {
            if ($app['id'] == $name) {
                return $app;
            }
        }

        throw new UndefinedAppException('未定义的应用: ' . $name);
    }

}