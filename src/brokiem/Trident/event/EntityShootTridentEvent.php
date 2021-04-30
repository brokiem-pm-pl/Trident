<?php

declare(strict_types=1);

namespace brokiem\Trident\event;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\Cancellable;
use pocketmine\event\entity\EntityEvent;
use pocketmine\item\Item;
use function count;

class EntityShootTridentEvent extends EntityEvent implements Cancellable{
    /** @var Item */
    private $trident;
    /** @var Projectile */
    private $projectile;
    /** @var float */
    private $force;

    public function __construct(Living $shooter, Item $trident, Projectile $projectile, float $force){
        $this->entity = $shooter;
        $this->trident = $trident;
        $this->projectile = $projectile;
        $this->force = $force;
    }

    public function getTrident() : Item{
        return $this->trident;
    }

    /**
     * Returns the entity considered as the projectile in this event.
     *
     * NOTE: This might not return a Projectile if a plugin modified the target entity.
     */
    public function getProjectile() : Entity{
        return $this->projectile;
    }

    public function setProjectile(Entity $projectile) : void{
        if($projectile !== $this->projectile){
            if(count($this->projectile->getViewers()) === 0){
                $this->projectile->close();
            }
            $this->projectile = $projectile;
        }
    }

    public function getForce() : float{
        return $this->force;
    }

    public function setForce(float $force) : void{
        $this->force = $force;
    }
}