<?php

namespace App\EntityBuilder;

use App\Entity\AbstractEntity;

abstract class AbstractEntityBuilder
{
    /**
     * @param AbstractEntity $entity
     * @return array
     */
    public static function toDefaultArray(AbstractEntity $entity): array
    {
        return [
            'id' => $entity->getId(),
            'createdDate' => $entity->getCreatedDate(),
            'updatedDate' => $entity->getUpdatedDate(),
        ];
    }

    /**
     * @param array $list
     * @return array
     */
    public static function toListArray(array $list = []): array
    {
        return [
            'totalItems' => count($list),
            'items' => $list
        ];
    }
}