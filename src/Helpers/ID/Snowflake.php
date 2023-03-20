<?php

namespace RedJasmine\Support\Helpers\ID;

use Exception;
use RuntimeException;

/**
 * ID 生成
 * Class SnowflakeUID
 */
class Snowflake
{
    //0       0000000000000000000000000000000000000000       000000000000
    //符号位   41位时间戳，大约够69年                           12位序列号 共计+64位
    // 最大 9223372036854775807 19位

    // 已秒确定
    public const EPOCH = 1609430400000; // 时间起始标记点，最近的时间作为基准，一般取系统的最近时间（一旦确定不能变动） 2022-01-01 00:00:00

    public const WORKER_ID_BITS     = 0; // 机器标识位数
    public const DATACENTER_ID_BITS = 0; // 数据中心标识位数
    public const SEQUENCE_BITS      = 12; // 毫秒内自增位
    public const SEQUENCE_DIVISOR   = 1; // 秒 除数 1000 到秒  1  到毫秒

    private int $workerId; // 工作机器ID
    private int $datacenterId; // 数据中心ID
    private int $sequence; // 毫秒内序列

    private int $maxWorkerId        = -1 ^ (-1 << self::WORKER_ID_BITS); // 机器ID最大值
    private int $maxDatacenterId    = -1 ^ (-1 << self::DATACENTER_ID_BITS); // 数据中心ID最大值
    private int $workerIdShift      = self::SEQUENCE_BITS; // 机器ID偏左移位数
    private int $datacenterIdShift  = self::SEQUENCE_BITS + self::WORKER_ID_BITS; // 数据中心ID左移位数
    private int $timestampLeftShift = self::SEQUENCE_BITS + self::WORKER_ID_BITS + self::DATACENTER_ID_BITS; // 时间毫秒左移位数
    private int $sequenceMask       = -1 ^ (-1 << self::SEQUENCE_BITS); // 生成序列的掩码

    private int $lastTimestamp = -1; // 上次生产id时间戳

    /**
     * @throws Exception
     */
    public function __construct(int|null $workerId = null, int|null $datacenterId = null, int $sequence = 0)
    {

        $workerId     = $workerId ?? rand(0, $this->maxWorkerId);
        $datacenterId = $datacenterId ?? rand(0, $this->maxDatacenterId);
        if ($workerId > $this->maxWorkerId || $workerId < 0) {
            throw new RuntimeException("worker Id can't be greater than {$this->maxWorkerId} or less than 0");
        }

        if ($datacenterId > $this->maxDatacenterId || $datacenterId < 0) {
            throw new RuntimeException("datacenter Id can't be greater than {$this->maxDatacenterId} or less than 0");
        }

        $this->workerId     = $workerId;
        $this->datacenterId = $datacenterId;
        $this->sequence     = $sequence;
    }


    private static self|null $self = null;

    public static function getInstance($workerId = 0, $datacenterId = 0) : ?Snowflake
    {

        if (self::$self === null) {
            try {
                self::$self = new self($workerId, $datacenterId);
            } catch (Exception $e) {
            }
        }
        return self::$self;
    }


    /**
     * @throws Exception
     */
    public function nextId() : int
    {
        $timestamp = $this->timeGen();

        if ($timestamp < $this->lastTimestamp) {
            $diffTimestamp = bcsub($this->lastTimestamp, $timestamp);
            throw new RuntimeException("Clock moved backwards.  Refusing to generate id for {$diffTimestamp} milliseconds");
        }

        if ($this->lastTimestamp == $timestamp) {
            $this->sequence = ($this->sequence + 1) & $this->sequenceMask;

            if (0 === $this->sequence) {
                $timestamp = $this->tilNextMillis($this->lastTimestamp);
            }
        } else {
            // php 不是常驻内存的
            $this->sequence = rand(0, (pow(2, self::SEQUENCE_BITS) - 1));
        }

        $this->lastTimestamp = $timestamp;
        $timestamp           = ((floor($timestamp / self::SEQUENCE_DIVISOR) - floor(self::EPOCH / self::SEQUENCE_DIVISOR)));


        return (($timestamp << $this->timestampLeftShift) |
                ($this->datacenterId << $this->datacenterIdShift) |
                ($this->workerId << $this->workerIdShift) |
                $this->sequence);
    }

    protected function tilNextMillis($lastTimestamp) : float
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }

        return $timestamp;
    }

    protected function timeGen() : float
    {
        return floor(microtime(true) * 1000);
    }

}
