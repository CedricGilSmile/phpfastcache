<?php
/**
 *
 * This file is part of phpFastCache.
 *
 * @license MIT License (MIT)
 *
 * For full copyright and license information, please see the docs/CREDITS.txt file.
 *
 * @author  Georges.L (Geolim4)  <contact@geolim4.com>
 *
 */
declare(strict_types=1);

namespace Phpfastcache\Cluster\Drivers\RandomReplication;

use Phpfastcache\Cluster\Drivers\MasterSlaveReplication\MasterSlaveReplicationCluster;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;
use Phpfastcache\Exceptions\PhpfastcacheDriverConnectException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use ReflectionException;
use ReflectionMethod;


/**
 * Class MasterSlaveReplicationCluster
 * @package Phpfastcache\Cluster\Drivers\MasterSlaveReplication
 */
class RandomReplicationCluster extends MasterSlaveReplicationCluster
{
    /**
     * MasterSlaveReplicationCluster constructor.
     * @param string $clusterName
     * @param ExtendedCacheItemPoolInterface ...$driverPools
     * @throws PhpfastcacheInvalidArgumentException
     * @throws PhpfastcacheDriverCheckException
     * @throws PhpfastcacheDriverConnectException
     * @throws PhpfastcacheInvalidConfigurationException
     * @throws ReflectionException
     */
    public function __construct(string $clusterName, ExtendedCacheItemPoolInterface ...$driverPools)
    {
        (new ReflectionMethod(get_parent_class(get_parent_class($this)), __FUNCTION__))
            ->invoke($this, $clusterName, ...$driverPools);
        $randomPool = $driverPools[random_int(0, count($driverPools) - 1)];
        $this->clusterPools = [$randomPool];
    }

    /**
     * @param callable $operation
     * @return mixed
     */
    protected function makeOperation(callable $operation)
    {
        return $operation($this->getMasterPool());
    }
}
