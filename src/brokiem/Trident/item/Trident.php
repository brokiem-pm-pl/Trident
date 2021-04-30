<?php

declare(strict_types=1);

namespace brokiem\Trident\item;

use brokiem\Trident\entity\projectile\Trident as TridentEntity;
use pocketmine\entity\Entity;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Tool;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

// https://minecraft.fandom.com/wiki/Trident
class Trident extends Tool {

    public function __construct(int $meta = 0) {
        parent::__construct(self::TRIDENT, $meta, "Trident");
    }

    public function getMaxDurability(): int {
        return 251;
    }

    public function onAttackEntity(Entity $victim): bool {
        return $this->applyDamage(1);
    }

    public function getAttackPoints(): int {
        return 9;
    }

    public function onReleaseUsing(Player $player): bool {
        $diff = $player->getItemUseDuration();
        $p = $diff / 20;
        $force = min(($p * $p + $p * 2) / 3, 1) * 2;

        if ($force < 0.1 or $diff < 5) {
            return false;
        }

        if ($player->isSurvival()) {
            $this->applyDamage(1);
            $this->pop();
        }

        $player->getLevelNonNull()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_ITEM_TRIDENT_THROW);

        if ($this->hasEnchantment(Enchantment::RIPTIDE)) {
            $player->setGenericFlag(Entity::DATA_FLAG_SPIN_ATTACK);
            return true;
        }

        $nbt = Entity::createBaseNBT($player->add(0, $player->getEyeHeight()), $player->getDirectionVector(), ($player->yaw > 180 ? 360 : 0) - $player->yaw, -$player->pitch);
        $entity = new TridentEntity($player->getLevelNonNull(), $nbt, $player);
        $entity->namedtag->setInt("trident_damage", $this->meta);
        foreach ($this->getEnchantments() as $enchantment) {
            $entity->addEnchantment($enchantment);
        }
        $entity->spawnToAll();

        return true;
    }
}