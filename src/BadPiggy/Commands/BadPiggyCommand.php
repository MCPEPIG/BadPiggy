<?php

namespace BadPiggy\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BadPiggyCommand extends VanillaCommand{
	public function __construct($name, $plugin){
		parent::__construct(
			$name, "Troll a player", "/badpiggy <player> <punishment>"
		);
		$this->setPermission("badpiggy.command");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }
        if(count($args) < 3){
            $sender->sendMessage("/badpiggy <player> <punishment>");
            return false;
        }
        $player = $this->plugin->getServer()->getPlayer($args[0]);
        if(!$player instanceof Player){
            $sender->sendMessage("§cInvalid player.");
        }
        switch(strtolower($args[1])){
            case "fall":
                if($sender->hasPermission("badpiggy.command.fall")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fall($player);
                $sender->sendMessage("§a" . $player->getName() . " is now falling to their deaths.");
                break;
            case "explode":
                if($sender->hasPermission("badpiggy.command.explode")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->explode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "burn":
                if($sender->hasPermission("badpiggy.command.burn")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])){
                    $sender->sendMessage("/badpiggy burn <seconds>");
                }
                if(!is_numeric($args[2])){
                    $sender->sendMessage("Seconds must be numeric.");
                }
                $this->plugin->burn($player, $arg[2]);
                $sender->sendMessage("§a" . $player->getName() . " is becoming human bacon.");
                break;
            default:
                $sender->sendMessage("§cInvalid Punishments");
                break;
        }
        return true;
	}

}
