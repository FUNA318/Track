<?php

namespace Track;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\scheduler\Task;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getLogger()->info("§aTrackを二次配布するのは禁止です");
        $this->getLogger()->info("§bBy R_Funa");
		    $this->getServer()->getPluginManager()->registerEvents($this, $this);

	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$cmd = $command->getName();
		switch($cmd){
			case "track":
			if(!isset($args[0])){
              $sender->sendMessage("§cTrackを実行できません : 相手プレイヤーが存在しない");
              break;
            }
            $p = $this->getServer()->getPlayer($sender->getName());
            if(!$p->isOp()){
            	$sender->sendMessage("§cTrackを実行できません : 権限がない");
              break;
            }
            $m = $this->getServer()->getPlayer($args[0]);
            if($m !== NULL){
            	$p->setGamemode(3);
            	$p->save();
            	$x = $m->getX();
            	$y = $m->getY();
            	$z = $m->getZ();
            	$vector = new Vector3($x, $y, $z);
            	$p->teleport($vector);
            	$n = $m->getName();
            	$this->track[$n] = $p;
            	$this->player[$sender->getName()] = $m;
            	$players = Server::getInstance()->getOnlinePlayers();
            	$this->getLogger()->info("§bTrackを実行 ".$sender->getName()." が ".$m->getName()." へ");
   foreach ($players as $player) {
   	if($player->isOp()){
   		$player->sendMessage("§bTrackを実行 ".$sender->getName()." が ".$m->getName()." へ");
   	}
   }
  }else{
            	$sender->sendMessage("§cTrackを実行できません : 相手プレイヤーが存在しない");
            }
            return true;
            break;
            case "tfin":
            if(isset($this->player[$sender->getName()])){
			$t = $this->player[$sender->getName()];
			$n = $p->getName();
			$this->track[$n] == NULL;
			$this->player[$sender->getName()] == NULL;
			$sender->sendMessage("§bTrackを終了 : 強制終了");
		}else{
			$sender->sendMessage("§cTrackを実行できません : 相手プレイヤーが存在しない");
		}
            return true;
            break;
	}
}
	public function toTrack($t, $p){
		/*
		$this->toTrack(追跡する相手のプレイヤーのオブジェクト,追跡するプレイヤーのオブジェクト);
		*/
		$x = $t->getX();
		$y = $t->getY();
		$z = $t->getZ();
		$pos = new Vector3($x, $y, $z);
		$p->teleport($pos);
	}
	public function onMove(PlayerMoveEvent $e){
		$t = $e->getPlayer();
		$n = $t->getName();
		if(isset($this->track[$n])){
			$p = $this->track[$n];
			$this->toTrack($t,$p);
		}
	}
	public function onQuit(PlayerQuitEvent $e){
		$p = $e->getPlayer();
		$n = $p->getName();
		if(isset($this->track[$n])){
			$this->track[$n]->sendMessage("§bTrackを終了 : 相手プレイヤーが退出");
			$this->track[$n] == NULL;
			$na = $this->track[$n]->getName();
			$this->player[$na] == NULL;
		}
	}
}