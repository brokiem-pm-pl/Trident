<?php

declare(strict_types=1);

namespace brokiem\Trident;

use brokiem\Trident\entity\projectile\Trident;
use brokiem\Trident\item\Trident as TridentItem;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;

class PMTrident extends PluginBase {

    public function onEnable(): void {
        ItemFactory::registerItem(new TridentItem(), true);
        Item::initCreativeItems();

        Entity::registerEntity(Trident::class, true, ["minecraft:trident", "Trident"]);
    }
}