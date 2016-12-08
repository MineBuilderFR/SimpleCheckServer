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

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\Event;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

class SheduleTickServer extends PluginTask{

   private $plugin;
    
   public $Checker = 0;
    
   public function __construct(Main $plugin){
     parent::__construct($plugin);
     $this->plugin = $plugin;
   }
    
    public function onRun($currentTick){
        $this->Checker = $this->Checker + 1;
        $this->plugin->getServer()->getLogger()->info("§2===========================");
        $this->plugin->getServer()->getLogger()->info("Server Tick Usage Average : {$this->plugin->getServer()->getTickUsageAverage()}");
        $this->plugin->getServer()->getLogger()->info("Server Tick Usage : {$this->plugin->getServer()->getTickUsage()}");
        $this->plugin->getServer()->getLogger()->info("Server TickAverage : {$this->plugin->getServer()->getTicksPerSecondAverage()}");
        $this->plugin->getServer()->getLogger()->info("Server Tick : {$this->plugin->getServer()->getTicksPerSecond()}");
        $this->plugin->getServer()->getLogger()->info("Checker = " . $this->Checker);
        $this->plugin->getServer()->getLogger()->info("§2===========================");
        if($this->Checker == $this->plugin->Checker()){
            $this->plugin->UnsetSheduler();
            $this->plugin->getServer()->getLogger()->info("§6ServerCheckServer Been stoped !");
        }
    }
}
