<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Plugin\Elasticsearch;

abstract class AdditionalFieldMapper
{
    const ES_DATA_TYPE_STRING = 'string';
    const ES_DATA_TYPE_FLOAT = 'float';
    const ES_DATA_TYPE_INT = 'integer';
    const ES_DATA_TYPE_DATE = 'date';

    /**
     * @var array
     */
    private $fields = [];

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function updateFields(array $fields): array
    {
        foreach ($this->fields as $fieldName => $fieldType) {
            if (empty($fieldName)) {
                continue;
            }
            if ($this->isValidFieldType($fieldType)) {
                $fields[$fieldName] = ['type' => $fieldType];
            }
        }

        return $fields;
    }

    /**
     * @param $fieldType
     * @return bool
     */
    private function isValidFieldType($fieldType)
    {
        switch ($fieldType) {
            case self::ES_DATA_TYPE_STRING:
            case self::ES_DATA_TYPE_DATE:
            case self::ES_DATA_TYPE_INT:
            case self::ES_DATA_TYPE_FLOAT:
                break;
            default:
                $fieldType = false;
                break;
        }

        return $fieldType;
    }
}
