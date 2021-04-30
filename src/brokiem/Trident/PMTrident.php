<?php

declare(strict_types=1);

namespace brokiem\Trident;

use brokiem\Trident\entity\projectile\Trident;
use brokiem\Trident\item\Trident as TridentItem;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\plugin\PluginBase;

class PMTrident extends PluginBase implements Listener {

    /** @var PMTrident */
    private static $i;

    public static function getInstance(): self {
        return self::$i;
    }

    public function onEnable(): void {
        self::$i = $this;

        Enchantment::registerEnchantment(new Enchantment(Enchantment::LOYALTY, "%enchantment.loyalty", Enchantment::RARITY_UNCOMMON, Enchantment::SLOT_NONE, Enchantment::SLOT_ALL, 3));
        Enchantment::registerEnchantment(new Enchantment(Enchantment::RIPTIDE, "%enchantment.riptide", Enchantment::RARITY_RARE, Enchantment::SLOT_NONE, Enchantment::SLOT_ALL, 3));

        ItemFactory::registerItem(new TridentItem(), true);
        Item::initCreativeItems();

        Entity::registerEntity(Trident::class, true, ["minecraft:trident", "Trident"]);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDataPacketRecieve(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        $player = $event->getPlayer();

        if ($packet instanceof PlayerActionPacket) {
            if ($packet->action === PlayerActionPacket::ACTION_START_SPIN_ATTACK) {
                $player->setGenericFlag(Entity::DATA_FLAG_SPIN_ATTACK, true);
            }

            if ($packet->action === PlayerActionPacket::ACTION_STOP_SPIN_ATTACK) {
                $player->setGenericFlag(Entity::DATA_FLAG_SPIN_ATTACK, false);
            }
        }
    }
}