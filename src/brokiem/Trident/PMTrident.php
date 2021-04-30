<?php

declare(strict_types=1);

namespace brokiem\Trident;

use brokiem\Trident\entity\projectile\Trident;
use brokiem\Trident\item\Trident as TridentItem;
use pocketmine\entity\Entity;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;

class PMTrident extends PluginBase {

    /** @var PMTrident */
    private static $i;

    public static function getInstance(): self {
        return self::$i;
    }

    public function onEnable(): void {
        self::$i = $this;

        Enchantment::registerEnchantment(new Enchantment(Enchantment::LOYALTY, "%enchantment.loyalty", Enchantment::RARITY_RARE, Enchantment::SLOT_NONE, Enchantment::SLOT_ALL, 1));

        ItemFactory::registerItem(new TridentItem(), true);
        Item::initCreativeItems();

        Entity::registerEntity(Trident::class, true, ["minecraft:trident", "Trident"]);
    }
}