<?php

namespace BadPiggy;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EventListener implements Listener{
	public function __construct($plugin){
		$this->plugin = $plugin;
	}

    /**
     * @param BlockBreakEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if(isset($this->plugin->lavablock[strtolower($player->getName())])){
			unset($this->plugin->lavablock[strtolower($player->getName())]);
			$player->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(Block::LAVA));
			$event->setCancelled();
		}
		if(isset($this->plugin->exblock[strtolower($player->getName())])){
			unset($this->plugin->exblock[strtolower($player->getName())]);
			$explosion = new Explosion($player, 4, $player);
			$explosion->explodeA();
			$explosion->explodeB();
			$event->setCancelled();
		}
	}

	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof Player){
			if(isset($this->plugin->invoid[strtolower($entity->getName())])){
				if($event->getCause() == EntityDamageEvent::CAUSE_VOID){
					$event->setCancelled();
				}
			}
		}
	}

	public function onPickup(InventoryPickupItemEvent $event){
		$inventory = $event->getInventory();
		$holder = $inventory->getHolder();
		echo "Bye";
		if($holder instanceof Player){
			echo "Hi";
			if(isset($this->plugin->maim[strtolower($holder->getName())])){
				$event->setCancelled();
				echo "k";
			}
		}
	}

	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$message = $event->getmessage();
		if(isset($this->plugin->babble[strtolower($player->getName())])){
			$event->setMessage(str_shuffle($message));
		}
		if(isset($this->plugin->mute[strtolower($player->getName())])){
			$event->setCancelled();
		}
	}

	public function onMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		if(isset($this->plugin->freeze[strtolower($player->getName())])){
			$event->setCancelled();
		}
	}

}