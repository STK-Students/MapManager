<?php

use Doctrine\Common\Collections\ArrayCollection;

$doctrineLoc = $_SERVER['DOCUMENT_ROOT'] . "/dependencies/Doctrine";
require_once "$doctrineLoc/Common/Collections/ArrayCollection.php";

class NestedAttributeUpdater
{
    /**
     * Updates a nested attribute (e.g. layers, style).
     * The problem is that only the context of a single instance of the nested attribute (e.g. one layer) is available.
     *
     * New attribute instances will be added.
     * Attribute instances not given in $layerData will be removed.
     * @param ArrayCollection $currentData The current nested section.
     * @param array $nestedData that contains new attribute data.
     * @return ArrayCollection an updated nested section
     */
    public static function setNestedAttribute(ArrayCollection $currentData, array $nestedData,  $addFunction): ArrayCollection
    {
        $sizeNewStyles = count($nestedData);
        $currentData = self::addInstance($sizeNewStyles, $nestedData, $currentData, $addFunction);
        return self::deleteRemovedInstances($currentData, $sizeNewStyles);
    }

   private static function addInstance(int $sizeNewStyles, array $styleData, ArrayCollection $currentStyles, $addFunction): ArrayCollection
    {
        for ($i = 0; $i < $sizeNewStyles; $i++) {
            if (!$currentStyles->containsKey($i)) {
                $currentStyles->add($addFunction($styleData[$i]));
            }
        }
        return $currentStyles;
    }

    private static function deleteRemovedInstances(ArrayCollection $currentStyles, int $sizeNewStyles): ArrayCollection
    {
        $sizeCurrentLayers = count($currentStyles);
        if ($sizeCurrentLayers > $sizeNewStyles) {
            $deletedLayers = $sizeCurrentLayers - $sizeNewStyles;
            for ($i = $sizeNewStyles; $i <= $sizeNewStyles + $deletedLayers; $i++) {
                unset($currentStyles[$i]);
            }
        }
        return $currentStyles;
    }
}