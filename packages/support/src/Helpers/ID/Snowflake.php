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
    // 时间起始标记点，最近的时间作为基准，一般取系统的最近时间（一旦确定不能变动）
    public const int EPOCH = 1609430400000; // 2022-01-01 00:00:00

    // 默认位分配
    public const int DEFAULT_WORKER_ID_BITS     = 5; // 机器标识位数
    public const int DEFAULT_DATACENTER_ID_BITS = 5; // 数据中心标识位数
    public const int DEFAULT_SEQUENCE_BITS      = 12; // 毫秒内自增位
    public const int DEFAULT_SEQUENCE_DIVISOR   = 1; // 秒 除数 1000 到秒  1  到毫秒

    // 53位ID配置
    public const int FIFTY_THREE_BIT_SEQUENCE_BITS = 12;

    // 64位ID配置

    public const int SIXTY_FOUR_BIT_WORKER_ID_BITS     = 5;
    public const int SIXTY_FOUR_BIT_DATACENTER_ID_BITS = 5;
    public const int SIXTY_FOUR_BIT_SEQUENCE_BITS      = 12;

    private int $workerId; // 工作机器ID
    private int $datacenterId; // 数据中心ID
    private int $sequence; // 毫秒内序列


    private int $workerIdBits;
    private int $datacenterIdBits;
    private int $sequenceBits;
    private int $sequenceDivisor;

    private int $maxWorkerId;
    private int $maxDatacenterId;
    private int $workerIdShift;
    private int $datacenterIdShift;
    private int $timestampLeftShift;
    private int $sequenceMask;

    private int $lastTimestamp = -1; // 上次生产id时间戳

    /**
     * NumberGenerator constructor.
     *
     * @param  int|null  $workerId  工作机器ID，通常用于区分不同的机器或服务实例，默认为随机值
     * @param  int|null  $datacenterId  数据中心ID，用于区分不同的数据中心，默认为随机值
     * @param  int  $sequence  毫秒内序列，默认为0
     * @param  string  $idType  ID类型，可选 '53bit' 或 '64bit'
     *
     * @throws Exception
     */
    public function __construct(
        int|null $workerId = null,
        int|null $datacenterId = null,
        int $sequence = 0,
        string $idType = '64bit'
    ) {
        switch ($idType) {
            case '53bit':

                $this->workerIdBits     = 0;
                $this->datacenterIdBits = 0;
                $this->sequenceBits     = self::FIFTY_THREE_BIT_SEQUENCE_BITS;
                break;
            case '64bit':

                $this->workerIdBits     = self::SIXTY_FOUR_BIT_WORKER_ID_BITS;
                $this->datacenterIdBits = self::SIXTY_FOUR_BIT_DATACENTER_ID_BITS;
                $this->sequenceBits     = self::SIXTY_FOUR_BIT_SEQUENCE_BITS;
                break;
            default:
                throw new InvalidArgumentException('Invalid ID type specified. Use "53bit" or "64bit".');
        }

        $this->sequenceDivisor = self::DEFAULT_SEQUENCE_DIVISOR;

        $this->workerId     = $workerId ?? rand(0, $this->maxWorkerId());
        $this->datacenterId = $datacenterId ?? rand(0, $this->maxDatacenterId());

        if ($this->workerId < 0 || $this->workerId > $this->maxWorkerId()) {
            throw new RuntimeException("worker Id can't be greater than {$this->maxWorkerId()} or less than 0");
        }

        if ($this->datacenterId < 0 || $this->datacenterId > $this->maxDatacenterId()) {
            throw new RuntimeException("datacenter Id can't be greater than {$this->maxDatacenterId()} or less than 0");
        }

        $this->sequence = $sequence;

        $this->calculateShiftsAndMasks();
    }

    private function calculateShiftsAndMasks() : void
    {
        $this->maxWorkerId        = -1 ^ (-1 << $this->workerIdBits);
        $this->maxDatacenterId    = -1 ^ (-1 << $this->datacenterIdBits);
        $this->workerIdShift      = $this->sequenceBits;
        $this->datacenterIdShift  = $this->sequenceBits + $this->workerIdBits;
        $this->timestampLeftShift = $this->sequenceBits + $this->workerIdBits + $this->datacenterIdBits;
        $this->sequenceMask       = -1 ^ (-1 << $this->sequenceBits);

    }

    private static array|null $instance = null;

    /**
     * 获取单例实例
     *
     * @param  int|null  $workerId  工作机器ID，通常用于区分不同的机器或服务实例，默认为随机值
     * @param  int|null  $datacenterId  数据中心ID，用于区分不同的数据中心，默认为随机值
     * @param  string  $idType  ID类型，可选 '53bit' 或 '64bit'
     *
     * @return self|null
     */
    public static function getInstance(
        int|null $workerId = null,
        int|null $datacenterId = null,
        string $idType = '64bit'
    ) : ?self {
        if ((self::$instance[$idType] ?? null) === null) {
            try {
                self::$instance[$idType] = new self($workerId, $datacenterId, 0, $idType);
            } catch (Exception $e) {
                // 处理异常
            }
        }
        return self::$instance[$idType];
    }


    public static function buildId(
        int|null $workerId = null,
        int|null $datacenterId = null,
        string $idType = '53bit'
    ) : int {
        // 调用getInstance方法获取Snowflake ID生成器的实例，并调用nextId方法生成并返回一个新的ID
        return static::getInstance($workerId, $datacenterId, $idType)->nextId();
    }

    public static function longId(
        int|null $workerId = null,
        int|null $datacenterId = null
    ) : int {
        return static::getInstance($workerId, $datacenterId, '64bit')->nextId();
    }

    public static function shortId(
        int|null $workerId = null,
        int|null $datacenterId = null
    ) : int {
        return static::getInstance($workerId, $datacenterId, '53bit')->nextId();
    }

    /**
     * 生成下一个唯一的ID
     *
     * @return int 生成的ID
     * @throws Exception
     */
    public function nextId() : int
    {
        $timestamp = $this->timeGen();

        if ($timestamp < $this->lastTimestamp) {
            $diffTimestamp = bcsub($this->lastTimestamp, $timestamp);
            throw new RuntimeException("Clock moved backwards. Refusing to generate id for {$diffTimestamp} milliseconds");
        }

        if ((int) $this->lastTimestamp === (int) $timestamp) {
            $this->sequence = ($this->sequence + 1) & $this->sequenceMask;

            if ($this->sequence === 0) {
                $timestamp = $this->tilNextMillis($this->lastTimestamp);
            }
        } else {
            $this->sequence = rand(0, (pow(2, $this->sequenceBits) - 1));
        }

        $this->lastTimestamp = $timestamp;
        $timestamp           = ((floor($timestamp / $this->sequenceDivisor) - floor(self::EPOCH / $this->sequenceDivisor)));


        return (($timestamp << $this->timestampLeftShift) |
                ($this->datacenterId << $this->datacenterIdShift) |
                ($this->workerId << $this->workerIdShift) |
                $this->sequence);
    }

    /**
     * 等待直到下一个毫秒
     *
     * @param  int  $lastTimestamp  上一次的时间戳
     *
     * @return float 当前时间戳
     */
    protected function tilNextMillis(int $lastTimestamp) : float
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }

        return $timestamp;
    }

    /**
     * 获取当前时间戳（毫秒级别）
     *
     * @return float 当前时间戳（毫秒级别）
     */
    protected function timeGen() : float
    {
        return floor(microtime(true) * 1000);
    }

    private function maxWorkerId() : int
    {
        return -1 ^ (-1 << $this->workerIdBits);
    }

    private function maxDatacenterId() : int
    {
        return -1 ^ (-1 << $this->datacenterIdBits);
    }
}
