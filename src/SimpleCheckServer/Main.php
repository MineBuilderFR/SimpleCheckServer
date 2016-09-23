<?php

/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

namespace SimpleCheckServer;


use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Event;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;

class Main extends PluginBase implements Listener{
    
    public $IDTickServer = [];
    
	public function onEnable(){
        if(!is_dir($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        $this->Config = new Config($this->getDataFolder()."Config.yml",Config::YAML);
        
        if(!$this->Config->exists("InformationBroadcastInSecond")){
            $this->Config->set("InformationBroadcastInSecond", 3);
            $this->Config->save();
        }
        if(!$this->Config->exists("StopBroadcastAfterChecker")){
            $this->Config->set("StopBroadcastAfterChecker", 0);
            $this->Config->save();
        }
        if(!$this->Config->exists("AutoCheckServerWhenServerStart")){
            $this->Config->set("AutoCheckServerWhenServerStart", "true");
            $this->Config->save();
        }
        
        if(!is_numeric($this->Config->get("StopBroadcastAfterChecker"))){
            $this->getServer()->getLogger()->info("§4'StopBroadcastAfterChecker' is not numeric !");
            $this->Config->set("StopBroadcastAfterChecker", 0);
            $this->Config->save();
        }
        if($this->Config->get("AutoCheckServerWhenServerStart") !== "true" and $this->Config->get("AutoCheckServerWhenServerStart") !== "false"){
            $this->getServer()->getLogger()->info("§6AutoCheckServerWhenServerStart set to true !");
            $this->Config->set("AutoCheckServerWhenServerStart", "true");
            $this->Config->save();
        }
        
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        if($this->Config->get("AutoCheckServerWhenServerStart") == "true"){
            $this->CallSheduler();
        }else{
            $this->getServer()->getLogger()->info("§eCheckServer not check When Server start (Change in Config.yml)");
        }
        $this->getServer()->getLogger()->info("=>");
        $this->getServer()->getLogger()->info("§2SimpleCheckServer Enabled ! §6(By MineBuilderFR Y-M++)");
        $this->getServer()->getLogger()->info("=>");
        
    }
    
    public function Checker(){
        if($this->Config->get("StopBroadcastAfterChecker") == 0) return;
        $checker = abs($this->Config->get("StopBroadcastAfterChecker"));
        return $checker;
    }
    
    public function CallSheduler(){
        $timesec = $this->Config->get("InformationBroadcastInSecond");
        $rkey = rand(0,1000);
        $shedulerplug = new SheduleTickServer($this);
        $h = $this->getServer()->getScheduler()->scheduleRepeatingTask($shedulerplug, $timesec*20);
        $shedulerplug->setHandler($h);
        $this->IDTickServer["Server"] = $shedulerplug->getTaskId();
    }
    
    public function UnsetSheduler(){
        if(!isset($this->IDTickServer["Server"])) return;
        $this->getServer()->getScheduler()->cancelTask($this->IDTickServer["Server"]);
        unset($this->IDTickServer["Server"]);
    }
    public function onCommand(CommandSender $sender, Command $command,$label,array $args){
        switch($command->getName()){
           case "checkserver":{
               if(!isset($args[0]) or strtolower($args[0]) == "help"){
                   $sender->sendMessage("§6---------- HELP ------------");
                   $sender->sendMessage("- /checkserver start");
                   $sender->sendMessage("- /checkserver stop");
               }
               if(strtolower($args[0]) == "start"){
                   if(isset($this->IDTickServer["Server"])){
                       $sender->sendMessage("§4CheckServer Already Started !");
                       return;
                   }
                   $this->CallSheduler();
                   $sender->sendMessage("§2CheckServer been Started !");
               }
               
               if(strtolower($args[0]) == "stop"){
                   if(!isset($this->IDTickServer["Server"])){
                       $sender->sendMessage("§4CheckServer Already Stoped !");
                       return;
                   }
                   $this->UnsetSheduler();
                   $sender->sendMessage("§2CheckServer been Stoped !");
               }
                   
               break;
           }
        }
    }
    //Soon : Added Event
}