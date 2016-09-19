<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\helpers;

use Yii;
use DateTime;
use DateTimeZone;
use yii\helpers\ArrayHelper;

/**
 * 时区助手
 */
class Timezone
{
    /**
     * Get all of the time zones with the offsets sorted by their offset
     * @return array
     */
    public static function getAll()
    {
        $timezones = [];
        $identifiers = DateTimeZone::listIdentifiers();
        foreach ($identifiers as $identifier) {
            $date = new DateTime("now", new DateTimeZone($identifier));
            $offsetText = $date->format("P");
            $offsetInHours = $date->getOffset() / 60 / 60;
            $timezones[] = [
                "identifier" => $identifier,
                "name" => "(GMT{$offsetText}) $identifier",
                "offset" => $offsetInHours
            ];
        }
        ArrayHelper::multisort($timezones, "offset", SORT_ASC, SORT_NUMERIC);
        return $timezones;
    }

}