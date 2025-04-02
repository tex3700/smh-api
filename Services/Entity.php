<?php

namespace Services;

use ApiClient;
use database\DbHandler;

abstract class Entity {
    protected ApiClient $apiClient;
    protected DbHandler $dbHandler;

    public function __construct(ApiClient $apiClient, DbHandler $dbHandler) {
        $this->apiClient = $apiClient;
        $this->dbHandler = $dbHandler;
    }

    abstract protected function getApiEndpoint(): string;
    abstract protected function getDbTableName(): string;
    abstract protected function mapFields(array $data): array;
    abstract protected function getUniqueIdField(): string;

    public function fetchByKeyword(string $keyword): array
    {
        $response = $this->apiClient->get(
            '/' . $this->getApiEndpoint() . '/search',
            ['q' => $keyword, 'limit' => 100]
        );
        return $response[$this->getApiEndpoint()] ?? [];
    }

    public function saveToDb(array $data): bool
    {
        $mappedData = $this->mapFields($data);

        if (!$this->dbHandler->exists(
            $this->getDbTableName(),
            [$this->getUniqueIdField() => $mappedData[$this->getUniqueIdField()]]
        )) {
            return $this->dbHandler->insert(
                $this->getDbTableName(),
                $mappedData
            );
        }
        return false;
    }

    //Добавил оптимизированный метод сохранения
    public function batchSaveToDb(array $items): bool
    {
        $mappedData = [];
        foreach ($items as $item) {
            if (!$this->dbHandler->exists(
                $this->getDbTableName(),
                [$this->getUniqueIdField() => $item['id']]
            )) {
                $mappedData[] = $this->mapFields($item);
            }
        }

        if (!empty($mappedData)) {
            return $this->dbHandler->batchInsert(
                $this->getDbTableName(),
                $mappedData
            );
        }

        return true;
    }

    public function addNew(array $data): array
    {
        $response = $this->apiClient->post(
            '/' . $this->getApiEndpoint() . '/add',
            $data
        );

        if (!empty($response['id'])) {
            $this->saveToDb($response);
        }

        return $response;
    }

}
