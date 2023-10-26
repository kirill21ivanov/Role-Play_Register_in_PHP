<?php

namespace 1;

use pocketmine\plugin\PluginBase;
//События
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
//
use pocketmine\utils\Config;
use pocketmine\Player;

class RPR extends PluginBase implements Listener {
	
	public $cfg;
	public $all;
	public $form;
	
	public function onEnable(){
		//Создание конфига
		if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		$this->cfg = new Config($this->getDataFolder()."accounts.yml");
		$this->all = new Config($this->getDataFolder()."allaccounts.yml");           //В конфиге allaccounts не изменяй ничего!!
		$all = $this->all->getAll();
		if(!isset($all["all"])){
			$all["all"] = 0;
			$this->all->setAll($all);
			$this->all->save();
		}
		//
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		// Подключение ФОРМ АПИ. Если у вас его нет, идите нахуй
		$this->form = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		//
		$this->getLogger()->info("Автор плугина - Канстантин Симонов");
	}
	
	public function onJoooooiiiiinNaxuuuy(PlayerJoinEvent $e){
		$p = $e->getPlayer();
		$n = $p->getName();
		$cfg = $this->cfg->getAll();
		$this->open($p);
		$p->setImmobile(true);
	}
	
	public function open(Player $pl){
		$f = $this->form->createSimpleForm(function (Player $pl, $data){
			if($data !== NULL){
					//data[0] - ник, data[1] - пароль
					$cfg = $this->cfg->getAll();
						switch($data){
							case 0:
								$login = new LoginForm($this);
								$login->open($pl);
									break;
							case 1:
								$reg = new RegisterForm($this);
								$reg->open($pl);
									break;
						}
			} else $this->open($pl);
        });
        $f->setTitle("Выберите");
        $f->addButton("Авторизация");
        $f->addButton("Регистрация");
        $f->sendToPlayer($pl);
        return $f;
	}
	
	public function getNumberAccount($name){
		foreach($this->cfg->getAll() as $key => $data){
			if($data["nick"] == $name){
				return $key;
			}
		}
	}
	
	public function getIssetNumberAccount($number){  //0 - истина, 1 - ложь
		$cfg = $this->cfg->getAll();
		foreach($this->cfg->getAll() as $key){
			if(isset($cfg[$number])){
				return 0;	
			} else return 1;
		}
	}
	
	}