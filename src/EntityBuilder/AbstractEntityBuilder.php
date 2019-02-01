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
            'createdDate' => $entity->getCreatedDate()->format('Y-m-d h:i:s'),
            'updatedDate' => is_null($entity->getUpdatedDate()) ? null : $entity->getUpdatedDate()->format('Y-m-d h:i:s'),
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