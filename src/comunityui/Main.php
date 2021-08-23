<?php

namespace comunityui;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getLogger()->info("§aPlugin ComunityUI enable");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);

        @mkdir($this->getDataFolder());
       $this->saveDefaultConfig();
       $this->getResource("config.yml");
    }

    public function onLoad(){
        $this->getLogger()->info("§eLoading ComunityUI...");
    }

    public function onDisable(){
        $this->getLogger()->info("§cDisabling ComunityUI, FormAPI Don't Detected");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        switch($cmd->getName()){
            case "comunity":
                if($sender instanceof Player){
                    $this->openMyForm($sender);
                    return true;
                }else{
                    $sender->sendMessage("§cUse Command In Game");
                }
            break;
        }
        return true;
    }
    
    public function openMyForm($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $sender, int $data = null) {
            $result = $data;
            if($result == null){
                return true;
            }
            switch($result){
                case 0:
                    $sender->addTitle("§cGOODBYE", "ComunityUI");
                break;

                case 1:
                    $sender->sendMessage($this->getConfig()->get("MSG-WA"));
                break;

                case 2:
                    $sender->sendMessage($this->getConfig()->get("MSG-DC"));
                break;

            }
        });
        $form->setTitle($this->getConfig()->get("TITLE-UI"));
        $form->setContent($this->getConfig()->get("CONTENT-UI"));
        $form->addButton("§c§lExit", 0, "textures/ui/cancel");
        $form->addButton($this->getConfig()->get("BTN-WA"), 0, "textures/items/feather");
        $form->addButton($this->getConfig()->get("BTN-DC"), 0, "textures/items/book_writable");
        $form->sendToPlayer($sender);
        return $form;
    }
}