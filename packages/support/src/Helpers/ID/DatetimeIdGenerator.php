<?php

namespace RedJasmine\Support\Helpers\ID;

use Exception;
use RuntimeException;

class DatetimeIdGenerator
{

    private int              $workerIdBits;
    private int              $datacenterIdBits;
    private int              $sequenceBits;
    private int              $workerId; // 工作机器ID
    private int              $datacenterId; // 数据中心ID
    private int              $sequence; // 毫秒内序列
    private int              $lastTimestamp = -1; // 上次生产id时间戳
    private static self|null $instance      = null;


    private int $maxWorkerId;
    private int $maxDatacenterId;
    private int $workerIdShift;
    private int $datacenterIdShift;

    public function __construct(
        ?int $workerId = null,
        ?int $datacenterId = null,
        int $workerIdBits = 10,
        int $datacenterIdBits = 10,
        int $sequenceBits = 12,
    ) {


        $this->workerIdBits     = $workerIdBits;
        $this->datacenterIdBits = $datacenterIdBits;
        $this->sequenceBits     = $sequenceBits;
        $this->workerId         = $workerId ?? rand(0, $this->maxWorkerId());
        $this->datacenterId     = $datacenterId ?? rand(0, $this->maxDatacenterId());

        if ($this->workerId < 0 || $this->workerId > $this->maxWorkerId()) {
            throw new RuntimeException("worker Id can't be greater than {$this->maxWorkerId()} or less than 0");
        }

        if ($this->datacenterId < 0 || $this->datacenterId > $this->maxDatacenterId()) {
            throw new RuntimeException("datacenter Id can't be greater than {$this->maxDatacenterId()} or less than 0");
        }


        $this->calculateShiftsAndMasks();
    }

    private function calculateShiftsAndMasks() : void
    {
        $this->maxWorkerId       = -1 ^ (-1 << $this->workerIdBits);
        $this->maxDatacenterId   = -1 ^ (-1 << $this->datacenterIdBits);
        $this->workerIdShift     = $this->sequenceBits;
        $this->datacenterIdShift = $this->sequenceBits + $this->workerIdBits;
        $this->sequenceMask      = -1 ^ (-1 << $this->sequenceBits);

    }

    public static function getInstance(
        int|null $workerId = null,
        int|null $datacenterId = null,
        int $workerIdBits = 10,
        int $datacenterIdBits = 12,
        int $sequenceBits = 12,
    ) : ?self {
        if (self::$instance === null) {
            try {
                self::$instance = new self($workerId, $datacenterId, $workerIdBits, $datacenterIdBits, $sequenceBits);
            } catch (Exception $e) {
                // 处理异常
            }
        }
        return self::$instance;
    }

    public static function buildId(
        int|null $workerId = null,
        int|null $datacenterId = null,
        int $workerIdBits = 10,
        int $datacenterIdBits = 12,
        int $sequenceBits = 12,
    ) : string {
        return static::getInstance($workerId, $datacenterId, $workerIdBits, $datacenterIdBits, $sequenceBits)->nextId();
    }


    public function nextId() : string
    {
        // 获取时间
        $timestamp = $this->timeGen();

        // 判断是中是否 回拨
        if ($timestamp < $this->lastTimestamp) {
            $diffTimestamp = bcsub($this->lastTimestamp, $timestamp);
            throw new RuntimeException("Clock moved backwards. Refusing to generate id for {$diffTimestamp} milliseconds");
        }
        // 判断时间是否一样
        if ($this->lastTimestamp == $timestamp) {
            $this->sequence = ($this->sequence + 1) & $this->sequenceMask;
            if ($this->sequence === 0) {
                $timestamp = $this->tilNextMillis($this->lastTimestamp);
            }
        } else {
            // 重新生成随机数
            $this->sequence = rand(0, (pow(2, $this->sequenceBits) - 1));
        }
        $this->lastTimestamp = $timestamp;

        // 17 位
        $datetime = date('YmdHis', (int) (floor($timestamp / 1000))).substr((int) $timestamp, -3);
        // 序号 8 位
        $sequence = (($this->datacenterId << $this->datacenterIdShift) |
                     ($this->workerId << $this->workerIdShift) |
                     $this->sequence);
        // php 给定一个整数  如何获取整数的 位数

        $len            = strlen(pow(2, ($this->datacenterIdShift + $this->datacenterIdBits)));
        $sequenceNumber = sprintf("%0{$len}d", $sequence);

        return (string) ($datetime.$sequenceNumber);

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
