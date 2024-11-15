<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    const statuses = [
        'accepted' => 'accepted',
        'success' => 'success',
        'warning' => 'warning',
        'fail' => 'fail',
        'denied' => 'denied',
        'error' => 'error',
        'expire' => 'expire',
    ];

    const HTTP_STATUS_CODE = [
        'success' => 200,
        'accepted' => 202,
        'bad_request' => 400,
        'unauthorized' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'conflict' => 409,
        'unprocessable_entity' => 422,
        'too_many_attempts' => 429,
        'serverError' => 500,
    ];

    private string $status;
    private int $httpStatus;
    private string $message;
    private array $data;
    private array $headers = [];
    private array $pagination;

    function __construct(array $data = [])
    {
        $this->httpStatus = $data['httpStatus'] ?? self::HTTP_STATUS_CODE['success'];
        $this->data = $data['data'] ?? [];
        $this->pagination = $data['pagination'] ?? null;
        $this->status = $data['status'] ?? self::statuses['success'];
    }


    public function json(int|null $httpStatus = null)
    {
        $data = [
            'status' => $this->getStatus(),
            'message' => $this->getMessage(),
            'pagination' => $this->getPagination(),
            'data' => $this->getData(),
        ];

        return response()->json($data, $httpStatus ?? $this->httpStatus, $this->headers, JSON_UNESCAPED_UNICODE);
    }


    public function getStatus()
    {
        if ($this->status) {
            return self::statuses[$this->status] ?? $this->status;
        }
        return null;
    }


    public function setStatus(string $status, int $httpStatus = self::HTTP_STATUS_CODE['success'])
    {
        $this->status = strtolower($status);
        if ($httpStatus) {
            $this->setHttpStatus($httpStatus);
        }

        return $this;
    }


    public function getHttpStatus()
    {
        if ($this?->httpStatus) {
            return self::HTTP_STATUS_CODE[$this->httpStatus] ?? $this->httpStatus;
        }

        return null;
    }


    public function getMessage()    
    {
        return $this->message ?? 'success';
    }


    public function getPagination()
    {
        return $this->pagination;
    }


    public function setPagination($pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }


    public function getData($index = null)
    {
        return $index == null ? $this->data : $this->data[$index] ?? null;
    }


    public function setData($index, $value = null)
    {
        if (is_array($index)) {
            array_push($this->data, $index);
        } else if (is_object($value) && $value instanceof LengthAwarePaginator) {
            $this->pagination = PaginationHelper::getPaginationInfo($value);
            $this->data[$index] = PaginationHelper::getData($value);
        } else {
            $this->data[$index] = $value;
        }

        return $this;
    }


    public function setHttpStatus($status)
    {
        $this->httpStatus = intval($status);

        return $this;
    }


    public function setMessage($message)
    {
        $this->message = trim($message);

        return $this;
    }

    public function clearAllData()
    {
        $this->data = [];
        return $this;
    }


    public function getHeader($index = null)
    {
        return $index == null ? $this->headers : $this->headers[$index] ?? null;
    }


    public function setHeader($index, $value = null)    
    {
        if (is_array($index)) {
            array_push($this->headers, $index);
        } else {
            $this->headers[$index] = $value;
        }

        return $this;
    }
}
