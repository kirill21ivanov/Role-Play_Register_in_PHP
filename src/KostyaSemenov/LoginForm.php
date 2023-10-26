<?php

namespace KostyaSemenov;
use KostyaSemenov\RPR;
use pocketmine\Player;

class LoginForm{
	
    private $plug;
    function __construct(RPR $plug){
        $this->plug = $plug;
    }
    function open(Player $pl){
        $f = $this->plug->form->createCustomForm(function (Player $pl, $data){
			if($data[0] !== NULL && $data[1] !== NULL){
					//data[0] - ник, data[1] - пароль
					$cfg = $this->plug->cfg->getAll();
						$number = $this->plug->getNumberAccount($data[0]);
					if($number !== NULL){
						if($data[0] == $cfg[$number]["nick"] && $data[1] == $cfg[$number]["password"]){
							$pl->sendMessage("§l§aВы успешно авторизировались!");
							$pl->setDisplayName($data[0]);
							$pl->setNameTag($data[0]);
							$pl->setImmobile(false);
						} else $pl->kick("Неверный ник или пароль");
					} else $pl->kick("Такого пользователя не существует!");
			} else $this->open($pl);
        });
        $f->setTitle("Авторизируйтесь");
        $f->addInput("Ник", "Например: Ivan_Ivanov");
        $f->addInput("Пароль", "Пароль к аккаунту");
        $f->sendToPlayer($pl);
        return $f;
    }
}
